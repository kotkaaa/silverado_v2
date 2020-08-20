<?php

use yii\db\Migration;

/**
 * Class m200820_211729_add_attribute_model
 */
class m200820_211729_add_attribute_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('attribute', [
            'uuid' => 'varchar(36) not null',
            'title' => 'varchar(255) not null',
            'position' => $this->integer()->defaultValue(1),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('attribute_pk', 'attribute', 'uuid');
        $this->createIndex('attribute_position_index', 'attribute', 'position');

        $this->createTable('attribute_value', [
            'uuid' => 'varchar(36) not null',
            'attribute_uuid' => 'varchar(36) not null',
            'title' => 'varchar(255) not null',
            'alias' => 'varchar(255) not null',
            'position' => $this->integer()->defaultValue(1),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('attribute_value_pk', 'attribute_value', 'uuid');
        $this->addForeignKey('attribute_value_attribute_fk', 'attribute_value', 'attribute_uuid', 'attribute', 'uuid', 'cascade');
        $this->createIndex('attribute_value_position_index', 'attribute_value', 'position');

        $this->createTable('product_attribute', [
            'uuid' => 'varchar(36) not null',
            'product_uuid' => 'varchar(36) not null',
            'value_uuid' => 'varchar(36) not null'
        ]);

        $this->addPrimaryKey('product_attribute_fk', 'product_attribute', 'uuid');
        $this->addForeignKey('product_attribute_product_fk', 'product_attribute', 'product_uuid', 'product', 'uuid', 'cascade');
        $this->addForeignKey('product_attribute_option_value_fk', 'product_attribute', 'value_uuid', 'attribute_value', 'uuid', 'cascade');
        $this->createIndex('product_attribute_product_attribute_value_uindex', 'product_attribute', ['product_uuid', 'value_uuid'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product_attribute');
        $this->dropTable('attribute_value');
        $this->dropTable('attribute');
    }
}
