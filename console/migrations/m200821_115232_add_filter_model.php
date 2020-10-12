<?php

use yii\db\Migration;

/**
 * Class m200821_115232_add_filter_model
 */
class m200821_115232_add_filter_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('filter', [
            'uuid' => 'varchar(36) not null',
            'attribute_uuid' => 'varchar(36)',
            'option_uuid' => 'varchar(36)',
            'title' => 'varchar(255) not null',
            'alias' => 'varchar(255) not null',
            'strategy_class' => 'varchar(255) not null',
            'position' => $this->integer()->defaultValue(1),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('filter_pk', 'filter', 'uuid');
        $this->createIndex('filter_attribute_strategy_class_uindex', 'filter', ['attribute_uuid', 'strategy_class'], true);
        $this->createIndex('filter_option_strategy_class_uindex', 'filter', ['option_uuid', 'strategy_class'], true);
        $this->createIndex('filter_alias_uindex', 'filter', 'alias');
        $this->createIndex('filter_position_index', 'filter', 'position');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('filter');
    }
}
