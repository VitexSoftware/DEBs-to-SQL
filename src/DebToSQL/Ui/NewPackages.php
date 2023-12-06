<?php

namespace DebToSQL\Ui;

/**
 * Description of NewPackages
 *
 * @author vitex
 */
class NewPackages extends \Ease\Html\SpanTag
{
    use \Ease\SQL\Orm;

    /**
     *
     * @var string table name we work on
     */
    public $myTable = 'vs_access_log';
    public $repobase = '/home/vitex/WWW/repo.vitexsoftware.cz/';
    public $libdir = '/var/lib/freight/apt';

    /**
     *
     * @var array
     */
    private $packagesByTime = [];

    /**
     *
     * @param type $pkgFile
     *
     * @return type
     */
    public function readpackages($pkgFile)
    {
        $packages = [];
        $pName = null;
        $handle = fopen($pkgFile, "r");
        $position = 0;
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                if (!strstr($buffer, ':')) {
                    continue;
                }
                list( $key, $value ) = explode(':', $buffer);
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
                    case 'Filename':
                        $fpparts = explode('/', $value);
                        $distro = $fpparts[1];
                        $section = $fpparts[2];
                        $origFile = $this->libdir . '/' . $distro . '/' . ($section == 'main') ? '' : $section . '/' . $pName;
                        $packages[$pName]['fileMtime'] = filemtime($origFile);
                    default:
                        $packages[$pName][$key] = trim($value);
                        break;
                }
                $packages[$pName]['Name'] = $pName;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        return $packages;
    }

    /**
     * New Packages handler
     *
     * @param int   $howmuch
     * @param array $properties
     */
    public function __construct($howmuch = 10, $properties = [])
    {
        $packager = new \DebToSQL\Packages($howmuch);

        $this->packagesByTime = $packager->listingQuery()->limit($howmuch)->where('Existing', 1)->groupBy('Name')->orderBy('updated,created DESC')->fetchAll();

//        $this->getPdo([
//            'dbType' => constant('STATS_TYPE'),
//            'server' => constant('STATS_SERVER'),
//            'username' => constant('STATS_USERNAME'),
//            'password' => constant('STATS_PASSWORD'),
//            'database' => constant('STATS_DATABASE'),
//            'port' => constant('STATS_PORT')
//        ]);

        parent::__construct(new \Ease\Html\H1Tag(
            _('Fresh Packages'),
            ['style' => 'text-align: center;']
        ));
    }

    /**
     *
     *
     * @param array $pProps
     *
     * @return \Ease\TWB4\Card
     */
    public function debInfoBlock($pProps)
    {
        $pName = trim($pProps['Name']);
        $packFile = trim($pProps['Filename']);
        $icon = 'img/deb/' . $pName . '.svg';
        if (!file_exists($icon)) {
            $icon = 'img/deb/' . $pName . '.png';
        }
        if (!file_exists($icon)) {
            $icon = 'img/deb-package.png';
        }
        $counts = $this->getPullCounts($pProps['Filename'], $pProps['Version']);

        $download = new \Ease\Html\ATag(
            'http://repo.vitexsoftware.cz/' . $pProps['Filename'],
            '<img style="width: 30px;" src="img/deb-package.png">&nbsp;' . ' ' . \Ease\Functions::formatBytes(intval($pProps['Size'])),
            ['class' => 'btn btn-success']
        );

        $icon = new \Ease\Html\ImgTag($icon, $pName, ['alt' => $pName, 'class' => 'card-img-top']);
        new \Ease\Html\H5Tag($pName . ' ' . $pProps['Version']);
        $cardBody = new \Ease\Html\DivTag(null, ['class' => 'card-body']);

        $cardBody->addItem(new \Ease\Html\ATag('package.php?package=' . $pName, $icon));

        $cardBody->addItem(new \Ease\Html\PTag($pProps['Description'], ['class' => 'card-text']));
        $cardBody->addItem($download);
        $packageCard = new \Ease\TWB4\Card(new \Ease\Html\DivTag(new \Ease\Html\H5Tag($pName . ' ' . $pProps['Version']), ['class' => 'card-header']), ['class' => 'text-black bg-warning']);
        $packageCard->addItem($cardBody);

     //   $packageCard->addItem(new \Ease\Html\DivTag('<small>' . _('Installed') . ': ' . $counts['installs'] . '&nbsp&nbsp;' . _('Downloaded') . ': ' . $counts['downloads'] . '</small>', ['class' => 'card-footer']));

        return $packageCard;
    }

    /**
     * RSS Feed data
     *
     * @return array
     */
    public function getRssData($filter = null)
    {
        $rssData = [];
        foreach ($this->packagesByTime as $packageInfo) {
            if (!is_null($filter) && (!strstr($packageInfo['Name'], $filter) && !strstr($packageInfo['Description'], $filter) )) {
                continue;
            }

            $rssData[] = [
                "title" => $packageInfo['Name'] . ' ' . $packageInfo['Version'],
                "description" => $packageInfo['Description'],
                "link" => 'package.php?package=' . $packageInfo['Name'],
                "icon" => \DebToSQL\Deb::getIcon($packageInfo['Name']),
                "date" => $packageInfo['fileMtime'],
            ];
        }
        return $rssData;
    }

    /**
     * Get Package pull counts
     *
     * @param string $package package base name
     *
     * @return array of int
     */
    public function getPullCounts($package)
    {
        $params = [':package' => sprintf('%%%s%%', basename($package)), ':agent' => 'Debian APT%%'];
        $installs = $this->getFluentPDO()->from('repo_access_log')->where('request_uri LIKE :package AND agent LIKE :agent', $params)->count();
        $downloads = $this->getFluentPDO()->from('repo_access_log')->where('request_uri LIKE :package AND agent NOT LIKE :agent', $params)->count();
        return ['installs' => $installs, 'downloads' => $downloads];
    }

    public function finalize()
    {
        foreach ($this->packagesByTime as $pProps) {
            $this->addItem(new \Ease\Html\PTag($this->debInfoBlock($pProps)));
        }

        $this->addItem(new \Ease\Html\PTag('<br>'));

        $this->addItem(new \Ease\Html\PTag(
            new \Ease\Html\ATag(
                'debs.php',
                '<img style="width: 30px;" src="img/deb-package.png">&nbsp;' . ' ' . _('All Packages') . ' <i class="fa fa-angle-double-right" aria-hidden="true"></i>
',
                ['class' => 'btn btn-info btn-lg btn-block']
            ),
            ['style' => 'text-align: center;']
        ));
    }
}
