<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use yii\web\View;
use app\models\ChiTietKham; // <-- Đã đổi từ ChiTietBenh

/** @var View $this */
/** @var app\models\LanKham $model */
/** @var app\models\ChiTietThuoc[] $modelsChiTietThuoc */
/** @var ChiTietKham[] $modelsChiTietKham <-- Đã đổi tên biến và type hint */

$isNewRecord = empty($model->ID);

// DAO: Truy vấn danh sách tham chiếu
$benhNhans = ArrayHelper::map(Yii::$app->db->createCommand('SELECT MaBN, TenBN FROM benhnhan')->queryAll(), 'MaBN', function($model) { return $model['TenBN'] . ' (' . $model['MaBN'] . ')'; });
$bacSis = ArrayHelper::map(Yii::$app->db->createCommand('SELECT ID_NHANVIEN, TenNV, MaNV FROM nhanvien WHERE VaiTro="BS"')->queryAll(), 'ID_NHANVIEN', function($model) { return $model['TenNV'] . ' (' . $model['MaNV'] . ')'; });
$khoaKhams = ['Noi' => 'Khoa Nội', 'Ngoai' => 'Khoa Ngoại', 'Rang' => 'Khoa Răng Hàm Mặt', 'DaLieu' => 'Khoa Da Liễu'];
$thuocs = ArrayHelper::map(Yii::$app->db->createCommand('SELECT MaThuoc, TenThuoc FROM thuoc')->queryAll(), 'MaThuoc', 'TenThuoc');
$benhs = ArrayHelper::map(Yii::$app->db->createCommand('SELECT MaBenh, TenBenh FROM benh')->queryAll(), 'MaBenh', 'TenBenh');
$thuocPrices = ArrayHelper::map(Yii::$app->db->createCommand('SELECT MaThuoc, GiaTien FROM thuoc')->queryAll(), 'MaThuoc', 'GiaTien');

// Khởi tạo một Model rỗng để làm khuôn mẫu (template)
$modelChiTietThuocTemplate = new \app\models\ChiTietThuoc();
$modelChiTietKhamTemplate = new \app\models\ChiTietKham(); // <-- Đã đổi tên Model

?>

