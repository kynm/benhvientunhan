<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var app\models\HoaDon $model */

$this->title = 'Hóa Đơn: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Hóa Đơn', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$lanKham = $model->lanKham;
?>
<div class="hoa-don-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập Nhật Trạng Thái', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('In Hóa Đơn', ['print', 'id' => $model->ID], ['class' => 'btn btn-info', 'target' => '_blank']) ?>
    </p>
    
    <div class="row">
        <div class="col-md-6">
            <h2>Thông tin Hóa Đơn</h2>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Bệnh Nhân',
                        'value' => $lanKham->benhNhan->TenBN . ' (Mã: ' . $lanKham->benhNhan->MaBN . ')',
                    ],
                    [
                        'label' => 'Mã Phiếu Khám',
                        'value' => $lanKham->MaKham,
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'NgayLap',
                        'format' => ['date', 'php:d/m/Y H:i'],
                    ],
                    [
                        'attribute' => 'TongTien',
                        'format' => ['decimal', 0],
                        'captionOptions' => ['style' => 'font-weight: bold;'],
                        'contentOptions' => ['style' => 'font-weight: bold; color: red; font-size: 1.2em;'],
                    ],
                    [
                        'attribute' => 'TrangThaiThanhToan',
                        'contentOptions' => ['class' => $model->TrangThaiThanhToan == 'Đã thanh toán' ? 'text-success font-weight-bold' : 'text-danger font-weight-bold'],
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <h2>Thao tác</h2>
            <?= Html::a('Xem Phiếu Khám Chi Tiết', ['kham-benh/view', 'id' => $lanKham->ID], ['class' => 'btn btn-warning btn-block mb-3']) ?>

            <?php if ($model->TrangThaiThanhToan !== 'Đã thanh toán'): ?>
                <div class="alert alert-danger">
                    **Tình trạng:** Hóa đơn chưa thanh toán.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <hr>
    
    <h2>Chi tiết Dịch vụ & Thuốc</h2>
    <div class="alert alert-info">
        **Lưu ý:** Chi tiết hóa đơn được lấy từ Phiếu Khám **<?= $lanKham->MaKham ?>**.
    </div>
    
    <h3>Thuốc Kê Đơn</h3>
    <?php 
    $dataProviderThuoc = new ArrayDataProvider([
        'allModels' => $lanKham->keDons,
        'pagination' => false,
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProviderThuoc,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Thuốc',
                'value' => 'thuoc.TenThuoc',
            ],
            'SoLuong',
            [
                'label' => 'Đơn giá',
                'value' => 'thuoc.GiaTien',
                'format' => ['decimal', 0],
            ],
            [
                'label' => 'Thành tiền',
                'value' => function($model) { return $model->SoLuong * $model->thuoc->GiaTien; },
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-right'],
            ],
        ],
    ]); ?>
    
    <h3>Dịch Vụ/CSVC Sử Dụng</h3>
    <?php 
    $dataProviderCSVC = new ArrayDataProvider([
        'allModels' => $lanKham->csvcSuDungs,
        'pagination' => false,
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProviderCSVC,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Dịch Vụ/CSVC',
                'value' => 'csvc.TenCSVC',
            ],
            'SoLuong',
            [
                'label' => 'Đơn giá',
                'value' => 'csvc.GiaTien',
                'format' => ['decimal', 0],
            ],
            [
                'label' => 'Thành tiền',
                'value' => function($model) { return $model->SoLuong * $model->csvc->GiaTien; },
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-right'],
            ],
        ],
    ]); ?>

</div>