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
            'TenBenh',
            [
                'attribute' => 'so_benhnhan',
                'label' => 'Số Bệnh Nhân Mắc',
                'format' => 'integer',
                'contentOptions' => ['class' => 'text-center'],
            ],
        ],
    ]); ?>
</div>