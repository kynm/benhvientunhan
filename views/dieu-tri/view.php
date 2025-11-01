<?php
use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\DotDieuTri $model */

$this->title = 'Chi Tiết Đợt Điều Trị: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Đợt Điều Trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dot-dieutri-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Cập Nhật', ['update', 'ID' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'ID' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa đợt điều trị này không?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'ID_LanKham',
            'NgayBatDau',
            'NgayKetThuc',
            'TrangThai',
            // Đã bỏ MoTa và KetQuaDieuTri
        ],
    ]) ?>
</div>