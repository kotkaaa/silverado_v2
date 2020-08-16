<?php

use yii\db\Migration;
use yii\helpers\Url;
use yii\helpers\FileHelper;

/**
 * Class m200816_085814_add_category_model
 */
class m200816_085814_add_category_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'uuid' => 'varchar(36) not null',
            'parent_uuid' => 'varchar(36)',
            'title' => 'varchar(255) not null',
            'description' => $this->text(),
            'icon'  => 'varchar(255)',
            'image'  => 'varchar(255)',
            'alias' => 'varchar(255) not null',
            'meta_title' => 'varchar(255)',
            'meta_description' => $this->text(),
            'meta_keywords' => $this->text(),
            'meta_robots' => 'varchar(32)',
            'position' => $this->integer()->notNull()->defaultValue(1),
            'active' => $this->boolean()->defaultValue(true),
            'separator' => $this->boolean()->defaultValue(false),
            'redirect' => 'varchar(255)',
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('category_pk', 'category', 'uuid');
        $this->createIndex('category_alias_uindex', 'category', 'alias', true);
        $this->createIndex('category_position_index', 'category', 'position');

        FileHelper::createDirectory(Url::to('@uploads/category'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');

        FileHelper::removeDirectory(Url::to('@uploads/category'));
    }
}
