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
 * Description of News.
 *
 * @author vitex
 */
class Packages extends \Ease\SQL\Engine
{
    public $myKeyColumn = 'id';

    /**
     * Column containing the date the record was added to the shop.
     */
    public string $myCreateColumn = 'created';

    /**
     * Column containing the date of the last modification of the record in the shop.
     */
    public string $myLastModifiedColumn = 'updated';

    /**
     * Packages handler
     * 
     * @param int|string $identifier
     * @param array<string,string> $options
     */
    public function __construct($identifier = null, array $options = [])
    {
        $this->nameColumn = 'Name';
        $this->myTable = 'packages';
        parent::__construct($identifier, $options);
    }
}
