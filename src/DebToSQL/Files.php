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
 * Description of Files.
 *
 * @author vitex
 */
class Files extends \Ease\SQL\Engine
{
    public string $myTable = 'files';

    /**
     * @param int    $packageId
     * @param string $packagePath
     */
    public function indexPackageContents($packageId, $packagePath): void
    {
        foreach (self::getPackageContents($packagePath) as $path => $size) {
            $this->insertToSQL(['packages_id' => $packageId, 'path' => $path, 'size' => $size]);
        }
    }

    /**
     * obtaing package contents.
     *
     * @param string $path deb file
     *
     * @return array
     */
    public static function getPackageContents($path)
    {
        $packageFiles = [];
        $fp = popen('/usr/bin/dpkg -c '.$path, 'r');

        while (!feof($fp)) {
            $lineData = preg_split('/[\s]+/', fgets($fp, 4096));

            if (\array_key_exists(2, $lineData)) {
                $packageFiles[$lineData[5]] = $lineData[2];
            }
        }

        pclose($fp);

        return $packageFiles;
    }
}
