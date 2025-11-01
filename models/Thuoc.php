<?php

namespace app\models;

use yii\base\Model;

class Thuoc extends Model
{
    // BẮT BUỘC: Khai báo tất cả các cột là public properties
    public $ID;
    public $MaThuoc;
    public $TenThuoc;
    public $DonViTinh;
    public $GiaTien;
    public $GhiChu; // Nếu bảng Thuoc có cột GhiChu

    public function rules()
    {
        return [
            [['MaThuoc', 'TenThuoc', 'DonViTinh', 'GiaTien'], 'required'],
            [['GiaTien'], 'number', 'min' => 0],
            [['MaThuoc'], 'string', 'max' => 20],
            [['TenThuoc', 'DonViTinh', 'GhiChu'], 'string', 'max' => 255],
            // Loại bỏ các validation liên quan đến CSDL như 'unique'
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'MaThuoc' => 'Mã Thuốc',
            'TenThuoc' => 'Tên Thuốc',
            'DonViTinh' => 'Đơn Vị Tính',
            'GiaTien' => 'Giá Tiền',
            'GhiChu' => 'Ghi Chú',
        ];
    }
    
    // Bỏ tất cả các phương thức Active Record (như tableName(), relations())
}