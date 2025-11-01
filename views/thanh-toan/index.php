<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Danh Sách Hóa Đơn Thanh Toán';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hoa-don-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="alert alert-info">
        Hóa đơn được lập từ trang **Khám Bệnh** cho các lần khám chưa thanh toán.
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            [
                'attribute' => 'MaKham', 
                'label' => 'Mã Lần Khám',
            ],
            [
                'attribute' => 'TenBN', // Dữ liệu đã có từ JOIN
                'label' => 'Bệnh Nhân',
            ],
            'NgayLap:datetime',
            'TongTien', 
            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Thao Tác',
                'template' => '{delete}', // Chỉ cho phép xóa
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="fas fa-trash"></span>', ['delete', 'id' => $model['ID']], [
                            'data' => ['confirm' => 'Xóa Hóa Đơn sẽ mở lại Lần Khám để lập hóa đơn mới. Bạn có chắc chắn muốn xóa?', 'method' => 'post'],
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>