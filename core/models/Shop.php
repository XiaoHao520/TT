<?php

namespace app\models;

use Yii;
use Codeception\PHPUnit\ResultPrinter\HTML;

/**
 * This is the model class for table "{{%shop}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $mobile
 * @property string $address
 * @property integer $is_delete
 * @property integer $addtime
 * @property string $longitude
 * @property string $latitude
 * @property integer $score
 * @property string $cover_url
 * @property string $pic_url
 * @property string $shop_time
 * @property string $content
 * @property string $user_id
 * @property string $username
 * @property string $password
 * @property string $docks_name
 * @property string $docks_id
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete', 'addtime', 'score','user_id'], 'integer'],
            [['longitude', 'latitude', 'cover_url', 'pic_url', 'content','username','password','docks_name','docks_id'], 'string'],
            [['name', 'mobile', 'address', 'shop_time'], 'string', 'max' => 255],
            [['docks_name','docks_id'],'default','value'=>null]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'score' => '评分 1~5',
            'cover_url' => '门店大图',
            'pic_url' => '门店小图',
            'shop_time' => '营业时间',
            'content' => '门店介绍',
            'docks_id'=>'核销码头id',
            'docks_name'=>'核销码头名称'
        ];
    }

    public function getShopPic()
    {
        return $this->hasMany(ShopPic::className(),['shop_id'=>'id'])->where(['is_delete'=>0]);
    }

    public function beforeSave($insert)
    {
        $this->name = \yii\helpers\Html::encode($this->name);
        $this->shop_time = \yii\helpers\Html::encode($this->shop_time);
        $this->content = \yii\helpers\Html::encode($this->content);
        $this->address = \yii\helpers\Html::encode($this->address);
        return parent::beforeSave($insert);
    }
}
