<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper; // Cần ArrayHelper để tạo map thưởng

class BaoCaoController extends Controller
{
    /**
     * Cấu hình filters nếu cần
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [],
                ],
            ]
        );
    }

    // --- 1. THỐNG KÊ LẦN KHÁM THEO THÁNG ---
    
    /**
     * Báo cáo 1: Thống kê số lượng Lần Khám theo tháng
     */
    public function actionThongKeLanKham()
    {
        $query = (new Query())
            ->select([
                'Nam' => 'YEAR(ThoiGianKham)',
                'Thang' => 'MONTH(ThoiGianKham)',
                'SoLuong' => 'COUNT(*)'
            ])
            ->from('lankham')
            ->groupBy(['Nam', 'Thang'])
            ->orderBy(['Nam' => SORT_ASC, 'Thang' => SORT_ASC]);
            
        $data = $query->all(Yii::$app->db);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'attributes' => ['Nam', 'Thang', 'SoLuong'],
            ],
        ]);

        return $this->render('thongkelankham', [
            'dataProvider' => $dataProvider,
            'title' => 'Thống Kê Số Lần Khám Theo Tháng'
        ]);
    }
    
    // --- 2. DOANH THU THEO THÁNG (Dựa trên SQL mẫu từ báo cáo) ---
    
    /**
     * Báo cáo 2: Tổng Doanh Thu theo tháng (Yêu cầu c)
     */
    public function actionDoanhThuThang()
    {
        $sql = "
            SELECT 
                YEAR(lk.ThoiGianKham) AS nam,
                MONTH(lk.ThoiGianKham) AS thang,
                IFNULL(SUM(lk.TongTienKham), 0) AS doanh_thu_kham,
                IFNULL(SUM(hd.TongTien), 0) AS doanh_thu_hoadon,
                IFNULL(SUM(ctt.SoLuong * ctt.GiaBan), 0) AS doanh_thu_thuoc,
                IFNULL(SUM(ctcs.SoLuong * ctcs.GiaBan), 0) AS doanh_thu_csvc,
                (
                    IFNULL(SUM(lk.TongTienKham), 0) + 
                    IFNULL(SUM(hd.TongTien), 0) + 
                    IFNULL(SUM(ctt.SoLuong * ctt.GiaBan), 0) + 
                    IFNULL(SUM(ctcs.SoLuong * ctcs.GiaBan), 0) 
                ) AS tong_doanh_thu
            FROM lankham lk 
            LEFT JOIN hoadon hd ON lk.ID = hd.ID_LanKham
            LEFT JOIN chitietthuoc ctt ON ctt.ID_LanKham = lk.ID 
            LEFT JOIN chitietcsvc ctcs ON ctcs.ID_LanKham = lk.ID
            GROUP BY YEAR(lk.ThoiGianKham), MONTH(lk.ThoiGianKham)
            ORDER BY nam, thang
        ";
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

        return $this->render('doanhthuthang', [
            'dataProvider' => $dataProvider,
            'title' => 'Tổng Doanh Thu Chi Tiết Theo Tháng'
        ]);
    }
    
    // --- 3. BỆNH PHỔ BIẾN (Yêu cầu d, Đếm theo số bệnh nhân không trùng) ---
    
    /**
     * Báo cáo 3: Thống kê bệnh phổ biến (Theo số lượng bệnh nhân mắc, không trùng)
     */
    public function actionBenhPhoBien($thang = 6, $nam = 2025)
    {
        $sql = "
            SELECT 
                b.TenBenh,
                COUNT(DISTINCT lk.MaBN) AS so_benhnhan
            FROM dotdieutri dt
            JOIN lankham lk ON dt.ID_LanKham = lk.ID
            JOIN Benh b ON dt.MaBenh = b.MaBenh
            WHERE YEAR(lk.ThoiGianKham) = :nam
            AND MONTH(lk.ThoiGianKham) = :thang
            GROUP BY b.TenBenh
            ORDER BY so_benhnhan DESC
        ";
        
        $data = Yii::$app->db->createCommand($sql, [':nam' => $nam, ':thang' => $thang])->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

        return $this->render('benhphobien', [
            'dataProvider' => $dataProvider,
            'thang' => $thang,
            'nam' => $nam,
            'title' => "Thống Kê Bệnh Phổ Biến Trong Tháng $thang/$nam"
        ]);
    }
    
    // --- 4. BẢNG LƯƠNG CHI TIẾT (Yêu cầu a) ---
    
    /**
     * Báo cáo 4: Bảng lương nhân viên chi tiết theo tháng/năm
     */
    public function actionBangLuongChiTiet($thang = 6, $nam = 2025)
    {
        $query = (new Query())
            ->select(['t2.TenNV', 't2.VaiTro', 't1.*'])
            ->from(['t1' => 'bangluong'])
            ->leftJoin(['t2' => 'NhanVien'], 't1.ID_NHANVIEN = t2.ID_NHANVIEN')
            ->where(['t1.Thang' => $thang, 't1.Nam' => $nam])
            ->orderBy(['t2.VaiTro' => SORT_ASC, 't1.TongLuong' => SORT_DESC]);
            
        $data = $query->all(Yii::$app->db);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

        return $this->render('bangluongchitiet', [
            'dataProvider' => $dataProvider,
            'thang' => $thang,
            'nam' => $nam,
            'title' => "Bảng Lương Tháng $thang/$nam"
        ]);
    }

    // --- 5. TÌNH TRẠNG ĐIỀU TRỊ CỦA CÁC ĐỢT ---
    
    /**
     * Báo cáo 5: Tình trạng điều trị của các đợt (Theo trạng thái)
     */
    public function actionTinhTrangDieuTri()
    {
        $sql = "
            SELECT 
                t1.TrangThai,
                COUNT(t1.ID) AS SoLuongDot
            FROM dotdieutri t1
            GROUP BY t1.TrangThai
            ORDER BY SoLuongDot DESC
        ";
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

        return $this->render('tinhtrangdieutri', [
            'dataProvider' => $dataProvider,
            'title' => 'Thống Kê Tình Trạng Điều Trị'
        ]);
    }

    /**
     * Chức năng Tổng Hợp Lương: Tính toán và ghi lương vào bảng bangluong
     * @param int|null $thang
     * @param int|null $nam
     */
   public function actionTinhTongHopLuong($thang = null, $nam = null)
    {
        // 1. Thiết lập tham số
        if ($thang === null) $thang = date('m');
        if ($nam === null) $nam = date('Y');

        $MUC_LUONG_CO_SO = 1800000; // Theo báo cáo 
        $THUONG_BS = 1000000; // 1,000,000/chữa khỏi [cite: 125]
        $THUONG_YT = 200000; // 200,000/lần hỗ trợ [cite: 126]

        $transaction = Yii::$app->db->beginTransaction();
        $ngayTinh = date('Y-m-d'); // Dùng ngày hiện tại làm NgayChiTra (DATE)
        $soLuongNVDuocTinh = 0;

        try {
            // Xóa dữ liệu lương cũ (ngăn lỗi trùng UNIQUE KEY khi chạy lại)
            Yii::$app->db->createCommand()
                ->delete('bangluong', ['Thang' => $thang, 'Nam' => $nam])
                ->execute();

            // --- A. TÍNH THƯỞNG BÁC SĨ (1,000,000 / đợt chữa khỏi) ---
            // TrangThai = 'Da Khoi' và tính theo NgayKetThuc của đợt điều trị [cite: 173, 164]
            $bacSiThuongSQL = "
                SELECT 
                    ID_BS AS ID_NHANVIEN,
                    COUNT(ID) * :thuong AS Thuong
                FROM dotdieutri
                WHERE 
                    TrangThai = 'Da Khoi' AND
                    YEAR(NgayKetThuc) = :nam AND MONTH(NgayKetThuc) = :thang
                GROUP BY ID_BS
            ";
            $bacSiThuongData = Yii::$app->db->createCommand($bacSiThuongSQL, [
                ':thuong' => $THUONG_BS,
                ':thang' => $thang, 
                ':nam' => $nam
            ])->queryAll();
            $bacSiThuongMap = ArrayHelper::index($bacSiThuongData, 'ID_NHANVIEN');


            // --- B. TÍNH THƯỞNG Y TÁ (200,000 / lần hỗ trợ) ---
            // Tính theo số lần hỗ trợ trong bảng lanchua_yta [cite: 173, 167]
            // Giả định bảng lanchua_yta không có NgayHoTro, nên phải JOIN qua lanchua để lấy thời gian
            $yTaThuongSQL = "
                SELECT
                    lyt.ID_YT AS ID_NHANVIEN,
                    COUNT(lyt.ID_LANCHUA) * :thuong AS Thuong
                FROM lanchua_yta lyt
                JOIN lanchua lc ON lyt.ID_LANCHUA = lc.ID
                WHERE 
                    YEAR(lc.ThoiGianChua) = :nam AND MONTH(lc.ThoiGianChua) = :thang
                GROUP BY lyt.ID_YT
            ";
            $yTaThuongData = Yii::$app->db->createCommand($yTaThuongSQL, [
                ':thuong' => $THUONG_YT,
                ':thang' => $thang, 
                ':nam' => $nam
            ])->queryAll();
            $yTaThuongMap = ArrayHelper::index($yTaThuongData, 'ID_NHANVIEN');


            // --- C. TỔNG HỢP VÀ GHI DỮ LIỆU ---
            $nhanViens = (new Query())
                ->select(['ID_NHANVIEN', 'HeSoLuong', 'VaiTro']) 
                ->from('NhanVien')
                ->all();
            
            foreach ($nhanViens as $nv) {
                $idNv = $nv['ID_NHANVIEN'];
                $heSoLuong = (float)$nv['HeSoLuong'];
                
                // 1. Lương Cơ Bản
                $luongCoBan = $heSoLuong * $MUC_LUONG_CO_SO; 

                // 2. Tổng Thưởng
                $tongThuong = 0;
                if ($nv['VaiTro'] === 'BS') {
                    $tongThuong = (float)($bacSiThuongMap[$idNv]['Thuong'] ?? 0);
                } elseif ($nv['VaiTro'] === 'YT') {
                    $tongThuong = (float)($yTaThuongMap[$idNv]['Thuong'] ?? 0);
                }

                // 3. Tổng Lương (Theo công thức mẫu trong báo cáo: Lương = LươngCB + Thưởng)
                $tongLuong = $luongCoBan + $tongThuong;
                
                // 4. Ghi vào bảng bangluong (Phụ cấp và Khấu hao mặc định là 0 vì không có công thức tính)
                Yii::$app->db->createCommand()->insert('bangluong', [
                    'ID_NHANVIEN' => $idNv,
                    'Thang' => $thang,
                    'Nam' => $nam,
                    'LuongCoBan' => $luongCoBan,
                    'PhuCap' => 0, 
                    'Thuong' => $tongThuong,
                    'KhauHao' => 0, 
                    'TongLuong' => $tongLuong,
                    'NgayChiTra' => $ngayTinh,
                ])->execute();
                
                $soLuongNVDuocTinh++;
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', "Tổng hợp lương tháng $thang/$nam thành công cho $soLuongNVDuocTinh nhân viên (Lương cơ sở: ".number_format($MUC_LUONG_CO_SO)." VND).");
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', "Lỗi trong quá trình tính lương: " . $e->getMessage());
        }

        return $this->redirect(['bang-luong-chi-tiet', 'thang' => $thang, 'nam' => $nam]);
    }
}