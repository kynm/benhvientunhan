<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\base\Model $model */
/** @var string $type Loại danh mục */
/** @var string $title Tiêu đề danh mục */

// Kiểm tra bằng Primary Key
$isNewRecord = empty($model->ID);

$this->title = ($isNewRecord ? 'Thêm Mới' : 'Cập Nhật') . ' ' . $title;
$this->params['breadcrumbs'][] = ['label' => 'Danh Mục ' . $title, 'url' => ['index', 'type' => $type]];
$this->params['breadcrumbs'][] = $this->title;

// Lấy danh sách các thuộc tính cần hiển thị trong form
$attributes = array_keys($model->attributeLabels());
// Loại bỏ ID khỏi form
$attributes = array_filter($attributes, function($attr) {
    return $attr !== 'ID';
});

?>

<div class="danh-muc-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?php 
    // Duyệt qua các thuộc tính và tạo field tương ứng
    foreach ($attributes as $attribute) {
        // Xử lý các trường đặc biệt
        if (strpos($attribute, 'GiaTien') !== false || strpos($attribute, 'HeSoLuong') !== false) {
            echo $form->field($model, $attribute)->textInput(['type' => 'number']);
        } elseif (strpos($attribute, 'MoTa') !== false) {
            echo $form->field($model, $attribute)->textarea(['rows' => 3]);
        } else {
            echo $form->field($model, $attribute)->textInput(['maxlength' => true]);
        }
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($isNewRecord ? 'Tạo Mới' : 'Cập Nhật', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hủy', ['index', 'type' => $type], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>