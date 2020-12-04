<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserSpecialTable extends AbstractMigration
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
            ->table('user_special', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('user_id', 'biginteger', ['signed' => false])
            ->addColumn('special_id', 'biginteger', ['signed' => false])
            ->addColumn('seen', 'boolean', ['default' => false])
            ->addIndex(['user_id', 'special_id'], ['name' => 'idx_user_special', 'unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('special_id', 'specials', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
