<?php

namespace app\models;

use yii\base\Model;

class ChiTietKham extends Model
{
    public $ID;
    public $ID_LanKham;
    public $MaBenh; // Cột DB
    
    public function rules()
    {
        return [
            [['ID_LanKham', 'MaBenh'], 'required'],
            [['ID', 'ID_LanKham'], 'integer'],
            [['MaBenh'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ID' => 'ID Chi Tiết Khám',
            'ID_LanKham' => 'ID Lần Khám',
            'MaBenh' => 'Mã Bệnh (ICD)',
        ];
    }
}