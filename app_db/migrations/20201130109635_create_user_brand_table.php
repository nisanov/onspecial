<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserBrandTable extends AbstractMigration
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
     * 
     * @return void
     */
    public function change(): void
    {
        $this
            ->table('user_brand', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('user_id', 'biginteger', ['signed' => false])
            ->addColumn('brand_id', 'biginteger', ['signed' => false])
            ->addColumn('ignoring', 'boolean', ['default' => false])
            ->addIndex(['user_id', 'brand_id'], ['name' => 'idx_user_brand', 'unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('brand_id', 'brands', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
