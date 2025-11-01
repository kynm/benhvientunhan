<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var string $title */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'GioiTinh',
                'label' => 'Giới Tính'
            ],
            [
                'attribute' => 'TongBenhNhan',
                'label' => 'Tổng Số Bệnh Nhân',
                'format' => 'integer',
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'TuoiTrungBinh',
                'label' => 'Tuổi Trung Bình',
                'format' => ['decimal', 1],
                'contentOptions' => ['class' => 'text-center'],
            ],
        ],
    ]); ?>

</div>