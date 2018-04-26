<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2018-04-02
 * Time: 14:32
 */

namespace app\modules\api\models;


class FileForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = 'uploads/image/shop/' . md5(time()) . '.' . $this->file->extension;
            $this->file->saveAs($path);
            $path = 'http://tt.sinbel.cn/addons/zjhj_mall/core/web/' . $path;
            return $path;
        } else {
            return false;
        }
    }

}