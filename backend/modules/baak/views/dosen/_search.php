<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\search\DosenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dosen-search">
    
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ref_kbk_id')->label('Program Studi')->dropDownList(\yii\helpers\ArrayHelper::map($Prodi, 'ref_kbk_id', 'desc_ind'),['prompt'=>'-- Pilih Program Studi --']) ?>
    <?= $form->field($model, 'nama_dosen') ?>

    <!--?= $form->field($model, 'email') ?-->

    <!--?= $form->field($model, 'alamat') ?-->

    <!--?= $form->field($model, 'nidn') ?-->

    <?php // echo $form->field($model, 'golongan_kepangkatan_id') ?>

    <?php // echo $form->field($model, 'pegawai_id') ?>

    <?php // echo $form->field($model, 'status_ikatan_kerja') ?>

    <?php // echo $form->field($model, 'aktif_start') ?>

    <?php // echo $form->field($model, 'aktif_end') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'ref_kbk_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
