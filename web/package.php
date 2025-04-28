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
 * VitexSoftware - Package Info.
 *
 * @author     Vitex <vitex@hippy.cz>
 * @copyright  2012-2025 Vitex@hippy.cz (G)
 */

require_once '../vendor/autoload.php';
\Ease\Shared::init(['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'], '../.env');

$package = \Ease\TWB5\WebPage::getRequestValue('package', 'string');

$oPage = new \Ease\TWB5\WebPage(sprintf(_('Package %s details'), $package));

$oPage->head->addItem('<meta property="og:image" content="http://vitexsoftware.cz/'.(file_exists('img/deb/'.$package.'.png') ? 'img/deb/'.$package.'.png' : 'img/deb-package.png').'"/>');
$oPage->head->addItem('<meta property="og:title" content="Debian Package '.$package.'"/>');

$oPage->addItem(new \Ease\TWB5\Container(new Ui\PackageInfo(urlencode($package))));

$oPage->draw();
