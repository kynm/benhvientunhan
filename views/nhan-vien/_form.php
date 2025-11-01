<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\NhanVien $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="nhan-vien-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'MaNV')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'TenNV')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'VaiTro')->dropDownList([
                'BS' => 'Bác sĩ', 
                'YT' => 'Y tá', 
                'QL' => 'Quản lý', // Giả định thêm Quản lý
            ], ['prompt' => 'Chọn vai trò...']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'GioiTinh')->dropDownList([
                'Nam' => 'Nam', 
                'Nữ' => 'Nữ', 
                'Khác' => 'Khác'
            ], ['prompt' => 'Chọn giới tính...']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'NgaySinh')->widget(DatePicker::class, [
                'language' => 'vi',
                'dateFormat' => 'dd/MM/yyyy',
                'options' => ['class' => 'form-control'],
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '1900:' . date('Y'),
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'DienThoai')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'DiaChi')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'ChuyenKhoa')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'HeSoLuong')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>