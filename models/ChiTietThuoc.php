<?php

namespace app\models;

use yii\base\Model;

class ChiTietThuoc extends Model
{
    public $ID;
    public $ID_LanKham;
    public $MaThuoc;
    public $SoLuong;
    public $GiaBan;
    
    public function rules()
    {
        return [
            [['ID_LanKham', 'MaThuoc', 'SoLuong', 'GiaBan'], 'required'],
            [['ID', 'ID_LanKham'], 'integer'],
            [['SoLuong'], 'integer', 'min' => 1],
            [['GiaBan'], 'number', 'min' => 0],
            [['MaThuoc'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ID' => 'ID Chi Tiết Thuốc',
            'ID_LanKham' => 'ID Lần Khám',
            'MaThuoc' => 'Mã Thuốc',
            'SoLuong' => 'Số Lượng',
            'GiaBan' => 'Giá Bán',
        ];
    }
}