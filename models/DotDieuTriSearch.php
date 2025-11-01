<?php

namespace app\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use Yii;
use yii\db\Query;

class DotDieuTriSearch extends DotDieuTri
{
    public function rules()
    {
        return [
            [['ID', 'ID_LanKham', 'ID_BS'], 'integer'],
            [['NgayBatDau', 'NgayKetThuc', 'TrangThai', 'MaBenh'], 'safe'],
        ];
    }
    // ... (scenarios)

    public function search($params)
    {
        $this->load($params);

        if (!$this->validate()) {
            return new ArrayDataProvider(['allModels' => []]);
        }

        $query = (new Query())->from('dotdieutri');

        $query->andFilterWhere([
            'ID' => $this->ID,
            'ID_LanKham' => $this->ID_LanKham,
            'ID_BS' => $this->ID_BS, // Thêm ID_BS
        ]);

        $query->andFilterWhere(['like', 'TrangThai', $this->TrangThai])
              ->andFilterWhere(['like', 'MaBenh', $this->MaBenh]) // Thêm MaBenh
              ->andFilterWhere(['like', 'NgayBatDau', $this->NgayBatDau]);
        
        $allModels = $query->all();
        
        return new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => ['ID', 'ID_LanKham', 'NgayBatDau', 'TrangThai'],
                'defaultOrder' => ['NgayBatDau' => SORT_DESC],
            ],
        ]);
    }
}