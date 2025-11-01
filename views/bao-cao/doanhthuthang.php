<?php
use yii\bootstrap4\Html;
use yii\grid\GridView;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nam',
            'thang',
            [
                'attribute' => 'doanh_thu_kham',
                'label' => 'Doanh Thu Khám',
                'format' => ['decimal', 0],
            ],
            [
                'attribute' => 'doanh_thu_thuoc',
                'label' => 'Doanh Thu Thuốc',
                'format' => ['decimal', 0],
            ],
            [
                'attribute' => 'doanh_thu_csvc',
                'label' => 'Doanh Thu CSVC',
                'format' => ['decimal', 0],
            ],
            [
                'attribute' => 'tong_doanh_thu',
                'label' => 'TỔNG DOANH THU',
                'format' => ['decimal', 0],
                'contentOptions' => ['style' => 'font-weight: bold; color: blue;'],
            ],
        ],
    ]); ?>
</div>