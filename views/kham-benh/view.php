<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\LanKham $model */
/** @var app\models\ChiTietKham[] $modelsChiTietKham */
/** @var app\models\ChiTietThuoc[] $modelsChiTietThuoc */
/** @var app\models\ChiTietCSVC[] $modelsChiTietCSVC */

$this->title = 'Chi Tiết Phiếu Khám: ' . $model->MaKham;
$this->params['breadcrumbs'][] = ['label' => 'Phiếu Khám Bệnh', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lan-kham-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập Nhật', ['update', 'ID' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'ID' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa phiếu khám này không?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Thông Tin Cơ Bản</h5>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'MaKham',
                    'MaBN',
                    [
                        'attribute' => 'ThoiGianKham',
                        'format' => ['datetime', 'php:d/m/Y H:i:s'],
                    ],
                    'KhoaKham',
                    'ID_BS', // Cần join để hiển thị tên bác sĩ
                    [
                        'attribute' => 'TongTienKham',
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Chẩn Đoán và Kết Quả</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p><strong>Lý Do Khám:</strong> <?= Html::encode($model->LyDoKham) ?></p>
                    <p><strong>Chẩn Đoán:</strong> <?= Html::encode($model->ChanDoan) ?></p>
                    <p><strong>Kết Quả:</strong> <?= Html::encode($model->KetQua) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Chi Tiết Bệnh Kèm Theo</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($modelsChiTietKham) && $modelsChiTietKham[0]->MaBenh !== null): ?>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Mã Bệnh (ICD)</th>
                        <th>Tên Bệnh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modelsChiTietKham as $i => $chiTietKham): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= Html::encode($chiTietKham->MaBenh) ?></td>
                        <td>(Tên bệnh từ mã: <?= Html::encode($chiTietKham->MaBenh) ?>)</td> 
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-muted">Không có chi tiết bệnh kèm theo nào được ghi nhận.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Đơn Thuốc</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($modelsChiTietThuoc) && $modelsChiTietThuoc[0]->MaThuoc !== null): ?>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Mã Thuốc</th>
                        <th>Tên Thuốc</th>
                        <th style="width: 100px;">Số Lượng</th>
                        <th style="width: 150px; text-align: right;">Giá Bán</th>
                        <th style="width: 150px; text-align: right;">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalThuoc = 0;
                    foreach ($modelsChiTietThuoc as $i => $chiTietThuoc): 
                        $thanhTien = $chiTietThuoc->SoLuong * $chiTietThuoc->GiaBan;
                        $totalThuoc += $thanhTien;
                    ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= Html::encode($chiTietThuoc->MaThuoc) ?></td>
                        <td>(Tên thuốc từ mã: <?= Html::encode($chiTietThuoc->MaThuoc) ?>)</td>
                        <td style="text-align: center;"><?= Html::encode($chiTietThuoc->SoLuong) ?></td>
                        <td style="text-align: right;"><?= ($chiTietThuoc->GiaBan) ?></td>
                        <td style="text-align: right;"><?= ($thanhTien) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Tổng Tiền Thuốc:</th>
                        <th style="text-align: right;"><?= ($totalThuoc) ?></th>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
                <p class="text-muted">Không có thuốc nào được kê đơn.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Dịch Vụ/Vật Tư Y Tế</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($modelsChiTietCSVC) && $modelsChiTietCSVC[0]->MaCSVC !== null): ?>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Mã CSVC/Dịch Vụ</th>
                        <th>Tên Dịch Vụ</th>
                        <th style="width: 100px;">Số Lượng</th>
                        <th style="width: 150px; text-align: right;">Đơn Giá</th>
                        <th style="width: 150px; text-align: right;">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalCSVC = 0;
                    foreach ($modelsChiTietCSVC as $i => $chiTietCSVC): 
                        $thanhTien = $chiTietCSVC->SoLuong * $chiTietCSVC->DonGia;
                        $totalCSVC += $thanhTien;
                    ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= Html::encode($chiTietCSVC->MaCSVC) ?></td>
                        <td>(Tên dịch vụ từ mã: <?= Html::encode($chiTietCSVC->MaCSVC) ?>)</td>
                        <td style="text-align: center;"><?= Html::encode($chiTietCSVC->SoLuong) ?></td>
                        <td style="text-align: right;"><?= ($chiTietCSVC->DonGia) ?></td>
                        <td style="text-align: right;"><?= ($thanhTien) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Tổng Tiền Dịch Vụ:</th>
                        <th style="text-align: right;"><?= ($totalCSVC) ?></th>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
                <p class="text-muted">Không có dịch vụ/vật tư y tế nào được sử dụng.</p>
            <?php endif; ?>
        </div>
    </div>
    
</div>