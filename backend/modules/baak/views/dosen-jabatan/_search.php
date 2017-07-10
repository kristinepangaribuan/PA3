<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenJabatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dosen-jabatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dosen_jabatan_id') ?>

    <?= $form->field($model, 'dosen_id') ?>

    <?= $form->field($model, 'struktur_jabatan_id') ?>

    <?= $form->field($model, 'semester_id') ?>

    <?= $form->field($model, 'header_dokumen_bukti_id') ?>

    <?php // echo $form->field($model, 'jlh_sks_beban_kerja_dosen') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
