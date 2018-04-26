<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/24
 * Time: 14:44
 */

namespace app\modules\api\models;


use app\models\Shop;
use app\models\ShopPic;

class ShopForm extends Model
{
    public $store_id;
    public $user;
    public $shop;
    public $limit;

    public $name;
    public $mobile;
    public $address;
    public $longitude;
    public $latitude;
    public $score;
    public $cover_url;
    public $pic_url;
    public $content;
    public $shop_time;
    public $shop_pic;
    public $user_id;



/*    public function rules()
    {
        return [
            [['shop_id'], 'integer']
        ];
    }*/


    public function rules()
    {
        return [
            [['user_id'],'integer'],
            [['name', 'mobile', 'address','latitude','longitude'], 'required'],
            [['name', 'mobile', 'address','latitude','longitude','cover_url','pic_url','content','shop_time','shop_pic'], 'string'],
            [['name', 'mobile', 'address','cover_url','pic_url','content','shop_time'], 'trim'],
            [['score'],'integer','min'=>1,'max'=>5],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'门店名称',
            'mobile'=>'联系方式',
            'address'=>'门店地址',
            'latitude'=>'经纬度',
            'longitude'=>'经纬度',
            'score'=>'评分',
            'cover_url'=>'门店大图',
            'pic_url'=>'门店小图',
            'content'=>'门店介绍',
            'shop_time'=>'营业时间',
        ];
    }

    public function save()
    {


        if (!$this->validate()) {
            return $this->getModelError();
        }
        $shop = $this->shop;
        if($shop->isNewRecord){
            $shop->is_delete = 0;
            $shop->addtime = time();
            $shop->store_id = $this->store_id;
            $shop->user_id=$this->user_id;
        }
        $shop->attributes = $this->attributes;
        if(is_array($this->shop_pic)){
            $shop->cover_url = $this->shop_pic[0];
        }
        $shop_pic='';
        if(!is_array($this->shop_pic)){
           $shop_pic= explode(",",$this->shop_pic);
            $shop->cover_url = $shop_pic[0];
        }
        if ($shop->save()) {
            ShopPic::updateAll(['is_delete' => 1], ['shop_id' => $shop->id]);
            foreach($shop_pic as $pic_url){
                $shop_pic = new ShopPic();
                $shop_pic->shop_id = $shop->id;
                $shop_pic->pic_url = $pic_url;
                $shop_pic->store_id = $shop->store_id;
                $shop_pic->is_delete = 0;
                $shop_pic->save();
            }
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常'
            ];
        }
    }






    public function search()
    {
        if (!$this->validate()) {
            $this->getModelError();
        }
        $shop = Shop::find()->where([
            'store_id' => $this->store_id, 'user_id' => $this->user_id, 'is_delete' => 0
        ])->asArray()->one();

        if (!$shop) {
            return [
                'code' => 1,
                'msg' => '店铺不存在',
                'user_id'=>$this->user_id
            ];
        }
        $shop_pic = ShopPic::find()->select(['pic_url'])->where(['store_id' => $this->store_id, 'shop_id' => $shop['id'], 'is_delete' => 0])->column();
        $shop['pic_list'] = $shop_pic;
        if (!$shop_pic) {
            $shop['pic_list'] = [$shop['pic_url']];
        }

        foreach ($shop as $index => $value) {
            if (!$value) {
                if (in_array($index, ['pic_url', 'cover_url', 'pic_list'])) {
                    continue;
                }
                $shop[$index] = "暂无设置";
            }
        }

        return [
            'code' => 0,
            'msg' => '',
            'data' => [
                'shop' => $shop
            ]
        ];
    }
}