<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\search\AsistenTugasPraktikumSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asisten-tugas-praktikum-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'asisten_tugas_praktikum_id') ?>

    <?= $form->field($model, 'jlh_sks_asistensi') ?>

    <?= $form->field($model, 'jlh_mhs_praktikum') ?>

    <?= $form->field($model, 'kuliah_id') ?>

    <?= $form->field($model, 'semester_id') ?>

    <?php // echo $form->field($model, 'header_dokumen_bukti_id') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
