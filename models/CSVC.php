<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CSVC".
 *
 * @property int $ID
 * @property string $MaCSVC
 * @property string $TenCSVC
 * @property string|null $MoTa
 * @property float|null $GiaTien
 *
 * @property ChiTietCSVC[] $chiTietCsvcs
 */
class CSVC extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CSVC';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['MaCSVC', 'TenCSVC'], 'required'],
            [['MoTa'], 'string'],
            [['GiaTien'], 'number'],
            [['MaCSVC'], 'string', 'max' => 20],
            [['TenCSVC'], 'string', 'max' => 255],
            [['MaCSVC'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'MaCSVC' => 'Mã CSVC',
            'TenCSVC' => 'Tên CSVC',
            'MoTa' => 'Mô tả',
            'GiaTien' => 'Giá tiền',
        ];
    }

    /**
     * Gets query for [[ChiTietCsvcs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChiTietCsvcs()
    {
        return $this->hasMany(ChiTietCSVC::class, ['MaCSVC' => 'MaCSVC']);
    }
}