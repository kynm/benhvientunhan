<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use app\models\Benh; // Cần phải có các Base Model này
use app\models\Thuoc;
use app\models\CSVC;

class DanhMucController extends Controller
{
    // Ánh xạ type -> table name và Model class
    const MAP = [
        'benh' => ['table' => 'Benh', 'model' => 'app\models\Benh', 'title' => 'Bệnh'],
        'thuoc' => ['table' => 'Thuoc', 'model' => 'app\models\Thuoc', 'title' => 'Thuốc'],
        'csvc' => ['table' => 'CSVC', 'model' => 'app\models\CSVC', 'title' => 'Cơ Sở Vật Chất'],
    ];

    /**
     * Lấy cấu hình danh mục dựa trên loại.
     * @param string $type
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getConfig($type)
    {
        if (!isset(self::MAP[$type])) {
            throw new NotFoundHttpException('Danh mục không hợp lệ.');
        }
        return self::MAP[$type];
    }
    
    /**
     * DAO: Tìm dữ liệu theo ID.
     * @param string $type
     * @param int $id
     * @return array|false
     * @throws NotFoundHttpException
     */
    protected function findDataById($type, $id)
    {
        $config = $this->getConfig($type);
        // DAO: Truy vấn trực tiếp vào CSDL
        $data = Yii::$app->db->createCommand("SELECT * FROM {$config['table']} WHERE ID = :id", [':id' => $id])->queryOne();

        if ($data !== false) {
            return $data;
        }
        throw new NotFoundHttpException($config['title'] . ' không tồn tại.');
    }

    public function actionIndex($type)
    {
        $config = $this->getConfig($type);
        
        // DAO: Lấy tất cả dữ liệu
        $data = Yii::$app->db->createCommand("SELECT * FROM {$config['table']}")->queryAll();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => ['pageSize' => 10],
            'key' => 'ID', 
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'type' => $type,
            'title' => $config['title'],
        ]);
    }
    
    public function actionCreate($type)
    {
        $config = $this->getConfig($type);
        $modelClass = $config['model'];
        $model = new $modelClass();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            // Lấy các thuộc tính từ Base Model, loại bỏ ID để chuẩn bị cho INSERT
            $attributes = array_filter($model->getAttributes(), function($value, $key) {
                return $key !== 'ID'; 
            }, ARRAY_FILTER_USE_BOTH);

            try {
                // DAO: INSERT
                Yii::$app->db->createCommand()->insert($config['table'], $attributes)->execute();
                
                Yii::$app->session->setFlash('success', 'Thêm ' . $config['title'] . ' thành công.');
                return $this->redirect(['index', 'type' => $type]);
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Lỗi: ' . $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $model, 'type' => $type, 'title' => $config['title']]);
    }

    public function actionUpdate($type, $id)
    {
        $config = $this->getConfig($type);
        $modelClass = $config['model'];
        
        $data = $this->findDataById($type, $id);
        $model = new $modelClass();
        $model->setAttributes($data, false); // Gán dữ liệu cũ vào Base Model
        $model->ID = $id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            // Lấy các thuộc tính từ Base Model, loại bỏ ID để chuẩn bị cho UPDATE
            $attributes = array_filter($model->getAttributes(), function($value, $key) {
                return $key !== 'ID'; 
            }, ARRAY_FILTER_USE_BOTH);

            try {
                // DAO: UPDATE
                Yii::$app->db->createCommand()->update($config['table'], 
                    $attributes, 
                    'ID = :id', 
                    [':id' => $id] // Tham số an toàn
                )->execute();
                
                Yii::$app->session->setFlash('success', 'Cập nhật ' . $config['title'] . ' thành công.');
                return $this->redirect(['index', 'type' => $type]);
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Lỗi: ' . $e->getMessage());
            }
        }
        return $this->render('update', ['model' => $model, 'type' => $type, 'title' => $config['title']]);
    }

    public function actionDelete($type, $id)
    {
        $config = $this->getConfig($type);
        try {
            // DAO: DELETE
            Yii::$app->db->createCommand()->delete($config['table'], 'ID = :id', [':id' => $id])->execute();
            Yii::$app->session->setFlash('success', 'Xóa ' . $config['title'] . ' thành công.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Lỗi xóa dữ liệu: ' . $e->getMessage());
        }
        return $this->redirect(['index', 'type' => $type]);
    }
}