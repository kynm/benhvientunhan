<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\LanKham $model */
/** @var app\models\ChiTietThuoc[] $modelsChiTietThuoc */
/** @var app\models\ChiTietKham[] $modelsChiTietKham */ // Đã đổi tên Model

$this->title = 'Lập Phiếu Khám Bệnh Mới';
$this->params['breadcrumbs'][] = ['label' => 'Phiếu Khám Bệnh', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lan-kham-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsChiTietThuoc' => $modelsChiTietThuoc,
        'modelsChiTietKham' => $modelsChiTietKham, // Sử dụng tên Model Chi Tiết Khám đã sửa
    ]) ?>

</div>