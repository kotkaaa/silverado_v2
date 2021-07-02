<?php

use yii\db\Migration;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * Class m210623_090042_reviews
 */
class m210623_090042_reviews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('review', [
            'uuid' => 'varchar(36) not null',
            'author' => 'varchar(255)',
            'comment' => $this->text(),
            'rate' => $this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'deleted_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('review_pk', 'review', 'uuid');

        $this->createTable('product_review', [
            'uuid' => 'varchar(36) not null',
            'product_uuid' => 'varchar(36) not null',
            'review_uuid' => 'varchar(36) not null',
        ]);

        $this->addPrimaryKey('product_review_pk', 'product_review', 'uuid');
        $this->addForeignKey('product_review_product_pk', 'product_review', 'product_uuid', 'product', 'uuid', 'cascade', 'cascade');
        $this->addForeignKey('product_review_review_pk', 'product_review', 'review_uuid', 'review', 'uuid', 'cascade', 'cascade');
        
        $this->createTable('review_files', [
            'uuid' => 'varchar(36) not null',
            'files_uuid' => 'varchar(36)',
            'review_uuid' => 'varchar(36)',
            'created_at' => $this->timestamp()
        ]);

        $this->addPrimaryKey('review_files_pk', 'review_files', 'uuid');
        $this->addForeignKey('review_files_files_uuid_fk', 'review_files', 'files_uuid', 'files', 'uuid', 'set default');
        $this->addForeignKey('review_files_review_uuid_fk', 'review_files', 'review_uuid', 'review', 'uuid', 'set default');

        FileHelper::createDirectory(Url::to('@uploads/review'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product_review');
        $this->dropTable('review_files');
        $this->dropTable('review');

        FileHelper::removeDirectory(Url::to('@uploads/review'));
    }
}
