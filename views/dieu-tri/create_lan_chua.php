<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\LanChua $lanChua */
/** @var app\models\DotDieuTri $dotDieuTri */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Thêm Lần Chữa Mới cho Đợt ' . $dotDieuTri->ID;
$this->params['breadcrumbs'][] = ['label' => 'Đợt Điều Trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $dotDieuTri->ID, 'url' => ['view-dot', 'id' => $dotDieuTri->ID]];
$this->params['breadcrumbs'][] = 'Thêm Lần Chữa';

// Giả định: Thuốc và CSVC data đã được truyền từ Controller (tương tự KhamBenhController)
$dataThuoc = ArrayHelper::map(\app\models\Thuoc::find()->all(), 'MaThuoc', 'TenThuoc');
$dataCSVC = ArrayHelper::map(\app\models\CSVC::find()->all(), 'MaCSVC', 'TenCSVC');
$dataYTa = ArrayHelper::map(\app\models\NhanVien::find()->where(['VaiTro' => 'YT'])->all(), 'ID_NHANVIEN', 'TenNV');
?>

<div class="lan-chua-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>Bệnh nhân: **<?= $dotDieuTri->lanKham->benhNhan->TenBN ?>** | Bệnh: **<?= $dotDieuTri->benh->TenBenh ?>**</p>
    <hr>

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($lanChua, 'ThoiGianChua')->textInput(['value' => date('Y-m-d H:i:s'), 'readonly' => true]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($lanChua, 'HinhThucChua')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($lanChua, 'ID_BS')->widget(Select2::class, [
                'data' => ArrayHelper::map(\app\models\NhanVien::find()->where(['VaiTro' => 'BS'])->all(), 'ID_NHANVIEN', 'TenNV'),
                'options' => ['placeholder' => 'Chọn bác sĩ thực hiện...'],
                'pluginOptions' => ['allowClear' => true],
            ])->label('Bác Sĩ Thực Hiện') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($lanChua, 'ytas')->widget(Select2::class, [ // Thuộc tính ảo 'ytas'
                'data' => $dataYTa,
                'options' => ['placeholder' => 'Chọn y tá hỗ trợ...', 'multiple' => true],
                'pluginOptions' => ['allowClear' => true],
            ])->label('Y tá Hỗ trợ') ?>
        </div>
    </div>

    <?= $form->field($lanChua, 'KetLuan')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Lưu Lần Chữa', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>