<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DotDieuTriSearch $searchModel */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Quản Lý Đợt Điều Trị';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dot-dieutri-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Tạo Đợt Điều Trị', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'ID',
            'ID_LanKham',
            'NgayBatDau',
            'NgayKetThuc',
            'TrangThai',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'ID' => $key]);
                 }
            ],
        ],
    ]); ?>
</div>