<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use app\models\HoaDon;

class ThanhToanController extends Controller
{
    /**
     * Hàm tìm kiếm LanKham chưa thanh toán (DAO)
     */
    protected function findLanKhamUnpaid($id)
    {
        // DAO: Lấy LanKham, JOIN với BenhNhan DÙNG MaBN, kiểm tra đã có hóa đơn chưa
        $data = Yii::$app->db->createCommand(
            'SELECT l.*, bn.TenBN 
             FROM lankham l
             LEFT JOIN hoadon hd ON l.ID = hd.ID_LanKham
             LEFT JOIN BenhNhan bn ON l.MaBN = bn.MaBN -- SỬA JOIN SANG MA_BN
             WHERE l.ID = :id AND hd.ID IS NULL', // Chỉ lấy LanKham CHƯA có Hóa Đơn
            [':id' => $id]
        )->queryOne();

        if ($data === false) {
            throw new NotFoundHttpException('Lần khám không tồn tại hoặc đã được thanh toán.');
        }
        return $data;
    }

    public function actionIndex()
    {
        // DAO: Lấy danh sách hóa đơn, thông tin LanKham, và tên BN (DÙNG MA_BN)
        $data = Yii::$app->db->createCommand(
            'SELECT hd.*, lk.MaKham, lk.TongTienKham, lk.MaBN, bn.TenBN 
             FROM hoadon hd
             LEFT JOIN lankham lk ON hd.ID_LanKham = lk.ID
             LEFT JOIN BenhNhan bn ON lk.MaBN = bn.MaBN -- SỬA JOIN SANG MA_BN
             ORDER BY hd.NgayLap DESC'
        )->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => ['pageSize' => 10],
            'key' => 'ID', 
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate($lanKhamId)
    {
        // Bước 1: Lấy thông tin Lần Khám chưa thanh toán
        try {
            $lanKhamData = $this->findLanKhamUnpaid($lanKhamId);
        } catch (NotFoundHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['kham-benh/index']);
        }
        
        $model = new HoaDon();
        $model->ID_LanKham = $lanKhamId;
        
        // Gán thông tin từ LanKham vào Model để hiển thị/tính toán
        $model->TongTien = $lanKhamData['TongTienKham'];
        $model->MaKham = $lanKhamData['MaKham'];
        $model->TenBN = $lanKhamData['TenBN'];
        $model->MaBN = $lanKhamData['MaBN'];

        // Lấy dữ liệu POST và tiến hành lưu
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $db = Yii::$app->db;
                
                // DAO: INSERT Hóa Đơn
                $db->createCommand()->insert('hoadon', [
                    // ID tự động tăng
                    'ID_LanKham' => $model->ID_LanKham,
                    'NgayLap' => date('Y-m-d H:i:s'), 
                    'TongTien' => $model->TongTien,
                ])->execute();
                
                // Không cần cập nhật trạng thái trong LanKham vì hóa đơn là 1-1, 
                // việc kiểm tra đã dựa vào sự tồn tại của bản ghi trong bảng Hóa Đơn.

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Lập Hóa Đơn cho Lần Khám ' . $model->MaKham . ' thành công.');
                return $this->redirect(['index']); 
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Lỗi lưu dữ liệu: ' . $e->getMessage());
                Yii::error($e->getMessage());
            }
        }
        
        // Render view Create (chỉ cần xác nhận)
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            // DAO: DELETE Hóa Đơn (Tự động xóa)
            $rows = Yii::$app->db->createCommand()->delete('hoadon', 'ID = :id', [':id' => $id])->execute();
            
            if ($rows > 0) {
                 Yii::$app->session->setFlash('success', 'Xóa Hóa Đơn thành công.');
            } else {
                 Yii::$app->session->setFlash('warning', 'Không tìm thấy Hóa Đơn để xóa.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Lỗi xóa dữ liệu: ' . $e->getMessage());
            Yii::error($e->getMessage());
        }
        return $this->redirect(['index']);
    }
}