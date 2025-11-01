<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Quản Lý Bệnh Nhân';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="benh-nhan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Thêm Mới Bệnh Nhân', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // Loại bỏ 'filterModel' vì chúng ta không dùng SearchModel
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // Các cột phải khớp với tên thuộc tính trong Base Model và cột trong CSDL
            'MaBN',
            'TenBN',
            'GioiTinh',
            'NgaySinh',
            'DienThoai',
            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Thao Tác',
                'buttons' => [
                    // Đảm bảo actionColumn hoạt động với ID_NHANVIEN (hoặc ID)
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="fas fa-eye"></span>', ['view', 'id' => $model['ID']], [
                            'title' => 'Xem chi tiết',
                            'aria-label' => 'Xem chi tiết',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="fas fa-edit"></span>', ['update', 'id' => $model['ID']], [
                            'title' => 'Cập nhật',
                            'aria-label' => 'Cập nhật',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="fas fa-trash"></span>', ['delete', 'id' => $model['ID']], [
                            'title' => 'Xóa',
                            'aria-label' => 'Xóa',
                            'data' => [
                                'confirm' => 'Bạn có chắc chắn muốn xóa bệnh nhân này không?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>