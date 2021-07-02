<?php

use yii\db\Migration;

/**
 * Class m210603_124435_options_plugin
 */
class m210603_124435_options_plugin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('option', 'plugin', 'varchar(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('option', 'plugin');
    }
}
