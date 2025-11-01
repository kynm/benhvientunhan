<?php

use yii\helpers\Html;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\LanKham $model */
/** @var array $dataBenh */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tab-pane active" id="tab_chandoan">
    
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'ChanDoanSoBo')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'ID_BENH')->widget(Select2::class, [
                'data' => $dataBenh,
                'options' => ['placeholder' => 'Chọn bệnh/mã bệnh (ICD)...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true, // Cho phép chọn nhiều bệnh
                ],
            ])->label('Chẩn Đoán Xác Định (ICD)') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'KetLuan')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    
</div>