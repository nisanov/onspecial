<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStoresTable extends AbstractMigration
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
        $table = $this->table('stores', ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('name', 'string')
            ->addColumn('active', 'boolean', ['default' => true])
            ->addColumn('scrapper', 'string')
            ->addColumn('created_at', 'timestamp', ['precision' => 4])
            ->addColumn('updated_at', 'timestamp', ['precision' => 4, 'null' => true, 'default' => null])
            ->addIndex(['name'], ['unique' => true, 'name' => 'idx_stores_name'])
            ->addIndex(['scrapper'], ['unique' => true, 'name' => 'idx_stores_scrapper'])
            ->create();
    }
}
