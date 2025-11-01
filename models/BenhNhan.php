<?php

namespace app\models;

use yii\base\Model;

/**
 * BenhNhan Model - Sử dụng Base Model cho DAO.
 * @property int $ID
 * @property string $MaBN
 * @property string $TenBN
 * @property string $GioiTinh
 * @property string $NgaySinh
 * @property string $DienThoai
 * @property string $Email
 * @property string $DiaChi
 */
class BenhNhan extends Model
{
    // KHAI BÁO TẤT CẢ CÁC CỘT LÀ PUBLIC PROPERTY
    public $ID;
    public $MaBN;
    public $TenBN;
    public $GioiTinh;
    public $NgaySinh;
    public $DienThoai;
    public $Email;
    public $DiaChi;

    public function rules()
    {
        return [
            [['MaBN', 'TenBN', 'DienThoai'], 'required'],
            [['MaBN'], 'string', 'max' => 15],
            [['TenBN', 'Email', 'DiaChi'], 'string', 'max' => 255],
            [['GioiTinh'], 'string', 'max' => 5],
            [['NgaySinh'], 'date', 'format' => 'php:Y-m-d'],
            [['Email'], 'email'],
            // Loại bỏ 'unique' vì nó cần truy vấn database, nên phải kiểm tra thủ công trong Controller
        ];
    }

    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'MaBN' => 'Mã Bệnh Nhân',
            'TenBN' => 'Tên Bệnh Nhân',
            'GioiTinh' => 'Giới Tính',
            'NgaySinh' => 'Ngày Sinh',
            'DienThoai' => 'Điện Thoại',
            'Email' => 'Email',
            'DiaChi' => 'Địa Chỉ',
        ];
    }
    
    // BỎ CÁC PHƯƠNG THỨC ACTIVE RECORD NHƯ tableName()
}