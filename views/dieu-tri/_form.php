<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DotDieuTri $model */
/** @var array $lanKhams Mảng [ID => MaKham] */ 
/** @var array $benhs Mảng [MaBenh => TenBenh] */ 
/** @var array $bacSis Mảng [ID_NHANVIEN => HoTen] */ 

$isNewRecord = empty($model->ID); 

?>

<div class="dot-dieutri-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'ID_LanKham')->dropDownList(
                $lanKhams, 
                [
                    'prompt' => '--- Chọn Mã Lần Khám ---', 
                    'disabled' => !$isNewRecord
                ]
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'MaBenh')->dropDownList(
                $benhs, 
                ['prompt' => '--- Chọn Mã Bệnh ---']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'ID_BS')->dropDownList(
                $bacSis, 
                ['prompt' => '--- Chọn Bác Sĩ ---']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'NgayBatDau')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'NgayKetThuc')->textInput(['type' => 'date']) ?>
            <small class="form-text text-muted">Có thể để trống nếu đợt điều trị chưa kết thúc.</small>
        </div>
    </div>
    
    <?= $form->field($model, 'TrangThai')->dropDownList([
        'Mới' => 'Mới',
        'Đang Điều Trị' => 'Đang Điều Trị',
        'Hoàn Thành' => 'Hoàn Thành',
        'Tạm Ngưng' => 'Tạm Ngưng',
    ], ['prompt' => '--- Chọn Trạng Thái ---']) ?>

    <div class="form-group">
        <?= Html::submitButton('Lưu', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>