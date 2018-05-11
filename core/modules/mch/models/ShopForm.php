<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22
 * Time: 17:06
 */

namespace app\modules\mch\models;


use app\models\Order;
use app\models\Seller;
use app\models\Shop;
use app\models\ShopPic;
use app\models\Store;
use app\models\UserCard;
use yii\data\Pagination;

/**
 * @property \app\models\Shop $shop
 */
class ShopForm extends Model
{
    public $store_id;
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
    public $username;
    public $password;
    public $docks_name;
    public $docks_id;
    public $login_address;

    public function rules()
    {
        return [
            [['name', 'mobile', 'address','latitude','longitude','login_address'], 'required'],
            [['name', 'mobile', 'address','latitude','longitude','cover_url','pic_url','content','shop_time','username','password','docks_name','docks_id','login_address'], 'string'],
            [['name', 'mobile', 'address','cover_url','pic_url','content','shop_time'], 'trim'],
            [['score'],'integer','min'=>1,'max'=>5],
            [['shop_pic'],'safe']
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
            'docks_name'=>'码头名称',
            'docks_id'=>'码头id',
            'login_address'=>'后台登录地址'
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

        }
         $shop->login_address=$this->login_address;

        if($this->docks_name==''){
            $this->docks_id=null;
        }
        $shop->attributes = $this->attributes;
        if(is_array($this->shop_pic)){
            $shop->cover_url = $this->shop_pic[0];
        }
        if ($shop->save()) {
            ShopPic::updateAll(['is_delete' => 1], ['shop_id' => $shop->id]);
            foreach($this->shop_pic as $pic_url){
                $shop_pic = new ShopPic();
                $shop_pic->shop_id = $shop->id;
                $shop_pic->pic_url = $pic_url;
                $shop_pic->store_id = $shop->store_id;
                $shop_pic->is_delete = 0;
                $shop_pic->save();
            }
            if ($this->username!=''&&$this->password!=''){

               $seller=Seller::findOne(['shop_id'=>$shop->id,'store_id'=>$this->store_id]);
                if($seller){
                    $seller->password=md5($this->password);
                    $seller->username=$this->username;
                    $seller->addtime=time();
                    $seller->save();
                }else{
                    $seller=new Seller();
                    $seller->password=md5($this->password);
                    $seller->username=$this->username;
                    $seller->addtime=time();
                    $seller->store_id=$this->store_id;
                    $seller->shop_id=$shop->id;
                    $seller->save();
                }
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


    public function getList()
    {
        $query = Shop::find()->where(['is_delete' => 0, 'store_id' => $this->store_id]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $list = $query->offset($p->offset)->limit($p->limit)->asArray()->all();
        $store = Store::findOne(['id' => $this->store_id]);
        $time = time() - $store->after_sale_time * 86400;
        foreach ($list as $index => $value) {
            $order_count = Order::find()->where([
                'store_id'=>$this->store_id,'is_delete'=>0,'shop_id'=>$value['id'],'is_cancel'=>0
            ])->count();
            $list[$index]['order_count'] = $order_count;
            $list[$index]['card_count'] = UserCard::find()->where([
                'store_id'=>$this->store_id,'is_delete'=>0,'shop_id'=>$value['id']
            ])->count();
        }
        return [
            'row_count' => $count,
            'pagination' => $p,
            'list' => $list
        ];
    }
}