<?php

/**
 * Debian paggage and its contents SQL indexer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2021 Vitex Software
 */
require_once '../vendor/autoload.php';

$shared = \Ease\Shared::instanced();
if (file_exists('../.env')) {
    $shared->loadConfig('../.env', true);
}

define('APP_NAME', 'debs2sqlContent');

$repositor = new \DebToSQL\Repository();
$filer = new \DebToSQL\Files();
$done = 0;
foreach ($repositor->listingQuery()->select(['id', 'Filename'], true) as $package) {
    $path = $repositor->repoDir . '/' . $package['Filename'];
    if ($filer->listingQuery()->select(['id'], true)->where('packages_id', $package['id'])->count() == 0) {
        $filer->indexPackageContents($package['id'], $path);
        $filer->addStatusMessage($done++ . $path, 'success');
    }
}
$filer->addStatusMessage($done.' packages contents indexed', 'info');
