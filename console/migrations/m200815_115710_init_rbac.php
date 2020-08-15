<?php

use yii\db\Migration;

/**
 * Class m200815_115710_init_rbac
 */
class m200815_115710_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        $dashboard = $auth->createPermission('dashboard');
        $dashboard->description = 'Admin dashboard access';
        $auth->add($dashboard);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $dashboard);

        $auth->assign($admin, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = \Yii::$app->authManager;

        $auth->removeAll();
    }
}
