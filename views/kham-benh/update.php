<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\LanKham $model */
/** @var app\models\ChiTietKham[] $modelsChiTietKham */
/** @var app\models\ChiTietThuoc[] $modelsChiTietThuoc */
/** @var app\models\ChiTietCSVC[] $modelsChiTietCSVC */

$this->title = 'Cập Nhật Phiếu Khám: ' . $model->MaKham;
$this->params['breadcrumbs'][] = ['label' => 'Phiếu Khám Bệnh', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->MaKham, 'url' => ['view', 'ID' => $model->ID]];
$this->params['breadcrumbs'][] = 'Cập Nhật';
?>
<div class="lan-kham-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">⚠️ Lưu ý Quan trọng!</h4>
        <p>Do đang sử dụng Query Builder (không dùng ActiveRecord), việc **cập nhật/xóa các chi tiết** (Chẩn đoán, Thuốc, CSVC) trong form này yêu cầu logic xử lý phức tạp trong Controller. Bạn cần đảm bảo đã viết logic **DELETE** chi tiết cũ và **INSERT** chi tiết mới trong <code>KhamBenhController::actionUpdate()</code>.</p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsChiTietKham' => $modelsChiTietKham,
        'modelsChiTietThuoc' => $modelsChiTietThuoc,
        'modelsChiTietCSVC' => $modelsChiTietCSVC,
    ]) ?>

</div>