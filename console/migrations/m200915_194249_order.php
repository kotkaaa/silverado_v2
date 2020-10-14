<?php

use yii\db\Schema;
use yii\db\Migration;

class m200915_194249_order extends Migration
{
    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}',[
            'id'=> $this->primaryKey(),
            'status'=> 'varchar(32)',
            'created_at'=> $this->timestamp(),
            'updated_at'=> $this->timestamp(),
            'deleted_at'=> $this->timestamp(),
        ]);


        $this->createTable('{{%order_info}}',[
            'uuid'=> $this->string(36)->notNull(),
            'order_id'=> $this->integer(32)->notNull(),
            'payment_type'=> $this->string(32),
            'delivery_type'=> $this->string(32),
            'user_name'=> $this->string(255),
            'user_phone'=> $this->string(32),
            'user_email'=> $this->string(64),
            'comment'=> $this->text(),
            'location'=> $this->string(255),
            'address'=> $this->string(255),
            'recepient_name'=> $this->string(255),
            'recepient_phone'=> $this->string(32),
        ]);

        $this->addPrimaryKey('pk_on_order_info','{{%order_info}}',['uuid']);

        $this->createTable('{{%order_product}}',[
            'uuid'=> 'varchar(36) not null',
            'order_id'=> $this->integer(32)->notNull(),
            'product_uuid'=> 'varchar(36)',
            'title'=> 'varchar(255)',
            'sku'=> 'varchar(10)',
            'price'=> $this->decimal(10, 2)->defaultValue('0.00'),
            'quantity'=> $this->integer(32),
            'options'=> "json",
        ]);

        $this->addPrimaryKey('pk_on_order_product','{{%order_product}}',['uuid']);
        $this->addForeignKey(
            'fk_order_info_order_id',
            '{{%order_info}}', 'order_id',
            '{{%order}}', 'id',
            'SET DEFAULT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_order_product_order_id',
            '{{%order_product}}', 'order_id',
            '{{%order}}', 'id',
            'SET DEFAULT', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_order_product_product_uuid',
            '{{%order_product}}', 'product_uuid',
            '{{%product}}', 'uuid',
            'SET DEFAULT', 'CASCADE'
        );
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_info_order_id', '{{%order_info}}');
        $this->dropForeignKey('fk_order_product_order_id', '{{%order_product}}');
        $this->dropForeignKey('fk_order_product_product_uuid', '{{%order_product}}');
        $this->dropTable('{{%order}}');
        $this->dropPrimaryKey('pk_on_order_info','{{%order_info}}');
        $this->dropTable('{{%order_info}}');
        $this->dropPrimaryKey('pk_on_order_product','{{%order_product}}');
        $this->dropTable('{{%order_product}}');
    }
}
