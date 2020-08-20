<?php

use yii\db\Migration;

/**
 * Class m200820_123313_add_option_model
 */
class m200820_123313_add_option_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('option', [
            'uuid' => 'varchar(36) not null',
            'title' => 'varchar(255) not null',
            'strategy' => 'varchar(32)',
            'required' => $this->boolean()->defaultValue(false),
            'position' => $this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('option_pk', 'option', 'uuid');
        $this->createIndex('option_position_index', 'option', 'position');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $th=$this->dropTable('option');
    }
}
