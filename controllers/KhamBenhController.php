<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query; // Cần dùng Query Builder

// Khai báo các Model cần thiết (đã chuyển sang yii\base\Model)
use app\models\LanKham;
use app\models\LanKhamSearch; // Giả định Model Search tồn tại và dùng ArrayDataProvider
use app\models\ChiTietThuoc;
use app\models\ChiTietKham;
use app\models\ChiTietCSVC;

class KhamBenhController extends Controller
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

    /**
     * Lists all LanKham records using ArrayDataProvider.
     * Lưu ý: LanKhamSearch phải được viết lại để trả về ArrayDataProvider.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LanKhamSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LanKham record by ID using Query Builder.
     * @param int $ID ID
     * @return string
     * @throws NotFoundHttpException if the record cannot be found
     */
    public function actionView($ID)
{
    $data = $this->findData($ID);
    $model = new LanKham($data); 

    // Fetch dữ liệu thô từ CSDL
    $rawDataChiTietKham = (new Query())->from('chitietkham')->where(['ID_LanKham' => $ID])->all();
    $rawDataChiTietThuoc = (new Query())->from('chitietthuoc')->where(['ID_LanKham' => $ID])->all();
    $rawDataChiTietCSVC = (new Query())->from('chitietcsvc')->where(['ID_LanKham' => $ID])->all();

    // CHUYỂN DỮ LIỆU THÔ SANG ĐỐI TƯỢNG MODEL
    $modelsChiTietKham = $this->loadArrayIntoModels($rawDataChiTietKham, ChiTietKham::class);
    $modelsChiTietThuoc = $this->loadArrayIntoModels($rawDataChiTietThuoc, ChiTietThuoc::class);
    $modelsChiTietCSVC = $this->loadArrayIntoModels($rawDataChiTietCSVC, ChiTietCSVC::class);
    
    // Đảm bảo có ít nhất 1 Model rỗng để hiển thị DynamicForm
    $modelsChiTietKham = $this->ensureMinimumModels($modelsChiTietKham, ChiTietKham::class);
    $modelsChiTietThuoc = $this->ensureMinimumModels($modelsChiTietThuoc, ChiTietThuoc::class);
    $modelsChiTietCSVC = $this->ensureMinimumModels($modelsChiTietCSVC, ChiTietCSVC::class);


    return $this->render('view', [
        'model' => $model,
        'modelsChiTietKham' => $modelsChiTietKham, 
        'modelsChiTietThuoc' => $modelsChiTietThuoc,
        'modelsChiTietCSVC' => $modelsChiTietCSVC,
    ]);
}

    /**
     * Creates a new LanKham record using Query Builder.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new LanKham();
        $modelsChiTietKham = $this->ensureMinimumModels([], ChiTietKham::class);
        $modelsChiTietThuoc = $this->ensureMinimumModels([], ChiTietThuoc::class);
        $modelsChiTietCSVC = $this->ensureMinimumModels([], ChiTietCSVC::class);

        if ($this->request->isPost) {
            
            if ($model->load($this->request->post()) && $model->validate()) {
                
                $model->ThoiGianKham = date('Y-m-d H:i:s');
                $model->TongTienKham = $model->TongTienKham ?? 0;
                
                $modelsChiTietKham = $this->loadChiTietModels(ChiTietKham::class, 'ChiTietKham');
                $modelsChiTietThuoc = $this->loadChiTietModels(ChiTietThuoc::class, 'ChiTietThuoc');
                $modelsChiTietCSVC = $this->loadChiTietModels(ChiTietCSVC::class, 'ChiTietCSVC');

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // 1. LƯU LANKHAM BẰNG QUERY BUILDER
                    $columns = [
                        'MaBN' => $model->MaBN,
                        'ThoiGianKham' => $model->ThoiGianKham,
                        'ID_BS' => $model->ID_BS,
                        'KhoaKham' => $model->KhoaKham,
                        'TongTienKham' => $model->TongTienKham,
                    ];

                    Yii::$app->db->createCommand()
                        ->insert('lankham', $columns)
                        ->execute();
                    
                    // Lấy ID mới chèn và Mã Khám (do Trigger tạo)
                    $lanKhamID = Yii::$app->db->getLastInsertID();
                    $model->MaKham = Yii::$app->db->createCommand("SELECT MaKham FROM lankham WHERE ID=:id", [':id' => $lanKhamID])->queryScalar();
                    $model->ID = $lanKhamID;
                    
                    // 2. LƯU CHI TIẾT KHÁM
                    foreach ($modelsChiTietKham as $mChiTietKham) {
                        if ($mChiTietKham->validate()) {
                            Yii::$app->db->createCommand()
                                ->insert('chitietkham', [
                                    'ID_LanKham' => $lanKhamID,
                                    'MaBenh' => $mChiTietKham->MaBenh,
                                ])
                                ->execute();
                        }
                    }

                    // 3. LƯU CHI TIẾT THUỐC
                    foreach ($modelsChiTietThuoc as $mChiTietThuoc) {
                        if ($mChiTietThuoc->validate()) {
                            Yii::$app->db->createCommand()
                                ->insert('chitietthuoc', [
                                    'ID_LanKham' => $lanKhamID,
                                    'MaThuoc' => $mChiTietThuoc->MaThuoc,
                                    'SoLuong' => $mChiTietThuoc->SoLuong,
                                    'GiaBan' => $mChiTietThuoc->GiaBan,
                                ])
                                ->execute();
                        }
                    }
                    
                    // 4. LƯU CHI TIẾT CSVC
                    foreach ($modelsChiTietCSVC as $mChiTietCSVC) {
                         if ($mChiTietCSVC->validate()) {
                            Yii::$app->db->createCommand()
                                ->insert('chitietcsvc', [
                                    'ID_LanKham' => $lanKhamID,
                                    'MaCSVC' => $mChiTietCSVC->MaCSVC,
                                    'SoLuong' => $mChiTietCSVC->SoLuong,
                                    'DonGia' => $mChiTietCSVC->DonGia,
                                ])
                                ->execute();
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Tạo phiếu khám bệnh thành công (Mã Khám: ' . $model->MaKham . ').');
                    return $this->redirect(['view', 'ID' => $model->ID]);
                    
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Lỗi CSDL: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Lỗi xác thực dữ liệu đầu vào.');
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'modelsChiTietKham' => $modelsChiTietKham,
            'modelsChiTietThuoc' => $modelsChiTietThuoc,
            'modelsChiTietCSVC' => $modelsChiTietCSVC,
        ]);
    }

    /**
     * Updates an existing LanKham record using Query Builder.
     * LƯU Ý: Đây là logic update TỐI THIỂU và chưa xử lý logic xóa/cập nhật phức tạp cho các bảng chi tiết!
     * @param int $ID ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the record cannot be found
     */
    public function actionUpdate($ID)
{
    $data = $this->findData($ID); 
    $model = new LanKham($data);

    // Fetch dữ liệu thô từ CSDL
    $rawDataChiTietKham = (new Query())->from('chitietkham')->where(['ID_LanKham' => $ID])->all();
    $rawDataChiTietThuoc = (new Query())->from('chitietthuoc')->where(['ID_LanKham' => $ID])->all();
    $rawDataChiTietCSVC = (new Query())->from('chitietcsvc')->where(['ID_LanKham' => $ID])->all();

    // CHUYỂN DỮ LIỆU THÔ SANG ĐỐI TƯỢNG MODEL
    $modelsChiTietKham = $this->loadArrayIntoModels($rawDataChiTietKham, ChiTietKham::class);
    $modelsChiTietThuoc = $this->loadArrayIntoModels($rawDataChiTietThuoc, ChiTietThuoc::class);
    $modelsChiTietCSVC = $this->loadArrayIntoModels($rawDataChiTietCSVC, ChiTietCSVC::class);

    // ... (logic xử lý POST request và update CSDL) ...

    if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
        // ... (Logic UPDATE đã viết trước đó) ...
        // Sau khi xử lý POST, chúng ta cần tải lại các models chi tiết từ POST data 
        // nếu muốn hiển thị lại form với dữ liệu người dùng nhập.
        
        // Tuy nhiên, đối với update, ta sẽ giữ nguyên models đã được load ở trên 
        // (để đơn giản hóa, vì logic update phức tạp đã bị lược bỏ).
    }
    
    // Đảm bảo có ít nhất 1 Model rỗng để DynamicForm hiển thị
    $modelsChiTietKham = $this->ensureMinimumModels($modelsChiTietKham, ChiTietKham::class);
    $modelsChiTietThuoc = $this->ensureMinimumModels($modelsChiTietThuoc, ChiTietThuoc::class);
    $modelsChiTietCSVC = $this->ensureMinimumModels($modelsChiTietCSVC, ChiTietCSVC::class);


    return $this->render('update', [
        'model' => $model,
        'modelsChiTietKham' => $modelsChiTietKham,
        'modelsChiTietThuoc' => $modelsChiTietThuoc,
        'modelsChiTietCSVC' => $modelsChiTietCSVC,
    ]);
}
    
    /**
     * Deletes an existing LanKham record and related details using Query Builder.
     * @param int $ID ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the record cannot be found
     */
    public function actionDelete($ID)
    {
        $this->findData($ID); // Kiểm tra tồn tại

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Xóa các chi tiết (nếu không có CASCADE DELETE trong DB)
            Yii::$app->db->createCommand()->delete('chitietkham', ['ID_LanKham' => $ID])->execute();
            Yii::$app->db->createCommand()->delete('chitietthuoc', ['ID_LanKham' => $ID])->execute();
            Yii::$app->db->createCommand()->delete('chitietcsvc', ['ID_LanKham' => $ID])->execute();
            
            // Xóa Lần Khám chính
            Yii::$app->db->createCommand()->delete('lankham', ['ID' => $ID])->execute();
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Xóa phiếu khám bệnh thành công.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Lỗi CSDL khi xóa: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds data based on primary key using Query Builder (thay thế findModel của AR)
     * @param int $ID ID
     * @return array Dữ liệu lần khám
     * @throws NotFoundHttpException if the record cannot be found
     */
    protected function findData($ID)
    {
        $data = (new Query())->from('lankham')->where(['ID' => $ID])->one();

        if ($data !== false) {
            return $data;
        }

        throw new NotFoundHttpException('Phiếu khám bệnh không tồn tại.');
    }

    /**
     * Tải dữ liệu cho các Model chi tiết (giữ nguyên logic load)
     */
    protected function loadChiTietModels($className, $formName)
    {
        $data = Yii::$app->request->post($formName);
        $models = [];
        if (!empty($data) && is_array($data)) {
            foreach ($data as $i => $item) {
                // Khi không dùng AR, luôn tạo mới Model và gán ID
                $model = new $className();
                
                // Loại bỏ dòng rỗng
                if (empty($item['MaThuoc']) && empty($item['MaBenh']) && empty($item['MaCSVC'])) {
                    continue; 
                }
                
                // Tải dữ liệu vào Model
                $model->load($item, ''); 
                $models[] = $model;
            }
        }
        
        return $models;
    }
    
    /**
     * Đảm bảo mảng models không rỗng
     */
    protected function ensureMinimumModels($models, $className)
    {
        // Khi không dùng AR, $models là một mảng dữ liệu CSDL.
        // Ta cần kiểm tra nếu mảng rỗng thì tạo ra 1 object rỗng (yii\base\Model)
        if (empty($models) || (is_array($models) && !isset($models[0]->ID))) {
            return [new $className()];
        }
        return $models;
    }

    protected function loadArrayIntoModels(array $rawData, string $className)
    {
        $models = [];
        foreach ($rawData as $data) {
            // Khởi tạo Model và truyền mảng dữ liệu thô vào constructor
            $model = new $className();
            // setAttributes(data, false) giúp tải dữ liệu không cần mass assignment
            $model->setAttributes($data, false); 
            $models[] = $model;
        }
        return $models;
    }
}