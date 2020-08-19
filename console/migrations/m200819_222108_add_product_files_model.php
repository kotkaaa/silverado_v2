<?php

use yii\db\Migration;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * Class m200819_222108_add_product_files_model
 */
class m200819_222108_add_product_files_model extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_files', [
            'uuid' => 'varchar(36) not null',
            'files_uuid' => 'varchar(36)',
            'product_uuid' => 'varchar(36)',
            'position' => $this->integer()
        ]);

        $this->addPrimaryKey('product_files_pk', 'product_files', 'uuid');
        $this->addForeignKey('product_files_files_uuid_fk', 'product_files', 'files_uuid', 'files', 'uuid', 'set default');
        $this->addForeignKey('product_files_product_uuid_fk', 'product_files', 'product_uuid', 'product', 'uuid', 'set default');
        $this->createIndex('product_files_position_index', 'product_files', 'position');

        FileHelper::createDirectory(Url::to('@uploads/product'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product_files');

        FileHelper::removeDirectory(Url::to('@uploads/product'));
    }
}
