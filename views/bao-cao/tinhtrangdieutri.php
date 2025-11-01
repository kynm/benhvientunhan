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

    <div class="alert alert-info">
        <p>Báo cáo này sử dụng **Raw SQL** với hàm `GROUP_CONCAT` để nhóm dữ liệu.</p>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'TrangThai',
                'label' => 'Trạng Thái Điều Trị'
            ],
            [
                'attribute' => 'SoLuongDot',
                'label' => 'Số Lượng Đợt',
                'format' => 'integer',
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'CacMaKham',
                'label' => 'Các Mã Khám Liên Quan',
                'format' => 'ntext',
            ],
        ],
    ]); ?>

</div>