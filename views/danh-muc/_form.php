<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\db\ActiveRecord $model Model (Benh, Thuoc, CSVC) */
/** @var string $type Loại danh mục */
/** @var string $title Tiêu đề danh mục */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="danh-muc-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-md-4">
            <?php 
            // Mã danh mục (tên thuộc tính khác nhau)
            if ($type === 'benh') {
                echo $form->field($model, 'MaBenh')->textInput(['maxlength' => true]);
            } elseif ($type === 'thuoc') {
                echo $form->field($model, 'MaThuoc')->textInput(['maxlength' => true]);
            } elseif ($type === 'csvc') {
                echo $form->field($model, 'MaCSVC')->textInput(['maxlength' => true]);
            }
            ?>
        </div>
        <div class="col-md-8">
            <?php
            // Tên danh mục (tên thuộc tính khác nhau)
            if ($type === 'benh') {
                echo $form->field($model, 'TenBenh')->textInput(['maxlength' => true]);
            } elseif ($type === 'thuoc') {
                echo $form->field($model, 'TenThuoc')->textInput(['maxlength' => true]);
            } elseif ($type === 'csvc') {
                echo $form->field($model, 'TenCSVC')->textInput(['maxlength' => true]);
            }
            ?>
        </div>
    </div>
    
    <?php if ($type === 'thuoc'): // Riêng cho Thuốc ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'DonViTinh')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'GiaTien')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($type === 'csvc'): // Riêng cho CSVC ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'GiaTien')->textInput(['type' => 'number', 'step' => '0.01']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'MoTa')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($type === 'benh'): // Riêng cho Bệnh ?>
        <?= $form->field($model, 'MoTa')->textarea(['rows' => 4]) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>