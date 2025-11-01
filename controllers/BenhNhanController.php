<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use app\models\BenhNhan;

class BenhNhanController extends Controller
{
    // Không có AccessControl filters

    /**
     * Finds the BenhNhan data by ID using DAO.
     * @param int $id
     * @return array|false the BenhNhan row data
     * @throws NotFoundHttpException
     */
    protected function findDataById($id)
    {
        $data = Yii::$app->db->createCommand('SELECT * FROM BenhNhan WHERE ID = :id', [':id' => $id])->queryOne();

        if ($data !== false) {
            return $data;
        }

        throw new NotFoundHttpException('Bệnh nhân không tồn tại.');
    }

    public function actionIndex()
    {
        // DAO: Lấy tất cả dữ liệu
        $data = Yii::$app->db->createCommand('SELECT * FROM BenhNhan')->queryAll();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => ['pageSize' => 10],
            'key' => 'ID', 
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            // Không có SearchModel
        ]);
    }

    public function actionView($id)
    {
        $data = $this->findDataById($id);
        
        // Gán dữ liệu vào Base Model để DetailView hoạt động
        $model = new BenhNhan();
        $model->setAttributes($data, false); 
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new BenhNhan();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            // DAO: INSERT dữ liệu
            $command = Yii::$app->db->createCommand()->insert('BenhNhan', [
                'MaBN' => $model->MaBN,
                'TenBN' => $model->TenBN,
                'GioiTinh' => $model->GioiTinh,
                'NgaySinh' => $model->NgaySinh,
                'DienThoai' => $model->DienThoai,
                'Email' => $model->Email,
                'DiaChi' => $model->DiaChi,
            ]);
            
            try {
                if ($command->execute()) {
                    Yii::$app->session->setFlash('success', 'Thêm bệnh nhân thành công.');
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
        
        // Gán dữ liệu cũ vào Base Model
        $model = new BenhNhan();
        $model->setAttributes($data, false);
        $model->ID = $id; // Giữ lại ID để biết hàng nào cần update

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            // DAO: UPDATE dữ liệu
            $command = Yii::$app->db->createCommand()->update('BenhNhan', [
                'MaBN' => $model->MaBN,
                'TenBN' => $model->TenBN,
                'GioiTinh' => $model->GioiTinh,
                'NgaySinh' => $model->NgaySinh,
                // ... các trường khác
            ], 'ID = :id', [':id' => $id]);
            
            try {
                if ($command->execute()) {
                    Yii::$app->session->setFlash('success', 'Cập nhật bệnh nhân thành công.');
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
        $command = Yii::$app->db->createCommand()->delete('BenhNhan', 'ID = :id', [':id' => $id]);
        
        try {
            if ($command->execute()) {
                Yii::$app->session->setFlash('success', 'Xóa bệnh nhân thành công.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Lỗi xóa dữ liệu: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }
}