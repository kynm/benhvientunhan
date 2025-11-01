<?php

namespace app\models;

use yii\base\Model;

class HoaDon extends Model
{
    // Khai báo các cột theo cấu trúc CSDL mới
    public $ID;
    public $ID_LanKham;
    public $NgayLap;
    public $TongTien;

    // Thuộc tính ảo để hiển thị trong form/view
    public $MaKham; // Mã khám của LanKham
    public $TenBN; // Tên bệnh nhân
    public $MaBN; // Mã bệnh nhân (lấy từ LanKham)

    public function rules()
    {
        return [
            [['ID_LanKham', 'TongTien'], 'required'],
            [['ID_LanKham'], 'integer'],
            [['TongTien'], 'number', 'min' => 0],
            [['NgayLap'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'ID' => 'ID Hóa Đơn',
            'ID_LanKham' => 'ID Lần Khám',
            'NgayLap' => 'Ngày Lập',
            'TongTien' => 'Tổng Thanh Toán',
        ];
    }
}