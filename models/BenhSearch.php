<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BenhSearch extends Benh
{
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['MaBenh', 'TenBenh', 'MoTa'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Benh::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'ID' => $this->ID,
        ]);
        $query->andFilterWhere(['like', 'MaBenh', $this->MaBenh])
            ->andFilterWhere(['like', 'TenBenh', $this->TenBenh])
            ->andFilterWhere(['like', 'MoTa', $this->MoTa]);

        return $dataProvider;
    }
}