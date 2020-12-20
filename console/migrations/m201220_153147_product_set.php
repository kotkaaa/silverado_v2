<?php

use yii\db\Migration;

/**
 * Class m201220_153147_product_set
 */
class m201220_153147_product_set extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_set}}', [
            'uuid'=> $this->string(36)->notNull(),
            'master_uuid'=> $this->string(36)->notNull(),
            'slave_uuid'=> $this->string(36)->notNull(),
        ]);

        $this->addPrimaryKey('pk_on_product_set','{{%product_set}}', 'uuid');
        $this->addForeignKey('fk_product_set_master_uuid',
            '{{%product_set}}',
            'master_uuid',
            '{{%product}}',
            'uuid',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey('fk_product_set_slave_uuid',
            '{{%product_set}}',
            'slave_uuid',
            '{{%product}}',
            'uuid',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex('product_set_master_uuid_slave_uuid_uindex','{{%product_set}}',['master_uuid', 'slave_uuid'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_set}}');
    }
}
