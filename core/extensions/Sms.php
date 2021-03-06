<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3
 * Time: 14:58
 */

namespace app\extensions;


use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use app\models\Goods;
use app\models\OrderDetail;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\SmsRecord;
use app\models\SmsSetting;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

require_once dirname(__DIR__) . '/extensions/aliyun/api_demo/SmsDemo.php';
require_once dirname(__DIR__) . '/extensions/alidayu/TopSdk.php';

class Sms
{
    /**
     * 发送短信
     *
     * 短信通知
     * @param string $store_id 商铺ID
     * @param string $content 内容，字符串
     * @return array
     */
    public static function send($store_id, $content = null, $order)
    {
//        require \Yii::$app->basePath . '/extensions/aliyun/api_demo/SmsDemo.php';
        $sms_setting = SmsSetting::findOne(['is_delete' => 0, 'store_id' => $store_id]);


        $detail = OrderDetail::findOne(['order_id' => $order->id]);
        $goods = Goods::findOne(['id' => $detail->goods_id]);
        if ($sms_setting->status == 0) {
            return [
                'code' => 1,
                'msg' => '短信通知服务未开启'
            ];
        }

        // $content_sms[$sms_setting->msg] =$detail->goods_id.substr($content, -8)
        $content_sms['order'] = $content;
        $content_sms['contact'] = $order->name;
        $content_sms['number'] = $order->mobile;
        $content_sms['goods_name'] = $goods->name;
        $baoxian = $order->baoxian;
        $baoxian = json_decode($baoxian, true);
        if (!empty($baoxian)) {
            $content_sms['name'] = $baoxian[0]['name'];
            $content_sms['idCard'] = $baoxian[0]['idCard'];
            $content_sms['tel'] = $baoxian[0]['tel'];
        } else {
            return;

        }


        $res = null;
        $resp = null;
        $a = str_replace("，", ",", $sms_setting->mobile);
        $g = explode(",", $a);
        if ($detail->tpl_user != null) {
            $g = explode(",", $detail->tpl_user);
        }


        foreach ($g as $mobile) {
            try {
                $acsClient = new \SmsDemo($sms_setting->AccessKeyId, $sms_setting->AccessKeySecret);
                $res = $acsClient->sendSms($sms_setting->sign, $sms_setting->tpl, $mobile, $content_sms);
            } catch (\Exception $e) {
                \Yii::warning("阿里云短信调用失败：" . $e->getMessage());
                try {
                    $c = new \TopClient();
                    $c->appkey = $sms_setting->AccessKeyId;
                    $c->secretKey = $sms_setting->AccessKeySecret;
                    $req = new \AlibabaAliqinFcSmsNumSendRequest;
                    $req->setSmsType("normal");
                    $req->setSmsFreeSignName($sms_setting->sign);
                    $req->setSmsParam(json_encode($content_sms, JSON_UNESCAPED_UNICODE));
                    $req->setRecNum($mobile);
                    $req->setSmsTemplateCode($sms_setting->tpl);
                    $resp = $c->execute($req);
                } catch (\Exception $e) {
                    \Yii::warning("阿里大鱼调用失败：" . $e->getMessage());

                }
            }
            \Yii::trace("短信发送结果：" . $resp ? json_encode($resp, JSON_UNESCAPED_UNICODE) : json_encode($res, JSON_UNESCAPED_UNICODE));
        }
        if (($res && $res->Code == "OK") || ($resp && $resp->code == 0)) {
            if (is_array($content_sms)) {
                foreach ($content_sms as $k => $v)
                    $content_sms[$k] = strval($v);
                $content_sms = json_encode($content_sms, JSON_UNESCAPED_UNICODE);
            }
            $smsRecord = new SmsRecord();
            $smsRecord->mobile = $sms_setting->mobile;
            $smsRecord->tpl = $sms_setting->tpl;
            $smsRecord->content = $content_sms;
            $smsRecord->ip = \Yii::$app->request->userIP;
            $smsRecord->addtime = time();
            $smsRecord->save();
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => $res->Message . $resp->sub_msg
            ];
        }
    }

    /**
     * 发送短信  退款通知
     * @param string $store_id 商铺ID
     * @param string $content 内容，字符串
     * @return array
     */
    public static function send_refund($store_id, $content = null)
    {
//        require \Yii::$app->basePath . '/extensions/aliyun/api_demo/SmsDemo.php';
        $sms_setting = SmsSetting::findOne(['is_delete' => 0, 'store_id' => $store_id]);
        if ($sms_setting->status == 0) {
            return [
                'code' => 1,
                'msg' => '短信通知服务未开启'
            ];
        }
//        $content_sms[$sms_setting->msg] = substr($content, -8);
        $res = null;
        $resp = null;

        $a = str_replace("，", ",", $sms_setting->mobile);
        $g = explode(",", $a);
        $tpl = json_decode($sms_setting->tpl_refund, true);
        if (!is_array($tpl)) {
            return [
                'code' => 1,
                'msg' => '未设置退款短信'
            ];
        }
        foreach ($g as $mobile) {
            try {
                $acsClient = new \SmsDemo($sms_setting->AccessKeyId, $sms_setting->AccessKeySecret);
                $res = $acsClient->sendSms($sms_setting->sign, $tpl['tpl'], $mobile, '');
            } catch (\Exception $e) {
                \Yii::warning("阿里云短信调用失败：" . $e->getMessage());
                try {
                    $c = new \TopClient();
                    $c->appkey = $sms_setting->AccessKeyId;
                    $c->secretKey = $sms_setting->AccessKeySecret;
                    $req = new \AlibabaAliqinFcSmsNumSendRequest;
                    $req->setSmsType("normal");
                    $req->setSmsFreeSignName($sms_setting->sign);
                    $req->setRecNum($mobile);
                    $req->setSmsTemplateCode($tpl['tpl']);
                    $resp = $c->execute($req);
                } catch (\Exception $e) {
                    \Yii::warning("阿里大鱼调用失败：" . $e->getMessage());

                }
            }
            \Yii::trace("短信发送结果：" . $resp ? json_encode($resp, JSON_UNESCAPED_UNICODE) : json_encode($res, JSON_UNESCAPED_UNICODE));
        }
        if (($res && $res->Code == "OK") || ($resp && $resp->code == 0)) {
            $smsRecord = new SmsRecord();
            $smsRecord->mobile = $sms_setting->mobile;
            $smsRecord->tpl = $tpl['tpl'];
            $smsRecord->content = '';
            $smsRecord->ip = \Yii::$app->request->userIP;
            $smsRecord->addtime = time();
            $smsRecord->save();
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => $res->Message . $resp->sub_msg
            ];
        }
    }


    public static function ptsend($store_id, $order_id)
    {
        $connection = \Yii::$app->db;
        $sql = "select * from hjmall_sms2_setting WHERE is_delete =0 AND  store_id=:store_id limit 1";
        $command = $connection->createCommand($sql)->bindValue(':store_id',$store_id);
        try {
            $sms_setting = $command->queryOne();
        } catch (Exception $e) {
        }
        if (!$sms_setting) {
            return "数据库中的 短信接口有问题";
        }
        $order = PtOrder::findOne(['id' => $order_id]);
        if (!$order) {
            return '订单找不到';
        }


        $detail = PtOrderDetail::findOne(['order_id' => $order_id]);
        $goods = PtGoods::findOne(['id' => $detail->goods_id]);
        $content_sms['goods_name'] = $goods->name;
   /*     $content_sms['goods_price'] = $detail->total_price;*/
        $baoxian = $order->baoxian;


     /*   if($baoxian==0){
            $content_sms['name'] = 'xiaohao';
            $content_sms['idCard'] = 'hhahah';
            $content_sms['tel'] = '132152454';

        }else{
            $baoxian = json_decode($baoxian, true);
            if (!empty($baoxian)) {
                $content_sms['name'] = $baoxian[0]['name'];
                $content_sms['idCard'] = $baoxian[0]['idCard'];
                $content_sms['tel'] = $baoxian[0]['tel'];
            }

        }*/

        $notice=$goods->notice;
        if($notice!=0){
                $notice= explode(",",$notice);
                  for($i=0;$i<count($notice);$i++){

                      $res = null;
                      $resp = null;
                      try {
                          $acsClient = new \SmsDemo('LTAIlDJyrn2EfCwc', 'DTdmT74oFcLbDiDDtgzezT1Iw0k2da');
                          //$res = $acsClient->sendSms('天天出海', 'SMS_133968603', $notice[$i], $content_sms);
                          $res = $acsClient->sendSms('天天出海', 'SMS_133969056', $notice[$i], $content_sms);

                          /*$acsClient = new \SmsDemo($sms_setting['AccessKeyId'], $sms_setting['AccessKeySecret']);
                          $res = $acsClient->sendSms($sms_setting['sign'], $sms_setting['tpl'], $order->mobile, $content_sms);*/
                      } catch (\Exception $e) {
                          \Yii::warning("阿里云短信调用失败：" . $e->getMessage());
                          try {
                              $c = new \TopClient();
                              $c->appkey = $sms_setting['AccessKeyId'];
                              $c->secretKey = $sms_setting['AccessKeySecret'];
                              $req = new \AlibabaAliqinFcSmsNumSendRequest;
                              $req->setSmsType("normal");
                              $req->setSmsFreeSignName($sms_setting['sign']);
                              $req->setSmsParam(json_encode($content_sms, JSON_UNESCAPED_UNICODE));
                              $req->setRecNum($order->mobile);
                              $req->setSmsTemplateCode($sms_setting['tpl']);
                              $resp = $c->execute($req);
                          } catch (\Exception $e) {
                              \Yii::warning("阿里大鱼调用失败：" . $e->getMessage());



                          }
                      }
                      \Yii::trace("短信发送结果：" . $resp ? json_encode($resp, JSON_UNESCAPED_UNICODE) : json_encode($res, JSON_UNESCAPED_UNICODE));

                      if (($res && $res->Code == "OK") || ($resp && $resp->code == 0)) {
                          if (is_array($content_sms)) {
                              foreach ($content_sms as $k => $v)
                                  $content_sms[$k] = strval($v);
                              $content_sms = json_encode($content_sms, JSON_UNESCAPED_UNICODE);
                          }
                          $smsRecord = new SmsRecord();
                          $smsRecord->mobile = $order->mobile;
                          $smsRecord->tpl = $sms_setting['tpl'];
                          $smsRecord->content = $content_sms;
                          $smsRecord->ip = \Yii::$app->request->userIP;
                          $smsRecord->addtime = time();
                          $smsRecord->save();


                          return [
                              'code' => 0,
                              'msg' => '成功'
                          ];
                      } else {
                          return [
                              'code' => 1,
                              'msg' => $res->Message . $resp->sub_msg
                          ];
                      }
                  }

        }














    }


}