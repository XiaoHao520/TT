<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2018-04-07
 * Time: 15:01
 */
namespace app\modules\mch\controllers;
use app\models\JiFenSetting;

class JiFenSettingController extends Controller{
    public function actionIndex(){

        echo "hello wolrd";
       /* $jifen_list=JiFenSetting::find()->where(['store_id'=>$this->store->id,'is_delete'=>0])->all();
        return $this->render('index', [
            'setting_list' => $jifen_list,
        ]);*/
    }

}