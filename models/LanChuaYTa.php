<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "LanChua_YTa".
 *
 * @property int $ID_LanChua
 * @property int $ID_YT
 *
 * @property LanChua $lanChua
 * @property NhanVien $yt
 */
class LanChuaYTa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LanChua_YTa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_LanChua', 'ID_YT'], 'required'],
            [['ID_LanChua', 'ID_YT'], 'integer'],
            [['ID_LanChua', 'ID_YT'], 'unique', 'targetAttribute' => ['ID_LanChua', 'ID_YT']],
            [['ID_LanChua'], 'exist', 'skipOnError' => true, 'targetClass' => LanChua::class, 'targetAttribute' => ['ID_LanChua' => 'ID']],
            [['ID_YT'], 'exist', 'skipOnError' => true, 'targetClass' => NhanVien::class, 'targetAttribute' => ['ID_YT' => 'ID_NHANVIEN']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_LanChua' => 'ID Lần chữa',
            'ID_YT' => 'ID Y tá',
        ];
    }

    /**
     * Gets query for [[LanChua]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanChua()
    {
        return $this->hasOne(LanChua::class, ['ID' => 'ID_LanChua']);
    }

    /**
     * Gets query for [[Yt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getYt()
    {
        return $this->hasOne(NhanVien::class, ['ID_NHANVIEN' => 'ID_YT']);
    }
}