<?php

declare(strict_types=1);

/**
 * This file is part of the DEBs-to-SQL package
 *
 * https://github.com/VitexSoftware/DEBs-to-SQL
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebToSQL;

/**
 * Description of Repository.
 *
 * @author vitex
 */
class Repository extends \Ease\SQL\Engine
{
    public array $dists = [];
    public string $repoDir = '';

    /**
     * @var array of packages
     */
    public array $packages = [];
    private array $archs = [];
    private array $skiplist = [];
    private string $poolDir;
    private array $suites = [];

    /**
     * @var array<string> of architectures
     */
    private array $arch = [];

    /**
     * Repository handler.
     *
     * @param array<string> $skiplist
     */
    public function __construct(array $skiplist = []) /* : \DirectoryIterator */
    {
        $this->myTable = 'packages';
        $this->setObjectName();
        parent::__construct();
        $this->repoDir = \Ease\Shared::cfg('REPO_DIR');
        $this->poolDir = $this->repoDir.'pool/';
        $this->skiplist = $skiplist;

        if (is_dir($this->repoDir.'dists/')) {
            foreach (new \DirectoryIterator($this->repoDir.'dists/') as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }

                if (!$fileInfo->isReadable()) {
                    continue;
                }

                if (strstr($fileInfo->getFilename(), '-')) {
                    continue;
                }

                $this->dists[$fileInfo->getFilename()] = $this->repoDir.'dists/'.$fileInfo->getFilename();
            }
        } else {
            $this->addStatusMessage(sprintf(_('dists not found in %s'), $this->repoDir), 'warning');
        }
    }

    /**
     * Search for Distributions in Repository.
     */
    public function parseDists(): void
    {
        $this->logBanner(\Ease\Shared::appName(), 'in: '.$this->repoDir);

        foreach (array_keys($this->dists) as $dist) {
            $this->parseDist($dist);
        }
    }

    /**
     * Search for Suites in Distribution.
     *
     * @return array list of architectures found
     */
    public function parseDist(string $distName): array
    {
        $this->addStatusMessage(_('Parsing distro').': '.$distName);
        $suites = $this->parseSuites($distName);

        foreach (array_keys($suites) as $suite) {
            $this->archs = array_merge(
                $this->archs,
                $this->parseArchs($distName, $suite),
            );
        }

        return array_keys($this->archs);
    }

    /**
     * Parse suites in given Distribution.
     *
     * @return array<string> list of suites found
     */
    public function parseSuites(string $distName): array
    {
        foreach (new \DirectoryIterator($this->dists[$distName]) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if (!$fileInfo->isReadable()) {
                continue;
            }

            if (strstr($fileInfo->getFilename(), '-')) {
                continue;
            }

            if (!$fileInfo->isDir()) {
                continue;
            }

            $suite = $fileInfo->getFilename();
            $this->addStatusMessage($distName.': suite found: '.$suite);
            $this->suites[$distName][$suite] = $this->dists[$distName].'/'.$suite;
        }

        return $this->suites[$distName];
    }

    /**
     * Parse Architectures in dist/suite.
     *
     * @param string $distName
     * @param string $suite
     *
     * @return array archs found
     */
    public function parseArchs($distName, $suite)
    {
        foreach (new \DirectoryIterator($this->dists[$distName].'/'.$suite) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if (!$fileInfo->isReadable()) {
                continue;
            }

            if (!$fileInfo->isDir()) {
                continue;
            }

            $arch = $fileInfo->getFilename();
            $this->addStatusMessage('Architecture found: '.$distName.'/'.$suite.': '.$arch);
            $this->arch[$distName][$suite][$arch] = $this->dists[$distName].'/'.$suite.'/'.$arch;
            $packages = $this->arch[$distName][$suite][$arch].'/Packages';

            if (file_exists($packages)) {
                if (
                    !empty(\Ease\Shared::cfg('SKIP')) && strstr(
                        $packages,
                        \Ease\Shared::cfg('SKIP'),
                    )
                ) {
                    $this->addStatusMessage('Private repo '.$packages.' skipped');

                    continue;
                }

                $this->packages[$distName][$suite][$arch] = $this->readpackages($packages);
                $this->addStatusMessage(
                    'Found '.\count($this->packages[$distName][$suite][$arch]).' packages in '.$distName.'/'.$suite.'/'.$arch,
                    'success',
                );
            }
        }

        return $this->arch[$distName][$suite];
    }

    /**
     * Filer only knonw fields.
     *
     * @param array $dataRaw
     *
     * @return array
     */
    public function onlyKnownColumns($dataRaw)
    {
        $dataOriginal = $dataRaw;
        $dataFiltered = [];
        $knownColumns = ['Name', 'Package', 'Appname', 'Essential', 'Vendor', 'License',
            'Distribution', 'Suite', 'Source', 'Version', 'Architecture', 'MultiArch',
            'Maintainer', 'InstalledSize', 'Depends', 'PreDepends', 'Breaks', 'Enhances', 'Replaces', 'Section', 'Priority',
            'Description', 'LongDescription', 'AutoBuiltPackage', 'Filename', 'MD5sum',
            'SHA1', 'SHA256', 'SHA512', 'Size', 'AutoBuiltPackage', 'Conflicts', 'Homepage', 'Provides', 'Recommends',
            'Suggests', 'Exists', 'fileMtime', 'created', 'Existing',
        ];

        foreach ($knownColumns as $column) {
            \Ease\Functions::divDataArray($dataRaw, $dataFiltered, $column);
        }

        $originalKeys = array_keys($dataOriginal);
        $filteredKeys = array_keys($dataFiltered);

        if (\count($originalKeys) !== \count($filteredKeys)) {
            $this->addStatusMessage('Unknown column: '.implode(
                ',',
                array_diff($originalKeys, $filteredKeys),
            ), 'warning');
        }

        return $dataFiltered;
    }

    /**
     * @return array<string, array<string, null|bool|int|string>>
     */
    public function readpackages(string $pkgFile): array
    {
        $packages = [];
        $pName = null;
        $handle = fopen($pkgFile, 'rb');
        $position = 0;

        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                if (!strstr($buffer, ': ')) {
                    continue;
                }

                [$key, $value] = explode(': ', $buffer);

                switch ($key) {
                    case 'Package':
                        $pName = trim($value);
                        ++$position;
                        $packages[$pName]['fileMtime'] = time();

                        break;
                    case 'Description':
                        $packages[$pName][$key] = trim($value);
                        $packages[$pName]['LongDescription'] = '';

                        while (($buffer = fgets($handle, 4096)) !== false) {
                            if (trim($buffer)) {
                                if ($buffer[0] === ' ') {
                                    $packages[$pName]['LongDescription'] .= trim($buffer);
                                } else {
                                    if (strstr($buffer, ':')) {
                                        [$key, $value] = preg_split(
                                            '/:/',
                                            $buffer,
                                            2,
                                        );
                                        $packages[$pName][$key] = trim(
                                            $value,
                                            " \t\n\r\0\x0B'\"",
                                        );
                                    } else {
                                        $this->addStatusMessage(
                                            $pName.' - '._('Unknown field').': '.$buffer,
                                            'warning',
                                        );
                                    }

                                    break;
                                }
                            }
                        }

                        break;
                    case 'fileMtime':
                        echo '';

                        break;
                    case 'Filename':
                        $fpparts = explode('/', $value);
                        $distro = $fpparts[1];
                        $section = $fpparts[2];
                        $origFile = $this->poolDir.'/'.$distro.'/'.($section === 'main' ? '' : $section).'/'.$pName;
                        $packages[$pName]['fileMtime'] = file_exists($origFile) ? filemtime($origFile) : null;
                        $packages[$pName]['Existing'] = file_exists($origFile) ? 1 : 0;

                        // no break
                    default:
                        $packages[$pName][str_replace('-', '', $key)] = trim($value);

                        break;
                }

                $packages[$pName]['Name'] = $pName;
            }

            if (!feof($handle)) {
                $this->addStatusMessage('unexpected fgets() fail', 'error');
            }

            fclose($handle);
        }

        return $packages;
    }

    /**
     * Save data to Database.
     */
    public function saveAllToSQL(): void
    {
        $saved = [];

        foreach ($this->packages as $dist => $suites) {
            foreach ($suites as $suite => $suiteData) {
                foreach ($suiteData as $arch => $packages) {
                    foreach ($packages as $packageData) {
                        $packageData['Distribution'] = $dist;
                        $packageData['Suite'] = $suite;
                        $packageData['Architecture'] = $arch;

                        if (\array_key_exists('fileMtime', $packageData) && $packageData['fileMtime']) {
                            $packageData['fileMtime'] = (new \DateTime())->setTimestamp($packageData['fileMtime'])->format('Y-m-d H:i:s');
                        } else {
                            unset($packageData['fileMtime']);
                        }

                        unset($packageData['Build-Ids']);

                        if (
                            \array_key_exists('Filename', $packageData) && empty($this->getColumnsFromSQL(
                                ['id'],
                                ['Filename' => $packageData['Filename']],
                            ))
                        ) {
                            if (file_exists($this->repoDir.'/'.$packageData['Filename'])) {
                                $this->setMyKey(null);

                                if ($this->dbsync($this->onlyKnownColumns($packageData))) {
                                    $this->indexPackageContents($packageData);
                                    $saved[] = $packageData['Name'];
                                }
                            } else {
                                $this->addStatusMessage(
                                    sprintf(
                                        _('File %s not found'),
                                        $this->repoDir.'/'.$packageData['Filename'],
                                    ),
                                    'warning',
                                );
                            }
                        }
                    }
                }
            }
        }

        $this->addStatusMessage((empty($saved) ? 'none' : \count($saved)).' packages saved: '.implode(
            ',',
            $saved,
        ), empty($saved) ? 'warning' : 'success');
    }

    /**
     * Check wether the package exist on the disk.
     */
    public function updatePresenceStatus(): void
    {
        foreach ($this->getColumnsFromSQL(['id', 'Filename', 'Existing']) as $pack) {
            $presence = file_exists($this->repoDir.'/'.$pack['Filename']) ? 1 : 0;

            if ($presence !== $pack['Existing']) {
                $this->updateToSQL(['id' => $pack['id'], 'updated' => date('Y-m-d H:i:s'),
                    'Existing' => $presence]);
                $this->addStatusMessage($pack['Filename'].' presence changed to '.($presence ? 'existing' : 'missing'));
            }
        }
    }

    /**
     * @param array<string, string> $packageData
     */
    public function indexPackageContents(array $packageData): void
    {
        $contentor = new Files();
        $contentor->indexPackageContents(
            $this->getMyKey(),
            $this->repoDir.'/'.$packageData['Filename'],
        );
    }
}
