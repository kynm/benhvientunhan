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
                'attribute' => 'Thang',
                'label' => 'Tháng'
            ],
            [
                'attribute' => 'Nam',
                'label' => 'Năm'
            ],
            [
                'attribute' => 'SoLuong',
                'label' => 'Tổng Số Lần Khám',
                'format' => 'integer',
                'contentOptions' => ['class' => 'text-center'],
            ],
        ],
    ]); ?>

</div>