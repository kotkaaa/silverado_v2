<?php

use yii\db\Migration;

/**
 * Class m200820_134857_add_option_value_model
 */
class m200820_134857_add_option_value_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('option_value', [
            'uuid' => 'varchar(36) not null',
            'option_uuid' => 'varchar(36) not null',
            'title' => 'varchar(255) not null',
            'alias' => 'varchar(255) not null',
            'price' => $this->float(2),
            'action' => 'varchar(32)',
            'position' => $this->integer()->defaultValue(1),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('option_value_pk', 'option_value', 'uuid');
        $this->addForeignKey('option_value_option_fk', 'option_value', 'option_uuid', 'option', 'uuid', 'cascade');
        $this->createIndex('option_value_alias_uindex', 'option_value', 'alias', true);
        $this->createIndex('option_value_position_index', 'option_value', 'position');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('option_value');
    }
}
