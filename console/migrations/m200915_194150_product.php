<?php

use yii\db\Migration;

class m200915_194150_product extends Migration
{
    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}',[
            'uuid'=> 'varchar(36) not null',
            'sku'=> 'varchar(10) not null',
            'category_uuid'=> 'varchar(36)',
            'title'=> 'varchar(255) not null',
            'description'=> $this->text(),
            'short'=> $this->text(),
            'price'=> $this->decimal(10, 2)->defaultValue('0.00'),
            'discount'=> $this->integer(32),
            'viewed'=> $this->integer(32),
            'purchased'=> $this->integer(32),
            'rating'=> $this->integer(32),
            'position'=> $this->integer(32)->defaultValue(1),
            'active'=> $this->boolean()->defaultValue(true),
            'alias'=> 'varchar(255) not null',
            'meta_title'=> 'varchar(255)',
            'meta_description'=> $this->text(),
            'meta_keywords'=> $this->text(),
            'meta_robots'=> 'varchar(32)',
            'created_at'=> $this->timestamp(),
            'updated_at'=> $this->timestamp(),
        ]);

        $this->createIndex('product_alias_index','{{%product}}',['alias'],true);
        $this->createIndex('product_position_index','{{%product}}',['position'],false);
        $this->createIndex('product_sku_uindex','{{%product}}',['sku'],true);
        $this->addPrimaryKey('pk_on_product','{{%product}}',['uuid']);
        $this->addForeignKey(
            'fk_product_category_uuid',
            '{{%product}}', 'category_uuid',
            '{{%category}}', 'uuid',
            'SET DEFAULT', 'CASCADE'
        );
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_category_uuid', '{{%product}}');
        $this->dropPrimaryKey('pk_on_product','{{%product}}');
        $this->dropTable('{{%product}}');
    }
}
