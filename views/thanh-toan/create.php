<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\HoaDon $model */

$this->title = 'Xác Nhận Lập Hóa Đơn';
$this->params['breadcrumbs'][] = ['label' => 'Hóa Đơn', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hoa-don-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        Bạn đang lập hóa đơn cho lần khám **<?= Html::encode($model->MaKham) ?>** của bệnh nhân **<?= Html::encode($model->TenBN) ?>**.
        Vui lòng xác nhận thông tin chi phí trước khi lưu.
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Mã Lần Khám',
                'value' => $model->MaKham,
            ],
            [
                'label' => 'Bệnh Nhân',
                'value' => $model->TenBN . ' (' . $model->MaBN . ')',
            ],
            [
                'label' => 'Tổng Tiền Thanh Toán',
                'value' => $model->TongTien,
                'captionOptions' => ['style' => 'font-weight: bold;'],
                'contentOptions' => ['style' => 'font-weight: bold; font-size: 1.2em; color: green;'],
            ],
        ],
    ]) ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'ID_LanKham')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'TongTien')->hiddenInput()->label(false) ?>
        </div>
    </div>
    
    <div class="form-group mt-3">
        <?= Html::submitButton('Xác Nhận Thanh Toán & Lập Hóa Đơn', ['class' => 'btn btn-success btn-lg']) ?>
        <?= Html::a('Hủy', ['kham-benh/index'], ['class' => 'btn btn-secondary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>