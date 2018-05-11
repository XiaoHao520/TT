<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2018-05-10
 * Time: 9:19
 */
namespace app\modules\sellers\controllers;



use app\modules\sellers\models\SellerForm;


class SellerController extends Controller {
    public function behaviors()
    {
        return array_merge(parent::behaviors(), []);
    }

    public function actionLogin(){

        if(\Yii::$app->request->isPost){

            $seller=new SellerForm();
            $seller->username=\Yii::$app->request->post('username');
            $seller->password=\Yii::$app->request->post('password');
             if($seller->login()){
                 \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['sellers/goods/goods','seller_id'=>\Yii::$app->session->get('seller')]))->send();
             }else{
                 return $this->render('login',['code'=>1]);
             }
        }
        return $this->render('login',[]);
    }
    public function actionGoods(){
        echo "goods";
    }
}
