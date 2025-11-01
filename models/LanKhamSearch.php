<?php

namespace app\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use Yii;

/**
 * LanKhamSearch represents the model behind the search form for LanKham.
 * Sử dụng Query Builder và ArrayDataProvider.
 */
class LanKhamSearch extends LanKham
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        // Thêm các thuộc tính dùng cho tìm kiếm (cần giống với LanKham)
        return [
            [['ID', 'ID_BS'], 'integer'],
            [['MaKham', 'MaBN', 'ThoiGianKham', 'KhoaKham'], 'safe'],
            [['TongTienKham'], 'number'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        // Bỏ qua việc triển khai scenarios() trong Model base
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     * @param array $params
     * @return ArrayDataProvider
     */
    public function search($params)
    {
        // 1. Tải dữ liệu và áp dụng validation
        $this->load($params);

        if (!$this->validate()) {
            // Trường hợp lỗi validation, vẫn trả về DataProvider rỗng
            return new ArrayDataProvider([
                'allModels' => [],
            ]);
        }

        // 2. Xây dựng truy vấn cơ sở (Base Query)
        // LƯU Ý: Nếu cần tìm kiếm theo tên BN/BS, bạn phải JOIN các bảng tương ứng.
        $query = (new \yii\db\Query())
            ->from('lankham');

        // 3. Áp dụng các điều kiện lọc (Filter Conditions)
        $query->andFilterWhere([
            'ID' => $this->ID,
            'ID_BS' => $this->ID_BS,
            'TongTienKham' => $this->TongTienKham,
        ]);

        $query->andFilterWhere(['like', 'MaKham', $this->MaKham])
            ->andFilterWhere(['like', 'MaBN', $this->MaBN])
            ->andFilterWhere(['like', 'KhoaKham', $this->KhoaKham]);
        
        // Lọc theo thời gian (cần xử lý khoảng thời gian nếu muốn chính xác hơn)
        if (!empty($this->ThoiGianKham)) {
            // Giả định tìm kiếm chính xác ngày (chứ không phải giờ)
            $query->andFilterWhere(['like', 'ThoiGianKham', $this->ThoiGianKham]);
        }

        // 4. Thực thi truy vấn và lấy tất cả dữ liệu
        // Lấy tất cả dữ liệu thô (raw data) từ CSDL
        $allModels = $query->all();
        
        // 5. Tạo ArrayDataProvider
        // ArrayDataProvider chịu trách nhiệm xử lý sắp xếp và phân trang trên mảng $allModels
        return new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 20, // Số lượng bản ghi mỗi trang
            ],
            'sort' => [
                'attributes' => ['ID', 'MaKham', 'MaBN', 'ThoiGianKham', 'TongTienKham'],
                'defaultOrder' => ['ThoiGianKham' => SORT_DESC],
            ],
        ]);
    }
}