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
 * Description of Deb.
 *
 * @author vitex
 */
class Deb extends \Ease\Brick
{
    /**
     * Get Deb Icon Image.
     */
    public static function getIcon(string $package): string
    {
        $icon = 'img/deb/'.$package.'.svg';

        if (!file_exists($icon)) {
            $icon = 'img/deb/'.$package.'.png';
        }

        if (!file_exists($icon)) {
            $icon = 'img/deb-package.png';
        }

        return $icon;
    }

    /**
     * Package Icon URL.
     */
    public static function getIconUrl(string $package): string
    {
        return \dirname(\Ease\WebPage::getUri()).self::getIcon($package);
    }
}
