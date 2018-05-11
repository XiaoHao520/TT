<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2018-05-10
 * Time: 11:51
 */
namespace app\modules\sellers\controllers;

use app\models\Store;

class Controller extends \app\controllers\Controller{
    public $store;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), []);
    }


    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->store = Store::findOne([
            'id' => \Yii::$app->session->get('store_id'),
        ]);
    }
}


