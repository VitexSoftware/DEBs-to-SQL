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

namespace DebToSQL\Ui;

/**
 * Description of DistroList.
 *
 * @author vitex
 */
class DistroList extends \Ease\TWB5\Table
{
    /**
     * List of Distributions.
     *
     * @param \DebToSQL\Engine      $engine
     * @param array<string, string> $properties
     */
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
                            'package' => new \Ease\Html\ATag('package.php?package='.$package, $package),
                        ],
                    );
                }
            }
        }
    }
}
