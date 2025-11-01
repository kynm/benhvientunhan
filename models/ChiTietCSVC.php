<?php

namespace app\models;

use yii\base\Model;

class ChiTietCSVC extends Model
{
    public $ID;
    public $ID_LanKham;
    public $MaCSVC;
    public $SoLuong;
    public $DonGia;

    public function rules()
    {
        return [
            [['ID_LanKham', 'MaCSVC', 'SoLuong', 'DonGia'], 'required'],
            [['ID', 'ID_LanKham'], 'integer'],
            [['SoLuong'], 'integer', 'min' => 1],
            [['DonGia'], 'number', 'min' => 0],
            [['MaCSVC'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ID' => 'ID Chi Tiết CSVC',
            'ID_LanKham' => 'ID Lần Khám',
            'MaCSVC' => 'Mã Dịch Vụ/Vật Tư',
            'SoLuong' => 'Số Lượng',
            'DonGia' => 'Đơn Giá',
        ];
    }
}