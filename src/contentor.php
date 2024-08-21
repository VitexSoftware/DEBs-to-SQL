<?php

/**
 * Debian package and its contents SQL indexer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2021-2023 Vitex Software
 */

require_once '../vendor/autoload.php';

const APP_NAME = 'debs2sqlContent';
\Ease\Shared::init(['DB_CONNECTION',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
    'REPO_DIR'], '../.env');

$repositor = new \DebToSQL\Repository();
$filer = new \DebToSQL\Files();
$done = 0;
foreach ($repositor->listingQuery()->select(['id', 'Filename'], true) as $package) {
    $path = $repositor->repoDir . '/' . $package['Filename'];
    if (
        $filer->listingQuery()->select(['id'], true)->where(
            'packages_id',
            $package['id']
        )->count() == 0
    ) {
        $filer->indexPackageContents($package['id'], $path);
        $filer->addStatusMessage($done++ . $path, 'success');
    }
}
$filer->addStatusMessage($done . ' packages contents indexed', 'info');
