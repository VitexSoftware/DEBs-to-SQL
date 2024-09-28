<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PackageContents extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $users = $this->table('files');
        $users->addColumn('packages_id', 'integer',['unsigned'=>true])
                ->addColumn('path', 'string', ['limit' => 4084])
                ->addColumn('size', 'integer', ['limit' => 40])
                ->addColumn('updated', 'datetime', ['null' => true])
                ->addForeignKey('packages_id', 'packages', 'id')
                ->create();
    }

}
