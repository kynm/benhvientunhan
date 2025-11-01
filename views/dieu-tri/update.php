<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\DotDieuTri $model */
/** @var array $lanKhams */ // Phải có các khai báo này
/** @var array $benhs */
/** @var array $bacSis */

$this->title = 'Cập Nhật Đợt Điều Trị: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Đợt Điều Trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'ID' => $model->ID]];
$this->params['breadcrumbs'][] = 'Cập Nhật';
?>
<div class="dot-dieutri-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'lanKhams' => $lanKhams,
        'benhs' => $benhs, // ✅ Phải truyền biến benhs
        'bacSis' => $bacSis,
    ]) ?>

</div>