<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m230315_183407_add_root_user
 */
class m230315_183407_add_root_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('{{%user%}}', [
			'id' => 1,
			'username' => 'root',
			'email' => 'root@localhost.com',
			'password_hash' => \Yii::$app->security->generatePasswordHash('2c4uk915'),
			'auth_key' => \Yii::$app->security->generateRandomString(),
			'confirmed_at' => time(),
			'registration_ip' => '127.0.0.1',
			'created_at' => time(),
			'updated_at' => time()
 		]);

		$this->insert('{{%profile%}}', [
			'user_id' => 1,
			'name' => 'root',
			'public_email' => 'root@localhost.com'
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%profile%}}', ['user_id' => 1]);

	    $this->delete('{{%user%}}', ['id' => 1]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230315_183407_add_root_user cannot be reverted.\n";

        return false;
    }
    */
}