<div class="lan-kham-form">

    <?php $form = ActiveForm::begin(['id' => 'form-kham-benh']); ?>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Thông Tin Khám Bệnh</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><?= $form->field($model, 'MaKham')->textInput(['maxlength' => true, 'readonly' => true]) ?></div>
                
                <div class="col-md-3">
                    <?= $form->field($model, 'MaBN')->dropDownList($benhNhans, ['prompt' => 'Chọn Bệnh Nhân']) ?>
                </div>
                
                <div class="col-md-3">
                    <?= $form->field($model, 'ID_BS')->dropDownList($bacSis, ['prompt' => 'Chọn Bác Sĩ']) ?>
                </div>
                
                <div class="col-md-3">
                    <?= $form->field($model, 'KhoaKham')->dropDownList($khoaKhams, ['prompt' => 'Chọn Khoa Khám']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'LyDoKham')->textarea(['rows' => 2]) ?></div>
                
                <div class="col-md-6"><?= $form->field($model, 'ChanDoan')->textarea(['rows' => 2]) ?></div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">Chuẩn Đoán Chính/Phụ (ICD)</div>
        <div class="card-body">
            <table class="table table-bordered" id="table-chi-tiet-kham"> <thead><tr><th style="width: 80%;">Tên Bệnh/Mã ICD</th><th style="width: 10%;"></th></tr></thead>
                <tbody class="container-items-kham"> <?php foreach ($modelsChiTietKham as $i => $modelChiTietKham): // <-- Đổi tên biến ?>
                    <tr class="item-kham"> <td>
                            <?= $form->field($modelChiTietKham, "[{$i}]ID")->hiddenInput()->label(false) ?>
                            <?= $form->field($modelChiTietKham, "[{$i}]MaBenh")->dropDownList($benhs, [
                                'prompt' => 'Chọn Bệnh (ICD)'
                            ])->label(false) ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="remove-item btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-minus"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <button type="button" class="add-item btn btn-success btn-sm" onclick="addRow('kham')"><i class="fas fa-plus"></i> Thêm Chuẩn Đoán</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-info text-white">Kê Đơn Thuốc</div>
        <div class="card-body">
            <table class="table table-bordered" id="table-chi-tiet-thuoc">
                <thead><tr><th style="width: 40%;">Thuốc</th><th style="width: 20%;">Số Lượng</th><th style="width: 20%;">Giá Bán/Đơn Vị</th><th style="width: 10%;"></th></tr></thead>
                <tbody class="container-items-thuoc">
                <?php foreach ($modelsChiTietThuoc as $i => $modelChiTietThuoc): ?>
                    <tr class="item-thuoc">
                        <td>
                            <?= $form->field($modelChiTietThuoc, "[{$i}]ID")->hiddenInput()->label(false) ?>
                            <?= $form->field($modelChiTietThuoc, "[{$i}]MaThuoc")->dropDownList($thuocs, [
                                'prompt' => 'Chọn Thuốc',
                                'class' => 'form-control ma-thuoc-select'
                            ])->label(false) ?>
                        </td>
                        <td><?= $form->field($modelChiTietThuoc, "[{$i}]SoLuong")->textInput(['type' => 'number', 'min' => 1])->label(false) ?></td>
                        <td><?= $form->field($modelChiTietThuoc, "[{$i}]GiaBan")->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01])->label(false) ?></td>
                        <td class="text-center">
                            <button type="button" class="remove-item btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-minus"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <button type="button" class="add-item btn btn-success btn-sm" onclick="addRow('thuoc')"><i class="fas fa-plus"></i> Thêm Thuốc</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton($isNewRecord ? 'Lưu Phiếu Khám Bệnh' : 'Cập Nhật Phiếu Khám Bệnh', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$thuocPricesJson = json_encode($thuocPrices);

// --- 1. KHUÔN MẪU CHO CHI TIẾT KHÁM (CHẨN ĐOÁN) ---
$khamFields = $form->field($modelChiTietKhamTemplate, '[{i}]ID')->hiddenInput()->label(false) . 
              $form->field($modelChiTietKhamTemplate, '[{i}]MaBenh')->dropDownList($benhs, ['prompt' => 'Chọn Bệnh (ICD)'])->label(false);
$khamTemplate = '
    <tr class="item-kham">
        <td>' . $khamFields . '</td>
        <td class="text-center"><button type="button" class="remove-item btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-minus"></i></button></td>
    </tr>
';

// --- 2. KHUÔN MẪU CHO CHI TIẾT THUỐC ---
$thuocFields = $form->field($modelChiTietThuocTemplate, '[{i}]ID')->hiddenInput()->label(false) . 
               $form->field($modelChiTietThuocTemplate, '[{i}]MaThuoc')->dropDownList($thuocs, ['prompt' => 'Chọn Thuốc', 'class' => 'form-control ma-thuoc-select'])->label(false);
$thuocTemplate = '
    <tr class="item-thuoc">
        <td>' . $thuocFields . '</td>
        <td>' . $form->field($modelChiTietThuocTemplate, '[{i}]SoLuong')->textInput(['type' => 'number', 'min' => 1])->label(false)->parts['{input}'] . '</td>
        <td>' . $form->field($modelChiTietThuocTemplate, '[{i}]GiaBan')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01])->label(false)->parts['{input}'] . '</td>
        <td class="text-center"><button type="button" class="remove-item btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-minus"></i></button></td>
    </tr>
';

$js = <<<JS
const thuocPrices = JSON.parse('$thuocPricesJson');
let rowCountThuoc = $('#table-chi-tiet-thuoc tbody tr').length;
let rowCountKham = $('#table-chi-tiet-kham tbody tr').length; // <-- Đổi tên biến đếm

// Hàm thêm dòng mới
window.addRow = function(type) {
    let newRow, container, template, rowCount, formId;

    if (type === 'thuoc') {
        container = $('#table-chi-tiet-thuoc tbody');
        template = '$thuocTemplate';
        rowCount = rowCountThuoc;
    } else if (type === 'kham') { // <-- Đổi tên type
        container = $('#table-chi-tiet-kham tbody');
        template = '$khamTemplate';
        rowCount = rowCountKham;
    }
    
    formId = '#form-kham-benh';

    newRow = template.replace(/{i}/g, rowCount);
    container.append(newRow);
    
    // Cập nhật bộ đếm
    if (type === 'thuoc') {
        rowCountThuoc++;
        registerChangeEvents($('#table-chi-tiet-thuoc'));
    } else if (type === 'kham') { // <-- Đổi tên type
        rowCountKham++;
    }

    // Cần gọi lại yii.activeForm.add() để Yii2 thêm các trường mới vào validation.
    setTimeout(function(){
        $(formId).yiiActiveForm('add', newRow);
    }, 100);
    
};

// Hàm xóa dòng
window.removeRow = function(button) {
    $(button).closest('tr').remove();
};

// Hàm đăng ký sự kiện change để tự động điền giá
const registerChangeEvents = (container) => {
    // Ngắt kết nối các sự kiện cũ để tránh chạy nhiều lần
    container.off('change', '.ma-thuoc-select'); 

    // Đăng ký lại sự kiện cho Thuốc
    container.on('change', '.ma-thuoc-select', function() {
        const maCode = $(this).val();
        const priceInput = $(this).closest('tr').find('input[id$="-giaban"]');
        if (maCode && thuocPrices[maCode]) {
            priceInput.val(thuocPrices[maCode]);
        }
    });
};

// Đăng ký sự kiện ban đầu khi DOM đã load
$(document).ready(function() {
    registerChangeEvents($('#table-chi-tiet-thuoc'));
});
JS;
$this->registerJs($js, View::POS_END);
?>