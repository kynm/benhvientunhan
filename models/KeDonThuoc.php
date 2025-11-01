<?php

namespace app\models;

use yii\base\Model;

class KeDonThuoc extends Model
{
    public $ID;
    public $ID_LanKham;
    public $ID_THUOC;
    public $SoLuong;
    public $CachDung;
    public $GhiChu;

    public function rules()
    {
        return [
            [['ID_THUOC', 'SoLuong', 'CachDung'], 'required'],
            [['ID_THUOC', 'SoLuong'], 'integer', 'min' => 1],
            [['CachDung', 'GhiChu'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ID_THUOC' => 'Thuốc',
            'SoLuong' => 'Số Lượng',
            'CachDung' => 'Cách Dùng',
        ];
    }
}