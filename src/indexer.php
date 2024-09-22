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

require_once '../vendor/autoload.php';

\define('APP_NAME', 'debs2sql');
\Ease\Shared::init(['DB_CONNECTION',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
    'REPO_DIR'], '../.env');

$repositor = new \DebToSQL\Repository();

$repositor->parseDists();

$repositor->saveAllToSQL();

$repositor->updatePresenceStatus();
