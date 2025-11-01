<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use app\models\NhanVien;

class NhanVienController extends Controller
{
    // Hàm tìm kiếm dữ liệu bằng DAO
    protected function findDataById($id)
    {
        // DAO: Truy vấn trực tiếp vào CSDL
        $data = Yii::$app->db->createCommand('SELECT * FROM NhanVien WHERE ID_NHANVIEN = :id', [':id' => $id])->queryOne();

        if ($data !== false) {
            return $data;
        }

        throw new NotFoundHttpException('Nhân viên không tồn tại.');
    }

    public function actionIndex()
    {
        // DAO: Lấy tất cả dữ liệu
        $data = Yii::$app->db->createCommand('SELECT * FROM NhanVien')->queryAll();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => ['pageSize' => 10],
            'key' => 'ID_NHANVIEN', // Khóa chính
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $data = $this->findDataById($id);
        $model = new NhanVien();
        $model->setAttributes($data, false); // Gán dữ liệu vào Base Model
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new NhanVien();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            // DAO: INSERT dữ liệu
            $command = Yii::$app->db->createCommand()->insert('NhanVien', [
                'MaNV' => $model->MaNV,
                'TenNV' => $model->TenNV,
                'VaiTro' => $model->VaiTro,
                'GioiTinh' => $model->GioiTinh,
                'NgaySinh' => $model->NgaySinh,
                'DienThoai' => $model->DienThoai,
                'Email' => $model->Email,
                'DiaChi' => $model->DiaChi,
                'ChuyenKhoa' => $model->ChuyenKhoa,
                'HeSoLuong' => $model->HeSoLuong,
                // Bỏ qua username, password_hash, auth_key vì không dùng login
            ]);
            
            try {
                if ($command->execute()) {
                    Yii::$app->session->setFlash('success', 'Thêm nhân viên thành công.');
                    return $this->redirect(['index']);
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Lỗi lưu dữ liệu: ' . $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $data = $this->findDataById($id);
        
        $model = new NhanVien();
        $model->setAttributes($data, false); // Gán dữ liệu cũ
        $model->ID_NHANVIEN = $id; // Giữ lại ID để biết hàng nào cần update

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            // DAO: UPDATE dữ liệu
            $command = Yii::$app->db->createCommand()->update('NhanVien', [
                'MaNV' => $model->MaNV,
                'TenNV' => $model->TenNV,
                'VaiTro' => $model->VaiTro,
                'GioiTinh' => $model->GioiTinh,
                'NgaySinh' => $model->NgaySinh,
                'DienThoai' => $model->DienThoai,
                'Email' => $model->Email,
                'DiaChi' => $model->DiaChi,
                'ChuyenKhoa' => $model->ChuyenKhoa,
                'HeSoLuong' => $model->HeSoLuong,
            ], 'ID_NHANVIEN = :id', [':id' => $id]);
            
            try {
                if ($command->execute()) {
                    Yii::$app->session->setFlash('success', 'Cập nhật nhân viên thành công.');
                    return $this->redirect(['view', 'id' => $id]);
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Lỗi cập nhật: ' . $e->getMessage());
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        // DAO: DELETE dữ liệu
        $command = Yii::$app->db->createCommand()->delete('NhanVien', 'ID_NHANVIEN = :id', [':id' => $id]);
        
        try {
            if ($command->execute()) {
                Yii::$app->session->setFlash('success', 'Xóa nhân viên thành công.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Lỗi xóa dữ liệu: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }
}