<?php

use yii\db\Migration;

/**
 * Class m200816_175627_add_files_model
 */
class m200816_175627_add_files_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('files', [
            'uuid' => 'varchar(36) not null',
            'name' => 'varchar(255) not null',
            'path' => 'varchar(255) not null',
            'url' => 'varchar(255) not null',
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('files_pk', 'files', 'uuid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('drop table files cascade');
    }
}
