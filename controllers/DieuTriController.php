<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query; 

// Khai báo các Model cần thiết (yii\base\Model)
use app\models\DotDieuTri;
use app\models\DotDieuTriSearch; 

class DieuTriController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    // --- CRUD ACTIONS ---

    public function actionIndex()
    {
        $searchModel = new DotDieuTriSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($ID)
    {
        $data = $this->findDataBySql($ID);
        $model = new DotDieuTri($data); 

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate($id_lan_kham = null)
    {
        $model = new DotDieuTri();
        
        if ($id_lan_kham !== null) {
            $model->ID_LanKham = $id_lan_kham;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Xử lý định dạng ngày tháng từ form (yyyy-mm-dd) sang format CSDL (DATE: Y-m-d)
                    $ngayBatDauFormatted = Yii::$app->formatter->asDate($model->NgayBatDau, 'php:Y-m-d');
                    // NgayKetThuc có thể NULL
                    $ngayKetThucFormatted = empty($model->NgayKetThuc) ? null : Yii::$app->formatter->asDate($model->NgayKetThuc, 'php:Y-m-d');

                    // INSERT DỮ LIỆU BẰNG RAW SQL
                    $sql = "INSERT INTO dotdieutri (ID_LanKham, MaBenh, ID_BS, NgayBatDau, NgayKetThuc, TrangThai) 
                            VALUES (:id_lk, :ma_benh, :id_bs, :ngay_bd, :ngay_kt, :trang_thai)";
                    
                    Yii::$app->db->createCommand($sql, [
                        ':id_lk' => $model->ID_LanKham,
                        ':ma_benh' => $model->MaBenh,
                        ':id_bs' => $model->ID_BS,
                        ':ngay_bd' => $ngayBatDauFormatted, 
                        ':ngay_kt' => $ngayKetThucFormatted, 
                        ':trang_thai' => $model->TrangThai,
                    ])->execute();
                    
                    $model->ID = Yii::$app->db->getLastInsertID();
                    $transaction->commit();
                    
                    Yii::$app->session->setFlash('success', 'Tạo đợt điều trị thành công.');
                    return $this->redirect(['view', 'ID' => $model->ID]);
                    
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Lỗi CSDL: ' . $e->getMessage());
                }
            }
        } else {
            $model->NgayBatDau = date('Y-m-d'); 
            $model->TrangThai = 'Mới'; 
        }

        // Lấy dữ liệu cho dropdown
        $lanKhams = $this->getLanKhamList();
        $benhs = $this->getBenhList(); 
        $bacSis = $this->getBacSiList();

        return $this->render('create', [
            'model' => $model,
            'lanKhams' => $lanKhams,
            'benhs' => $benhs, // ✅ Đã sửa: Truyền biến $benhs
            'bacSis' => $bacSis,
        ]);
    }

    public function actionUpdate($ID)
    {
        $data = $this->findDataBySql($ID);
        $model = new DotDieuTri($data);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Xử lý định dạng ngày tháng
                $ngayBatDauFormatted = Yii::$app->formatter->asDate($model->NgayBatDau, 'php:Y-m-d');
                $ngayKetThucFormatted = empty($model->NgayKetThuc) ? null : Yii::$app->formatter->asDate($model->NgayKetThuc, 'php:Y-m-d');

                // UPDATE DỮ LIỆU BẰNG RAW SQL
                $sql = "UPDATE dotdieutri SET MaBenh = :ma_benh, ID_BS = :id_bs, NgayBatDau = :ngay_bd, 
                        NgayKetThuc = :ngay_kt, TrangThai = :trang_thai
                        WHERE ID = :id";
                
                Yii::$app->db->createCommand($sql, [
                    ':ma_benh' => $model->MaBenh,
                    ':id_bs' => $model->ID_BS,
                    ':ngay_bd' => $ngayBatDauFormatted,
                    ':ngay_kt' => $ngayKetThucFormatted,
                    ':trang_thai' => $model->TrangThai,
                    ':id' => $ID,
                ])->execute();
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Cập nhật đợt điều trị thành công.');
                return $this->redirect(['view', 'ID' => $ID]);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Lỗi CSDL: ' . $e->getMessage());
            }
        }
        
        // Lấy dữ liệu cho dropdown
        $lanKhams = $this->getLanKhamList();
        $benhs = $this->getBenhList();
        $bacSis = $this->getBacSiList();

        return $this->render('update', [
            'model' => $model,
            'lanKhams' => $lanKhams,
            'benhs' => $benhs, // ✅ Đã sửa: Truyền biến $benhs
            'bacSis' => $bacSis,
        ]);
    }
    
    public function actionDelete($ID)
    {
        $this->findDataBySql($ID); 
        
        $sql = "DELETE FROM dotdieutri WHERE ID = :id";
        Yii::$app->db->createCommand($sql, [':id' => $ID])->execute();
        
        Yii::$app->session->setFlash('success', 'Xóa đợt điều trị thành công.');

        return $this->redirect(['index']);
    }

    // --- HELPER FUNCTIONS ---

    protected function findDataBySql($ID)
    {
        $sql = "SELECT * FROM dotdieutri WHERE ID = :id";
        $data = Yii::$app->db->createCommand($sql, [':id' => $ID])->queryOne();

        if ($data !== false) {
            return $data;
        }

        throw new NotFoundHttpException('Đợt điều trị không tồn tại.');
    }
    
    protected function getLanKhamList()
    {
        $data = (new Query())
            ->select(['ID', 'MaKham'])
            ->from('lankham')
            ->all();
            
        return ArrayHelper::map($data, 'ID', 'MaKham');
    }

    protected function getBenhList()
    {
        $data = (new Query())
            ->select(['MaBenh', 'TenBenh'])
            ->from('Benh') 
            ->all();
            
        return ArrayHelper::map($data, 'MaBenh', 'TenBenh');
    }
    
    /**
     * Lấy danh sách Bác sĩ cho Dropdown (Dùng cột TenNV)
     * @return array
     */
    protected function getBacSiList()
    {
        $data = (new Query())
            ->select(['ID_NHANVIEN', 'TenNV']) // SỬA: Dùng TenNV
            ->from('NhanVien') 
            ->all();
            
        return ArrayHelper::map($data, 'ID_NHANVIEN', 'TenNV'); 
    }
}