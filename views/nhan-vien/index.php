<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Quản Lý Nhân Viên';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nhan-vien-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Thêm Mới Nhân Viên', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'MaNV',
            'TenNV',
            'VaiTro',
            'ChuyenKhoa',
            'HeSoLuong',
            
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Thao Tác',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    // Đảm bảo dùng ID_NHANVIEN
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="fas fa-eye"></span>', ['view', 'id' => $model['ID_NHANVIEN']]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="fas fa-edit"></span>', ['update', 'id' => $model['ID_NHANVIEN']]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="fas fa-trash"></span>', ['delete', 'id' => $model['ID_NHANVIEN']], [
                            'data' => ['confirm' => 'Bạn có chắc chắn muốn xóa nhân viên này không?', 'method' => 'post'],
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>