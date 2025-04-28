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

namespace DebToSQL\Ui;

/**
 * Description of PackageInfo.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class PackageInfo extends \Ease\Html\DivTag
{
    public static string $debBox = 'data:image/png;base64,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';

    public function __construct(string $pName)
    {
        $projectUrl = null;

        $pProps = $this->packageInfo($pName);

        $packFile = trim($pProps['Filename']);

        //        $installs = $repostats->getPackageInstalls($pName);
        //        $downloads = $repostats->getPackageDownloads($pName);

        $packageDownloadLink = new \Ease\Html\ATag(
            'https://repo.vitexsoftware.cz/'.$pProps['Filename'],
            '<img style="width: 18px;" src="'.self::$debBox.'">&nbsp;'.$pProps['Filename'],
            ['class' => 'btn btn-lg btn-default'],
        );

        $packageInstallLink = new \Ease\Html\ATag(
            'install.php?package='.$pName,
            '<img style="width: 18px;" src="'.self::$debBox.'">&nbsp;'.$pName,
            ['class' => 'btn btn-lg btn-success'],
        );

        $heading = new \Ease\TWB5\Row();
        $heading->addColumn(
            2,
            self::packageLogo($pName, ['style' => ' height: 90px;']),
        );
        $heading->addColumn(
            14,
            new \Ease\Html\H1Tag($pName.' '.$pProps['Version']),
        );

        parent::__construct([$heading, $packageDownloadLink, $packageInstallLink]);

        $this->addItem(new \Ease\TWB5\Card($pProps['Description']));

        $infotable = new \Ease\Html\TableTag(null, ['class' => 'table']);

        $infotable->addRowColumns([_('Filename'), $packageDownloadLink]);
        $infotable->addRowColumns([_('Version'), $pProps['Version']]);

        if (\array_key_exists('fileMtime', $pProps) && $pProps['fileMtime']) {
            $fileMtime = $pProps['fileMtime'];
            $incTime = date('Y m. d.', strtotime($fileMtime));
            $packAge = self::secondsToTime((float) (time() - strtotime($fileMtime)));
            $infotable->addRowColumns([_('Age in days'), (int) $packAge]);
            $infotable->addRowColumns([_('Release date'), $incTime]);
        }

        $infotable->addRowColumns([_('Size'), \Ease\Functions::humanFilesize((int) $pProps['Size'])]);
        //        $infotable->addRowColumns([_('Installs'), $installs]);
        //        $infotable->addRowColumns([_('Downloads'), $downloads]);
        $depIcons = '';

        foreach ($pProps as $key => $value) {
            switch ($key) {
                case 'Version':
                    $version = $value;

                    break;
                case 'Depends':
                case 'Suggests':
                case 'Recommends':
                case 'Conflicts':
                case 'Replaces':
                    if ($value) {
                        $infotable->addRowColumns([_($key), self::addPackageLinks($value)]);
                        $depIcons .= self::packagesIcons(self::DependsToArray($value));
                    }

                    break;
                case 'Homepage':
                    $projectUrl = $value;
                    $infotable->addRowColumns([
                        _('Homepage'),
                        '<a href="'.$value.'">'.$value.'</a>',
                    ]);

                    break;

                default:
                    $infotable->addRowColumns([_($key), \is_array($value) ? implode(
                        ',',
                        $value,
                    ) : $value]);

                    break;
            }
        }

        $infoColumns = new \Ease\TWB5\Row();
        $infoColumns->addColumn(8, $infotable);

        $rightColumn = new \Ease\Html\DivTag();
        $rightColumn->addItem(self::packageLogo($pName));

        if (\strlen($depIcons)) {
            $rightColumn->addItem('<h3>'._('see also').'</h3>'.$depIcons);
        }

        $infoColumns->addColumn(4, $rightColumn);

        $this->includeJavaScript('js/jquery.tablesorter.min.js');
        $this->addJavaScript('$("#dwlstats").tablesorter();');

        $popularityTable = new \Ease\Html\TableTag(
            null,
            ['class' => 'table', 'id' => 'dwlstats'],
        );

        $popularityTable->addRowHeaderColumns([_('Version'), _('Download/Install count'),
            _('Last hit')]);

        $packageTabs = new \Ease\TWB5\Tabs([_('Info') => $infoColumns], ['id' => 'ptabs']);

        $filer = new \DebToSQL\Files();
        $packageContents = $filer->getPackageContents($pProps['Name']);

        if ($packageContents) {
            $fileTable = new \Ease\Html\TableTag();
            $fileTable->addRowHeaderColumns(_('Path'), _('Size'));

            foreach ($packageContents as $pc) {
                $fileTable->addRowColumns(['path' => $pc['path'], 'size' => $pc['size']]);
            }

            $packageTabs->addTab(_('Files'), $fileTable);
        }

        if (strstr($projectUrl, 'github.com')) {
            $packageTabs->addTab(
                _('Read Me'),
                new HtmlMarkdownReadme($projectUrl, $version),
            );
        }

        $this->addItem($packageTabs);
    }

    public static function packagesIcons($packs)
    {
        $packIcons = [];

        foreach (array_keys($packs) as $packName) {
            $icon = 'img/deb/'.$packName.'.png';

            if (file_exists($icon)) {
                $packIcons[] = '<div><a href="package.php?package='.$packName.'">'.$packName.'</a></div>';
                $packIcons[] = '<a href="package.php?package='.$packName.'">'.self::packageLogo($packName).'</a>';
            }
        }

        return implode('', $packIcons);
    }

    public static function DependsToArray(string $dependsRaw)
    {
        $packagesRaw = explode(',', str_replace('|', ',', $dependsRaw));

        foreach ($packagesRaw as $pid => $package) {
            $package = trim($package);

            if (strstr($package, ' ')) {
                $packageName = explode(' ', $package)[0];
            } else {
                $packageName = trim($package);
            }

            $packages[$packageName] = $package;
        }

        return $packages;
    }

    public static function addPackageLinks($dependsRaw)
    {
        $packs = self::DependsToArray($dependsRaw);

        foreach ($packs as $pack => $name) {
            $icon = 'img/deb/'.$pack.'.png';

            if (file_exists($icon)) {
                $packs[$pack] = '<a href="package.php?package='.$pack.'">'.$name.'</a>';
            } else {
                $packs[$pack] = '<a href="https://packages.debian.org/stretch/'.$pack.'">'.$name.'</a>';
            }
        }

        return implode(' , ', $packs);
    }

    public static function getPackageIcon($package)
    {
        $icon = 'img/deb/'.$package.'.png';

        if (!file_exists($icon)) {
            $icon = 'img/deb/'.$package.'.svg';
        }

        if (!file_exists($icon)) {
            $icon = 'img/deb-package.png';
        }

        return $icon;
    }

    public static function packageLogo(
        $pName,
        $properties = ['class' => 'img-responsive',
            'style' => 'margin: auto auto; width: 200px;'],
    ) {
        return new \Ease\Html\ImgTag(
            self::getPackageIcon($pName),
            $pName,
            $properties,
        );
    }

    /**
     * Obtain DPKG info for given package.
     *
     * @param string $pName package-name
     *
     * @return array dpkg info
     */
    public function packageInfo($pName)
    {
        $packager = new \DebToSQL\Packages($pName, ['autoload' => true]);

        $candidates = $packager->getData();

        return $candidates[0];
    }

    /**
     * Timestap to time convertor.
     *
     * @param int|long $seconds
     *
     * @return Date
     */
    public static function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@{$seconds}");

        return $dtF->diff($dtT)->format('%a');
    }
}
