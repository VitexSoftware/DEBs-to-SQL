<?php

declare(strict_types=1);

/**
 *
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2023 Vitex Software
 */

namespace DebToSQL\Ui;

/**
 * Description of DistroList
 *
 * @author vitex
 */
class DistroList extends \Ease\TWB5\Table
{
    public function __construct($engine, $properties = [])
    {
        $packagesTree = [];
        $pkgsRaw = $engine->listingQuery()->select(['id', 'Distribution', 'Suite', 'Name', 'Version'], true)->fetchAll();
        foreach ($pkgsRaw as $pkg) {
            $packagesTree[$pkg['Distribution']][$pkg['Suite']][$pkg['Name']] = $pkg['id'];
        }

        parent::__construct(null, $properties);
        $this->addRowHeaderColumns([_('Distribution'), _('Suite'), _('Package')]);
        foreach ($packagesTree as $distroName => $suites) {
            foreach ($suites as $suite => $packages) {
                foreach ($packages as $package => $id) {
                    $this->addRowColumns(
                        [
                                'distro' => $distroName,
                                'suite' => $suite,
                                'package' => $package
                            ]
                    );
                }
            }
        }
    }
}
