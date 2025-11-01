<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use app\models\CSVCSuDung;

/** @var yii\web\View $this */
/** @var app\models\LanKham $model */
/** @var array $modelsCSVC */
/** @var array $dataCSVC */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dynamicform_wrapper_csvc">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Chi tiết CSVC/Dịch Vụ Sử Dụng</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 40%">CSVC/Dịch vụ</th>
                        <th style="width: 20%">Số lượng</th>
                        <th style="width: 30%">Ghi chú</th>
                        <th style="width: 5%"></th>
                    </tr>
                </thead>
                <tbody class="container-items-csvc">
                <?php foreach ($modelsCSVC as $i => $modelCSVC): ?>
                    <tr class="item panel panel-default">
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td>
                            <?php 
                            // Mã ẩn để lưu ID chi tiết (nếu có)
                            echo Html::activeHiddenInput($modelCSVC, "[{$i}]ID");
                            
                            echo $form->field($modelCSVC, "[{$i}]ID_CSVC")->widget(Select2::class, [
                                'data' => $dataCSVC,
                                'options' => ['placeholder' => 'Chọn CSVC/Dịch vụ...'],
                                'pluginOptions' => ['allowClear' => true],
                            ])->label(false);
                            ?>
                        </td>
                        <td>
                            <?= $form->field($modelCSVC, "[{$i}]SoLuong")->textInput(['type' => 'number', 'min' => 1])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($modelCSVC, "[{$i}]GhiChu")->textInput(['maxlength' => true])->label(false) ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fas fa-minus"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"></td>
                        <td class="text-center">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="fas fa-plus"></i> Thêm</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>