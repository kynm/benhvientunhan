<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\BenhNhan $model */

$this->title = $model->TenBN;
$this->params['breadcrumbs'][] = ['label' => 'Bệnh Nhân', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="benh-nhan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập Nhật', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Xóa', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bạn có chắc chắn muốn xóa bệnh nhân này không?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'MaBN',
            'TenBN',
            'GioiTinh',
            'NgaySinh:date',
            'DienThoai',
            'Email:email',
            'DiaChi',
        ],
    ]) ?>

</div>