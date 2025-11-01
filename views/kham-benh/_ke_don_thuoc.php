<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use app\models\KeDonThuoc;

/** @var yii\web\View $this */
/** @var app\models\LanKham $model */
/** @var array $modelsKeDon */
/** @var array $dataThuoc */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dynamicform_wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Chi tiết Thuốc Kê Đơn</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 30%">Thuốc</th>
                        <th style="width: 15%">Số lượng</th>
                        <th style="width: 20%">Cách dùng</th>
                        <th style="width: 25%">Ghi chú</th>
                        <th style="width: 5%"></th>
                    </tr>
                </thead>
                <tbody class="container-items">
                <?php foreach ($modelsKeDon as $i => $modelKeDon): ?>
                    <tr class="item panel panel-default">
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td>
                            <?php 
                            // Mã ẩn để lưu ID chi tiết (nếu có)
                            echo Html::activeHiddenInput($modelKeDon, "[{$i}]ID");
                            
                            echo $form->field($modelKeDon, "[{$i}]ID_THUOC")->widget(Select2::class, [
                                'data' => $dataThuoc,
                                'options' => ['placeholder' => 'Chọn thuốc...'],
                                'pluginOptions' => ['allowClear' => true],
                            ])->label(false);
                            ?>
                        </td>
                        <td>
                            <?= $form->field($modelKeDon, "[{$i}]SoLuong")->textInput(['type' => 'number', 'min' => 1])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($modelKeDon, "[{$i}]CachDung")->textInput(['maxlength' => true])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($modelKeDon, "[{$i}]GhiChu")->textInput(['maxlength' => true])->label(false) ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fas fa-minus"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"></td>
                        <td class="text-center">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="fas fa-plus"></i> Thêm</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>