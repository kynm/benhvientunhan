<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CsvcSearch extends CSVC
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['GiaTien'], 'number'],
            [['MaCSVC', 'TenCSVC', 'MoTa'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = CSVC::find();
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
        $query->andFilterWhere(['like', 'MaCSVC', $this->MaCSVC])
            ->andFilterWhere(['like', 'TenCSVC', $this->TenCSVC])
            ->andFilterWhere(['like', 'MoTa', $this->MoTa]);

        return $dataProvider;
    }
}