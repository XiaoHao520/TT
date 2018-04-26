<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:01
 */

namespace app\modules\mch\models;

use app\models\Cat;
use app\models\Dock;
use app\models\Model;
use yii\data\Pagination;

class DockForm extends Model
{
    public $dock;
    public $store_id;
    public $parent_id;
    public $name;
    public $address;
    public $latitude;
    public $longitude;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'store_id'], 'required'],
            [['store_id'], 'integer'],
            [['name', 'address'], 'string'],
            [['latitude', 'longitude'], 'double'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '码头名称',
            'address' => '码头地址',
            'latitude'=>'纬度',
            'longitude'=>'经度'

        ];
    }

    /**
     * @param $store_id
     * @return array
     * 获取列表数据
     */
    public function getList($store_id)
    {
        $query = Dock::find()->andWhere(['is_delete' => 0, 'store_id' => $store_id]);
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $list = $query
            ->orderBy('addtime ASC')
            ->offset($p->offset)
            ->limit($p->limit)
            ->asArray()
            ->all();

        return [$list, $p];
    }

    /**
     * 编辑
     * @return array
     */
    public function save()
    {
        if ($this->validate()) {
            $dock = $this->dock;
            if ($dock->isNewRecord) {
                $dock->is_delete = 0;
                $dock->addtime = time();
            }
            $dock->attributes = $this->attributes;
            return $dock->saveDock();
        } else {
            return $this->getModelError();
        }
    }

}