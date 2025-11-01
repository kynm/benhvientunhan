<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BenhNhan $model */

$this->title = ($model->isNewRecord ? 'Thêm Mới' : 'Cập Nhật') . ' Bệnh Nhân';
$this->params['breadcrumbs'][] = ['label' => 'Bệnh Nhân', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="benh-nhan-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'MaBN')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'TenBN')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'NgaySinh')->widget(\yii\jui\DatePicker::class, [
                'options' => ['class' => 'form-control'],
                'dateFormat' => 'yyyy-MM-dd',
            ]) ?>
        </div>
        <div class="col-md-3">
             <?= $form->field($model, 'GioiTinh')->dropDownList([
                'Nam' => 'Nam', 
                'Nữ' => 'Nữ',
                'Khác' => 'Khác'
            ], ['prompt' => 'Chọn giới tính']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'DienThoai')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'DiaChi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tạo Mới' : 'Cập Nhật', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>