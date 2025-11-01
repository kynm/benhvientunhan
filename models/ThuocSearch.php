<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ThuocSearch extends Thuoc
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['GiaTien'], 'number'],
            [['MaThuoc', 'TenThuoc', 'DonViTinh'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Thuoc::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'ID' => $this->ID,
            'GiaTien' => $this->GiaTien,
        ]);
        $query->andFilterWhere(['like', 'MaThuoc', $this->MaThuoc])
            ->andFilterWhere(['like', 'TenThuoc', $this->TenThuoc])
            ->andFilterWhere(['like', 'DonViTinh', $this->DonViTinh]);

        return $dataProvider;
    }
}