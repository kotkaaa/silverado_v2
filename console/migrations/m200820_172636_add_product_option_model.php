<?php

use yii\db\Migration;

/**
 * Class m200820_172636_add_product_option_model
 */
class m200820_172636_add_product_option_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_option', [
            'uuid' => 'varchar(36) not null',
            'product_uuid' => 'varchar(36) not null',
            'value_uuid' => 'varchar(36) not null'
        ]);

        $this->addPrimaryKey('product_option_fk', 'product_option', 'uuid');
        $this->addForeignKey('product_option_product_fk', 'product_option', 'product_uuid', 'product', 'uuid', 'cascade');
        $this->addForeignKey('product_option_option_value_fk', 'product_option', 'value_uuid', 'option_value', 'uuid', 'cascade');
        $this->createIndex('product_option_product_option_value_uindex', 'product_option', ['product_uuid', 'value_uuid'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product_option');
    }
}
