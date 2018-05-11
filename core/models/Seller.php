<?php

namespace app\models;
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2018-05-10
 * Time: 10:56
 */

/**
 * This is the model class for table "{{%seller}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $store_id
 * @property integer $addtime
 * @property integer $shop_id
 */

class Seller extends \yii\db\ActiveRecord{
    /**/

    public static function tableName()
    {
        return '{{%seller}}';
    }

    public function rules()
    {
        return [
            [['password','username'],'required','on'=>'SUCCESS'],
            [['addtime','store_id','shop_id'],'integer'],
        ];
    }
}

