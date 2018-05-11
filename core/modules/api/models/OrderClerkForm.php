<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 17:20
 */

namespace app\modules\api\models;


use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Setting;
use app\models\User;
use app\modules\mch\models\ShareSettingForm;
use function GuzzleHttp\Promise\all;
use yii\db\Exception;
use yii\db\StaleObjectException;

class OrderClerkForm extends Model
{
    public $order_id;
    public $store_id;
    public $user_id;

    public function save()
    {
        $order = Order::findOne(['id' => $this->order_id, 'store_id' => $this->store_id, 'is_pay' => 1]);
        //$oderDetail_list=OrderDetail::find()->where(['order_id'=>$order->id])->asArray()->all();
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '网络异常-1'
            ];
        }
        $user = User::findOne(['id' => $this->user_id]);
        if ($user->is_clerk == 0) {
            return [
                'code' => 1,
                'msg' => '不是核销员'
            ];
        }
        $is_dock_clerk = false;
        if ($order->dock_id == $user->dock_id) {
            $is_dock_clerk = true;
        }

        if (!$is_dock_clerk) {
            return [
                'code' => 1,
                'msg' => '你不是本店的核销员',

            ];
        }
        if ($order->is_send == 1) {
            if ($order->is_confirm == 1) {


                return [
                    'code' => 1,
                    'msg' => '订单已核销'
                ];
            }
        }

        $order->clerk_id = $user->id;
        $order->is_send = 1;
        $order->shop_id = $user->shop_id;
        $order->dock_id = $user->dock_id;
        $order->send_time = time();
        $order->is_confirm = 1;
        $order->confirm_time = time();
        //当这个订单完成了

        $store_setting = Setting::findOne(['store_id' => $this->store_id]);
        if ($store_setting) {
            $parent_id = 0;
            $order_temp = Order::findOne(['id' => $this->order_id]);
            $first_price = $order_temp->first_price;
            $second_price = $order_temp->second_price;
            $third_price = $order_temp->third_price;
            for ($i = 0; $i < intval($store_setting->level); $i++) {
                if ($parent_id == 0) {
                    //算1级的钱
                    $parent_id = $order_temp->parent_id;
                    $parent_money = floatval($first_price);
                    $user = User::findOne($parent_id);
                    if($user){
                        $user->total_price = floatval($user->total_price) + $parent_money;
                        $user->price=floatval($user->price) + $parent_money;
                        $parent_id = $user->parent_id;
                        try {
                            $user->update();
                            if ($parent_id == 0) {
                                break;
                            }
                        } catch (StaleObjectException $e) {
                        } catch (\Exception $e) {
                        }
                    }


                } else {
                    $currnt_user_id = $parent_id;
                    $user = User::findOne(["id" => $currnt_user_id]);
                    if(!$user){
                           break;
                    }

                    $parent_id = $user->parent_id;
                    if ($i == 1) {
                        $parent_money = floatval($second_price);
                    }
                    if ($i == 2) {
                        $parent_money = floatval($third_price);
                    }
                    $user->total_price = floatval($user->total_price) + $parent_money;
                    $user->price=floatval($user->price) + $parent_money;
                    $parent_id = $user->parent_id;
                    try {
                        $user->update();
                        if ($parent_id == 0) {
                            break;
                        }
                    } catch (StaleObjectException $e) {
                    } catch (\Exception $e) {
                    }
                }
            }
        }


           if ($order->save()) {
              return [
                  'code' => 0,
                  'msg' => '成功'
              ];
          } else {
              return [
                  'code' => 1,
                  'msg' => '网络异常',
                  //'setting'=>$store_setting
              ];
          }
    }
}