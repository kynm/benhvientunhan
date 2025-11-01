<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BenhNhan;

/**
 * BenhNhanSearch represents the model behind the search form of `app\models\BenhNhan`.
 */
class BenhNhanSearch extends BenhNhan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        // Thêm các thuộc tính cần tìm kiếm vào đây
        return [
            [['ID'], 'integer'],
            [['MaBN', 'TenBN', 'GioiTinh', 'NgaySinh', 'DienThoai', 'Email', 'DiaChi'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BenhNhan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => [
                    'ID' => SORT_DESC, // Sắp xếp theo ID mới nhất
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'NgaySinh' => $this->NgaySinh,
            'GioiTinh' => $this->GioiTinh,
        ]);

        $query->andFilterWhere(['like', 'MaBN', $this->MaBN])
            ->andFilterWhere(['like', 'TenBN', $this->TenBN])
            ->andFilterWhere(['like', 'DienThoai', $this->DienThoai])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'DiaChi', $this->DiaChi]);

        return $dataProvider;
    }
}