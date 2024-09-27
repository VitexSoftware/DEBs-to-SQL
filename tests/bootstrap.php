<?php

/**
 * This file is part of the DEB_SQL_indexer package
 *
 * https://github.com/VitexSoftware/
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



require_once __DIR__ . '/../vendor/autoload.php';

\define('APP_NAME', 'debs2sql');
\Ease\Shared::init(['DB_CONNECTION',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
    'REPO_DIR'], __DIR__ .'/../.env');
