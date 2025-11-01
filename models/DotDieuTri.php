<?php

namespace app\models;

use yii\base\Model;

class DotDieuTri extends Model
{
    // Cột DB (Khớp với cấu trúc bảng)
    public $ID;
    public $ID_LanKham;
    public $MaBenh; // Bắt buộc
    public $ID_BS;    // Bắt buộc
    public $TrangThai; // Không bắt buộc
    public $NgayBatDau; // Kiểu DATE
    public $NgayKetThuc; // Kiểu DATE
    
    public $MaKham; 

    public function rules()
    {
        return [
            // Thêm MaBenh và ID_BS vào required
            [['ID_LanKham', 'MaBenh', 'ID_BS'], 'required'],
            [['ID', 'ID_LanKham', 'ID_BS'], 'integer'],
            // Dùng 'safe' cho ngày tháng (NgayBatDau/NgayKetThuc)
            [['NgayBatDau', 'NgayKetThuc'], 'safe'], 
            [['MaBenh'], 'string', 'max' => 20],
            [['TrangThai'], 'string', 'max' => 50],
            [['MaKham'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'ID' => 'ID Đợt Điều Trị',
            'ID_LanKham' => 'ID Lần Khám',
            'MaBenh' => 'Mã Bệnh Chính',
            'ID_BS' => 'Bác Sĩ Điều Trị',
            'NgayBatDau' => 'Ngày Bắt Đầu',
            'NgayKetThuc' => 'Ngày Kết Thúc',
            'TrangThai' => 'Trạng Thái',
        ];
    }
}