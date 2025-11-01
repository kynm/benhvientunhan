<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class HoaDonSearch extends HoaDon
{
    public $ten_benh_nhan;
    public $ma_kham;

    public function rules()
    {
        return [
            [['ID', 'ID_LanKham'], 'integer'],
            [['TongTien'], 'number'],
            [['NgayLap', 'ten_benh_nhan', 'ma_kham'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = HoaDon::find()
            ->joinWith(['lanKham.benhNhan']); // Join qua LanKham để lấy thông tin BN

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->sort->attributes['ten_benh_nhan'] = [
            'asc' => ['BenhNhan.TenBN' => SORT_ASC],
            'desc' => ['BenhNhan.TenBN' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['ma_kham'] = [
            'asc' => ['LanKham.MaKham' => SORT_ASC],
            'desc' => ['LanKham.MaKham' => SORT_DESC],
        ];

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'HoaDon.ID' => $this->ID,
            'TongTien' => $this->TongTien,
        ]);

        $query->andFilterWhere(['like', 'NgayLap', $this->NgayLap])
            ->andFilterWhere(['like', 'BenhNhan.TenBN', $this->ten_benh_nhan])
            ->andFilterWhere(['like', 'LanKham.MaKham', $this->ma_kham]);

        return $dataProvider;
    }
}