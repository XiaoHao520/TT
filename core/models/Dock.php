<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dock}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $address
 * @property integer $addtime
 * @property integer $is_delete
 * @property double $latitude
 * @property double $longitude
 */
class Dock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dock}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name','latitude','longitude'], 'required'],
            [['store_id', 'addtime', 'is_delete',], 'integer'],
            [['name', 'address', ], 'string'],
            [['latitude','longitude'], 'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '商城id',
            'name' => '码头名称',
            'address' => '码头地址',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'latitude' => '纬度',
            'longitude' => '经度',

        ];
    }

    /**
     * @return array
     */
    public function saveDock()
    {
        if ($this->validate()) {
            if ($this->save(false)) {
                return [
                    'code' => 0,
                    'msg' => '成功'
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '失败'
                ];
            }
        } else {
            return (new Model())->getModelError($this);
        }
    }

}
