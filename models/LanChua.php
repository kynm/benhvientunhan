<?php

namespace app\models;

use yii\base\Model;

class LanChua extends Model
{
    // Cột chính (từ bảng Lanchua trong CSDL)
    public $ID;
    public $ID_DotDieuTri; // <--- ĐÃ THÊM: Khắc phục lỗi "unknown property"
    public $ThoiGianChua;
    public $PhuongPhap; // Map với cột HinhThucChua trong DB
    public $ID_BS;
    public $KetLuan; // Nếu bạn muốn sử dụng

    // Thuộc tính ảo (dùng cho Controller/View/Index)
    public $TongTienChua; // Tổng tiền tính toán từ chi tiết (không có trong bảng LanChua)
    public $MaBN; // Mã BN (lấy qua JOIN)
    public $TenBN; // Tên bệnh nhân (lấy qua JOIN)
    public $TenBS; // Tên bác sĩ (lấy qua JOIN)

    public function rules()
    {
        return [
            // Đã thay MaChua/MaBN bằng ID_DotDieuTri
            [['ID_DotDieuTri', 'ThoiGianChua', 'PhuongPhap', 'ID_BS'], 'required'], 
            [['ID', 'ID_BS', 'ID_DotDieuTri'], 'integer'], // <--- ĐÃ THÊM: ID_DotDieuTri vào integer
            [['TongTienChua'], 'number'],
            [['ThoiGianChua'], 'safe'],
            [['PhuongPhap', 'KetLuan'], 'string', 'max' => 255],
            [['MaBN', 'TenBN', 'TenBS'], 'safe'], // Thuộc tính ảo
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'ID_DotDieuTri' => 'Đợt Điều Trị Liên Quan',
            'ThoiGianChua' => 'Thời Gian Điều Trị',
            'PhuongPhap' => 'Hình Thức Điều Trị',
            'TongTienChua' => 'Tổng Tiền Điều Trị (Tạm tính)',
            'MaBN' => 'Mã Bệnh Nhân',
            'ID_BS' => 'Bác Sĩ Thực Hiện',
            'KetLuan' => 'Kết Luận Lần Chữa',
        ];
    }
}