<?php
namespace app\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NhanVienSearch extends NhanVien
{
    public function rules()
    {
        return [
            [['ID_NHANVIEN'], 'integer'],
            [['HeSoLuong'], 'number'],
            [['MaNV', 'TenNV', 'VaiTro', 'GioiTinh', 'NgaySinh', 'DienThoai', 'Email', 'DiaChi', 'ChuyenKhoa'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = NhanVien::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'ID_NHANVIEN' => $this->ID_NHANVIEN,
            'NgaySinh' => $this->NgaySinh,
            'HeSoLuong' => $this->HeSoLuong,
        ]);
        $query->andFilterWhere(['like', 'MaNV', $this->MaNV])
            ->andFilterWhere(['like', 'TenNV', $this->TenNV])
            ->andFilterWhere(['like', 'VaiTro', $this->VaiTro])
            ->andFilterWhere(['like', 'ChuyenKhoa', $this->ChuyenKhoa]);

        return $dataProvider;
    }
}