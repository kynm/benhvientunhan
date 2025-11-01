<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var string $type Loại danh mục: 'benh', 'thuoc', 'csvc' */
/** @var string $title Tiêu đề danh mục */

$this->title = 'Quản Lý Danh Mục ' . $title;
$this->params['breadcrumbs'][] = $this->title;

// Định nghĩa cột hiển thị dựa trên loại danh mục
$columns = [
    ['class' => 'yii\grid\SerialColumn'],
];

if ($type === 'benh') {
    $columns[] = 'MaBenh';
    $columns[] = 'TenBenh';
    $columns[] = 'MoTa';
} elseif ($type === 'thuoc') {
    $columns[] = 'MaThuoc';
    $columns[] = 'TenThuoc';
    $columns[] = 'DonViTinh';
    $columns[] = 'GiaTien';
} elseif ($type === 'csvc') {
    $columns[] = 'MaCSVC';
    $columns[] = 'TenCSVC';
    $columns[] = 'GiaTien';
    $columns[] = 'MoTa';
}

$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'header' => 'Thao Tác',
    'template' => '{update} {delete}',
    'buttons' => [
        'update' => function ($url, $model, $key) use ($type) {
            return Html::a('<span class="fas fa-edit"></span>', ['update', 'type' => $type, 'id' => $model['ID']], [
                'title' => 'Cập nhật',
            ]);
        },
        'delete' => function ($url, $model, $key) use ($type) {
            return Html::a('<span class="fas fa-trash"></span>', ['delete', 'type' => $type, 'id' => $model['ID']], [
                'title' => 'Xóa',
                'data' => [
                    'confirm' => 'Bạn có chắc chắn muốn xóa mục này không?',
                    'method' => 'post',
                ],
            ]);
        },
    ]
];

?>
<div class="danh-muc-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Thêm Mới ' . $title, ['create', 'type' => $type], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>

</div>