<?php

namespace app\models;

use yii\base\Model;

class NhanVien extends Model
{
    // Bắt buộc: Khai báo tất cả các cột là public properties
    public $ID_NHANVIEN;
    public $MaNV;
    public $TenNV;
    public $VaiTro;
    public $GioiTinh;
    public $NgaySinh;
    public $DienThoai;
    public $Email;
    public $DiaChi;
    public $ChuyenKhoa;
    public $HeSoLuong;
    public $username; 
    public $password_hash;
    public $auth_key;

    public function rules()
    {
        return [
            [['MaNV', 'TenNV', 'VaiTro', 'HeSoLuong'], 'required'],
            [['MaNV', 'VaiTro'], 'string', 'max' => 10],
            [['TenNV', 'Email', 'DiaChi', 'ChuyenKhoa'], 'string', 'max' => 255],
            [['GioiTinh'], 'string', 'max' => 5],
            [['NgaySinh'], 'safe'], 
            [['DienThoai'], 'string', 'max' => 15],
            [['Email'], 'email'],
            [['HeSoLuong'], 'number'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'MaNV' => 'Mã Nhân Viên',
            'TenNV' => 'Tên Nhân Viên',
            'VaiTro' => 'Vai Trò',
            'GioiTinh' => 'Giới Tính',
            'NgaySinh' => 'Ngày Sinh',
            'DienThoai' => 'Điện Thoại',
            'ChuyenKhoa' => 'Chuyên Khoa',
            'HeSoLuong' => 'Hệ Số Lương',
            //...
        ];
    }
}