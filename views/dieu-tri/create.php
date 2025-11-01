<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\DotDieuTri $model */
/** @var array $lanKhams */ // Phải có các khai báo này
/** @var array $benhs */
/** @var array $bacSis */

$this->title = 'Tạo Đợt Điều Trị Mới';
$this->params['breadcrumbs'][] = ['label' => 'Đợt Điều Trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dot-dieutri-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'lanKhams' => $lanKhams,
        'benhs' => $benhs, // ✅ Phải truyền biến benhs
        'bacSis' => $bacSis,
    ]) ?>

</div>