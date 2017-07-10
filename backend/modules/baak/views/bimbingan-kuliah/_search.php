<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\search\BimbinganKuliahSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bimbingan-kuliah-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bimbingan_kuliah_id') ?>

    <?= $form->field($model, 'topik_bimbingan') ?>

    <?= $form->field($model, 'jlh_mhs_bimbingan_kuliah') ?>

    <?= $form->field($model, 'jenis_bimbingan_id') ?>

    <?= $form->field($model, 'semester_id') ?>

    <?php // echo $form->field($model, 'dosen_id') ?>

    <?php // echo $form->field($model, 'jlh_sks_bimbingan_kuliah') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
