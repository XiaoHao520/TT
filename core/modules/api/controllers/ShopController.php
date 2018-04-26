<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 15:15
 */

namespace app\modules\api\controllers;

use app\models\Shop;
use app\modules\api\models\FileForm;
use app\modules\api\models\ShopListForm;

use app\modules\api\models\ShopForm;
use yii\web\UploadedFile;


class ShopController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
        ]);
    }

    public function actionIndex(){

    echo "shop.index";
    }

     public function actionFile(){
        $fileForm=new FileForm();

      if(\Yii::$app->request->isPost){
            $fileForm->file=UploadedFile::getInstanceByName('file');
            $path=$fileForm->upload();
          if ($path) {
              // 文件上传成功
                 echo $this->renderJson($path);
              return;
          }

      }else{
            echo $this->renderJson("没有获取");
        }

     }


    public function actionAdd(){

        $shop = new Shop();

        if (\Yii::$app->request->isPost) {

            $form = new ShopForm();
            $form->store_id = $this->store->id;
            $form->shop = $shop;
            $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->save());
        }

    }

    //门店列表
    public function actionList()
    {
        $form = new ShopListForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    //门店详情
    public function actionDetail()
    {
        $form = new ShopForm();
        $form->store_id = $this->store->id;

        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->search());
    }
}