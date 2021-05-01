<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DebToSQL;

/**
 * Description of Files
 *
 * @author vitex
 */
class Files extends \Ease\SQL\Engine {

    public $myTable = 'files';

    public function indexPackageContents($packageId, $packagePath) {
        foreach ( self::getPackageContents($packagePath) as $path => $size ){
            $this->insertToSQL(['packages_id' => $packageId, 'path' => $path, 'size'=> $size]  );
        }
    }

    public static function getPackageContents($path) {
        $packageFiles = [];
        $fp = popen('/usr/bin/dpkg -c ' . $path, 'r');
        while (!feof($fp)) {
            $lineData = preg_split('/[\s]+/', fgets($fp, 4096));
            if (array_key_exists(2, $lineData)) {
                $packageFiles[$lineData[5]] = $lineData[2];
            }
        }
        pclose($fp);
        return $packageFiles;
    }

}
