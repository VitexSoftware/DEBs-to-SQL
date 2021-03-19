<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DebToSQL;

/**
 * Description of Repository
 *
 * @author vitex
 */
class Repository extends \Ease\SQL\Engine {

    public $dists = [];
    public $repoDir = '';
    private $archs = [];
    public $myTable = 'packages';

    public function __construct(array $skiplist = []) /* : \DirectoryIterator */ {
        $this->repoDir = \Ease\Functions::cfg('REPO_DIR');
        $this->poolDir = $this->repoDir . 'pool/';
        foreach (new \DirectoryIterator($this->repoDir . 'dists/') as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if (!$fileInfo->isReadable()) {
                continue;
            }
            if (strstr($fileInfo->getFilename(), '-')) {
                continue;
            }
            $this->dists[$fileInfo->getFilename()] = $this->repoDir . 'dists/' . $fileInfo->getFilename();
        }
    }

    public function parseDists() {
        $this->logBanner(\Ease\Shared::appName(), 'in: ' . $this->repoDir);
        foreach (array_keys($this->dists) as $dist) {
            $this->parseDist($dist);
        }
    }

    public function parseDist($distName) {
        $this->addStatusMessage(_('Parsing distro') . ': ' . $distName);
        $suites = $this->parseSuites($distName);

        foreach (array_keys($suites) as $suite) {
            $this->archs = $this->parseArchs($distName, $suite);
        }
    }

    /**
     * Parse suites in given Distribution
     * 
     * @param string $distName
     * 
     * @return array
     */
    public function parseSuites($distName) {
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
            $this->addStatusMessage($distName . ': suite found: ' . $suite);
            $this->suites[$distName][$suite] = $this->dists[$distName] . '/' . $suite;
        }
        return $this->suites[$distName];
    }

    /**
     * Parse Architectures in dist/suite
     * 
     * @param string $distName
     * @param string $suite
     * 
     * @return array archs found
     */
    public function parseArchs($distName, $suite) {
        foreach (new \DirectoryIterator($this->dists[$distName] . '/' . $suite) as $fileInfo) {
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

            $this->addStatusMessage('Architecture found: ' . $distName . '/' . $suite . ': ' . $arch);
            $this->arch[$distName][$suite][$arch] = $this->dists[$distName] . '/' . $suite . '/' . $arch;

            $packages = $this->arch[$distName][$suite][$arch] . '/Packages';
            if (file_exists($packages)) {
                $this->packages[$distName][$suite][$arch] = $this->readpackages($packages);
                $this->addStatusMessage('Found ' . count($this->packages[$distName][$suite][$arch]) . ' packages in ' . $distName . '/' . $suite . '/' . $arch, 'success');
            }
        }
        return $this->arch[$distName][$suite];
    }

    /**
     * 
     * 
     * @param type $dataRaw
     * 
     * @return array
     */
    public function onlyKnownColumns($dataRaw) {
        $dataFiltered = [];
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Name');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Package');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Appname');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Essential');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Vendor');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'License');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Distribution');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Suite');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Source');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Version');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Architecture');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'MultiArch');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Maintainer');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'InstalledSize');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Depends');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'PreDepends');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Breaks');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Replaces');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Section');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Priority');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Description');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'LongDescription');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'AutoBuiltPackage');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Filename');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'MD5sum');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'SHA1');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'SHA256');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'SHA512');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Size');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'AutoBuiltPackage');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Conflicts');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Homepage');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Provides');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Recommends');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Suggests');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'Exists');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'fileMtime');
        \Ease\Functions::divDataArray($dataRaw, $dataFiltered, 'created');
        return $dataFiltered;
    }

    /**
     * 
     * @param type $pkgFile
     * 
     * @return type
     */
    public function readpackages($pkgFile) {
        $packages = [];
        $pName = null;
        $handle = fopen($pkgFile, "r");
        $position = 0;
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                if (!strstr($buffer, ': ')) {
                    continue;
                }
                list( $key, $value) = explode(': ', $buffer);
                switch ($key) {
                    case 'Package':
                        $pName = trim($value);
                        $position++;
                        $packages[$pName]['fileMtime'] = time();
                        break;
                    case 'Description':
                        $packages[$pName][$key] = trim($value);
                        $packages[$pName]['LongDescription'] = '';
                        while (($buffer = fgets($handle, 4096)) !== false) {
                            if ($buffer[0] == ' ') {
                                $packages[$pName]['LongDescription'] .= trim($buffer);
                            } else {
                                list( $key, $value ) = explode(':', $buffer);
                                $packages[$pName][$key] = trim($value);
                                break;
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
                        $origFile = $this->poolDir . '/' . $distro . '/' . ($section == 'main') ? '' : $section . '/' . $pName;
                        $packages[$pName]['fileMtime'] = filemtime($origFile);
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

    public function saveAllToSQL() {
        $saved = [];
        foreach ($this->packages as $dist => $suites) {

            foreach ($suites as $suite => $suiteData) {

                foreach ($suiteData as $arch => $packages) {

                    foreach ($packages as $packageData) {

                        $packageData['Distribution'] = $dist;
                        $packageData['Suite'] = $suite;
                        $packageData['Architecture'] = $arch;
                        if ($packageData['fileMtime']) {
                            $packageData['fileMtime'] = (New \DateTime())->setTimestamp($packageData['fileMtime'])->format('Y-m-d H:i:s');
                        } else {
                            unset($packageData['fileMtime']);
                        }

                        unset($packageData['Build-Ids']);

                        if (array_key_exists('Filename', $packageData) && empty($this->getColumnsFromSQL(['id'], ['Filename' => $packageData['Filename']]))) {
                            if ($this->insertToSQL(self::onlyKnownColumns($packageData))) {
                                $saved[] = $packageData['Name'];
                            }
                        }
                    }
                }
            }
        }
        $this->addStatusMessage((empty($saved) ? 'none' : count($saved)) . ' packages saved: ' . implode(',', $saved), empty($saved) ? 'warning' : 'success' );
    }

}
