<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\NhanVien $model */

$isNewRecord = empty($model->ID_NHANVIEN); // Kiểm tra bằng Primary Key

$this->title = ($isNewRecord ? 'Thêm Mới' : 'Cập Nhật') . ' Nhân Viên';
$this->params['breadcrumbs'][] = ['label' => 'Nhân Viên', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nhan-vien-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'MaNV')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-5"><?= $form->field($model, 'TenNV')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4">
            <?= $form->field($model, 'VaiTro')->dropDownList([
                'BS' => 'Bác Sĩ',
                'QL' => 'Quản Lý',
                'KT' => 'Kế Toán'
            ], ['prompt' => 'Chọn vai trò']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'ChuyenKhoa')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'HeSoLuong')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'NgaySinh')->widget(DatePicker::class, [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd',
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'DiaChi')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($isNewRecord ? 'Tạo Mới' : 'Cập Nhật', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>