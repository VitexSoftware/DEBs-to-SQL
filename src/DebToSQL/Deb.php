<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DebToSQL;

/**
 * Description of Deb
 *
 * @author vitex
 */
class Deb extends \Ease\Brick
{
    static function getIcon($package)
    {
        $icon = 'img/deb/' . $package . '.svg';
        if (!file_exists($icon)) {
            $icon = 'img/deb/' . $package . '.png';
        }
        if (!file_exists($icon)) {
            $icon = 'img/deb-package.png';
        }
        return $icon;
    }

    static function getIconUrl($package)
    {
        return dirname(ui\WebPage::getUri()) . self::getIcon($package);
    }
}
