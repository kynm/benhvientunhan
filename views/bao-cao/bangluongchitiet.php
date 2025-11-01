<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var string $title */
/** @var int $thang */
/** @var int $nam */

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Báo Cáo - Thống Kê', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Lấy tên chức vụ/vai trò nếu cần thiết cho định dạng
$vaiTroMap = [
    'BS' => 'Bác Sĩ',
    'YT' => 'Y Tá',
];

?>
<div class="report-bang-luong-chi-tiet">
<h1><?= Html::encode($this->title) ?></h1>
    <div class="mb-3">
        <?= Html::a(
            'Chạy Tổng Hợp Lương Tháng Này (' . $thang . '/' . $nam . ')',
            ['tinh-tong-hop-luong', 'thang' => $thang, 'nam' => $nam],
            [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => 'Bạn có chắc chắn muốn tính toán lại lương cho tháng ' . $thang . '/' . $nam . '? (Dữ liệu cũ sẽ bị ghi đè).',
                    'method' => 'post', // Nên dùng POST cho các action thay đổi dữ liệu
                ],
            ]
        ) ?>
        
        </div>
    <p class="text-info">Bảng lương chi tiết được tính toán và ghi nhận trong CSDL cho kỳ **Tháng <?= Html::encode($thang) ?>/<?= Html::encode($nam) ?>**.</p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => "Hiển thị **{begin} - {end}** trong tổng số **{totalCount}** nhân viên.",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'TenNV',
                'label' => 'Họ Tên Nhân Viên',
                'headerOptions' => ['style' => 'width: 20%;'],
            ],
            [
                'attribute' => 'VaiTro',
                'label' => 'Chức Danh',
                'value' => function ($model) use ($vaiTroMap) {
                    return $vaiTroMap[$model['VaiTro']] ?? $model['VaiTro'];
                },
                'contentOptions' => ['class' => 'text-center'],
            ],
            
            // Lương Cơ Bản
            [
                'attribute' => 'LuongCoBan',
                'label' => 'Lương Cơ Bản',
                'format' => ['decimal', 0], // Định dạng tiền tệ, không lấy số thập phân
                'contentOptions' => ['class' => 'text-right'],
            ],
            
            // Thưởng
            [
                'attribute' => 'Thuong',
                'label' => 'Tiền Thưởng',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-right', 'style' => 'color: green;'],
            ],
            
            // Tổng Lương Thực Nhận
            [
                'attribute' => 'TongLuong',
                'label' => 'TỔNG LƯƠNG NHẬN',
                'format' => ['decimal', 0],
                'contentOptions' => ['class' => 'text-right', 'style' => 'font-weight: bold; color: blue; font-size: 1.1em;'],
            ],
            
            // Ngày tính (Có thể ẩn nếu không cần)
            [
                'attribute' => 'NgayTinh',
                'label' => 'Ngày Tính',
                'format' => ['date', 'php:d/m/Y H:i'],
                'contentOptions' => ['class' => 'text-center', 'style' => 'font-size: 0.9em;'],
            ],
        ],
    ]); ?>

</div>