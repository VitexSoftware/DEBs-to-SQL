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
 * VitexSoftware - DEBs to SQL web interface.
 *
 * @author     Vitex <vitex@hippy.cz>
 * @copyright  2023-2024 Vitex@hippy.cz (G)
 */
require_once '../vendor/autoload.php';
\Ease\Shared::init(['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'], '../.env');
$oPage = new \Ease\TWB5\WebPage(_('Packages Repository'));
$engine = new Repository();
$oPage->addItem(new Ui\DistroList($engine));
$oPage->draw();
