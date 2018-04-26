<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attr}}".
 *
 * @property integer $id
 * @property integer $attr_group_id
 * @property string $attr_name
 * @property integer $is_delete
 * @property integer $is_default
 * @property string $attr_detail
 */
class Attr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attr_group_id', 'attr_name'], 'required'],
            [['attr_group_id', 'is_delete', 'is_default'], 'integer'],
            [['attr_name','attr_detail'], 'string', 'max' => 255],
            [['attr_detail'], 'default', 'value' => '默认'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attr_group_id' => 'Attr Group ID',
            'attr_name' => 'Attr Name',
            'is_delete' => 'Is Delete',
            'is_default' => '是否是默认属性',
            'attr_detail'=>'属性描述'
        ];
    }
}
