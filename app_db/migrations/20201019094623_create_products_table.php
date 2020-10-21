<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
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
        $table = $this->table('products', ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('brand_id', 'biginteger', ['signed' => false, 'null' => true])
            ->addColumn('store_id', 'biginteger', ['signed' => false])
            ->addColumn('store_reference', 'string')
            ->addColumn('name', 'string')
            ->addColumn('description', 'text')
            ->addColumn('ingredients', 'text')
            ->addColumn('uri', 'string')
            ->addColumn('thumb', 'string', ['null' => true])
            ->addColumn('image', 'string')
            ->addColumn('size', 'string', ['null' => true, 'length' => 50])
            ->addColumn('on_special', 'boolean', ['default' => true])
            ->addColumn('created_at', 'timestamp', ['precision' => 4])
            ->addColumn('updated_at', 'timestamp', ['precision' => 4, 'null' => true, 'default' => null])
            ->addIndex(['store_reference'], ['name' => 'idx_products_store_reference'])
            ->addIndex(['store_id', 'store_reference'], ['name' => 'idx_products_store', 'unique' => true])
            ->addForeignKey('store_id', 'stores', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('brand_id', 'brands', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->create();
    }
}
