<?php

namespace app\models;

use yii\base\Model;

class Benh extends Model
{
    // Bắt buộc: Khai báo tất cả các cột là public properties
    public $ID;
    public $MaBenh;
    public $TenBenh;
    public $MoTa;

    public function rules()
    {
        return [
            [['MaBenh', 'TenBenh'], 'required'],
            [['MaBenh'], 'string', 'max' => 20],
            [['TenBenh', 'MoTa'], 'string', 'max' => 255],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'MaBenh' => 'Mã Bệnh',
            'TenBenh' => 'Tên Bệnh',
            'MoTa' => 'Mô Tả',
        ];
    }
}