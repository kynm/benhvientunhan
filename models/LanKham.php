<?php

namespace app\models;

use yii\base\Model;

class LanKham extends Model
{
    // Cột DB (Cần khai báo TẤT CẢ)
    public $ID;
    public $MaKham;
    public $MaBN;
    public $ThoiGianKham;
    public $ID_BS;
    public $KhoaKham;
    public $TongTienKham;
    
    // Thuộc tính ẢO
    public $LyDoKham; 
    public $ChanDoan; 
    public $KetQua; 

    // Thuộc tính ảo (cho View)
    public $TenBN;
    public $TenBS;

    public function rules()
    {
        return [
            // MaKham không required vì nó được tạo bởi Trigger, nhưng các cột DB khác thì required
            [['MaBN', 'ID_BS', 'KhoaKham', 'LyDoKham', 'ChanDoan'], 'required'], 
            
            [['ID', 'ID_BS'], 'integer'],
            [['MaBN', 'KhoaKham', 'MaKham'], 'string', 'max' => 50],
            [['ThoiGianKham'], 'safe'],
            [['TongTienKham'], 'number'], 
            [['LyDoKham', 'ChanDoan', 'KetQua'], 'string'], 
            [['TenBN', 'TenBS'], 'safe'], 
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'ID' => 'ID Lần Khám',
            'MaKham' => 'Mã Phiếu Khám',
            'MaBN' => 'Mã Bệnh Nhân',
            'ThoiGianKham' => 'Thời Gian Khám',
            'ID_BS' => 'Bác Sĩ Khám',
            'KhoaKham' => 'Khoa Khám',
            'TongTienKham' => 'Tổng Tiền Khám',
            'LyDoKham' => 'Lý Do Khám', 
            'ChanDoan' => 'Chẩn Đoán',
            'KetQua' => 'Kết Quả (Chẩn Đoán)',
        ];
    }
}