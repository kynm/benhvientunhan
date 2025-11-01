<?php

namespace app\models;

use yii\base\Model;

class CSVCSuDung extends Model
{
    public $ID;
    public $ID_LanKham;
    public $ID_CSVC;
    public $SoLuong;
    public $GhiChu;

    public function rules()
    {
        return [
            [['ID_CSVC', 'SoLuong'], 'required'],
            [['ID_CSVC', 'SoLuong'], 'integer', 'min' => 1],
            [['GhiChu'], 'string', 'max' => 255],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'ID_CSVC' => 'Dịch Vụ',
            'SoLuong' => 'Số Lượng',
        ];
    }
}