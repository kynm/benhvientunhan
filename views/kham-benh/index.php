<?php

use app\models\LanKham;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LanKhamSearch $searchModel */
/** @var yii\data\ArrayDataProvider $dataProvider // PHẢI LÀ ArrayDataProvider NẾU KHÔNG DÙNG AR */

$this->title = 'Quản Lý Phiếu Khám Bệnh';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lan-kham-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Tạo Phiếu Khám Mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // Render form tìm kiếm nếu có
    // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // Cột hiển thị Mã Khám
            'MaKham',
            
            // Cột hiển thị Mã Bệnh Nhân
            'MaBN',
            
            // Cột hiển thị thời gian khám
            'ThoiGianKham',
            
            // Cột hiển thị Bác Sĩ (Nếu bạn có quan hệ join, hãy sửa để hiển thị Tên BS)
            'ID_BS',
            
            // Cột hiển thị Khoa Khám
            'KhoaKham',
            
            // Cột hiển thị Tổng Tiền
            [
                'attribute' => 'TongTienKham',
                'headerOptions' => ['style' => 'width: 150px; text-align: right;'],
                'contentOptions' => ['style' => 'text-align: right;'],
            ],

            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    // $model ở đây là một mảng hoặc object (không phải ActiveRecord)
                    // $key là giá trị ID
                    return Url::toRoute([$action, 'ID' => $key]);
                 },
                'header' => 'Hành Động',
            ],
        ],
    ]); ?>


</div>