<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\search\KegiatanPengabdianMasyarakatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-pengabdian-masyarakat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kegiatan_pengabdian_masyarakat_id') ?>

    <?= $form->field($model, 'nama_kegiatan') ?>

    <?= $form->field($model, 'semester_id') ?>

    <?= $form->field($model, 'header_dokumen_bukti_id') ?>

    <?= $form->field($model, 'kategori_kegiatan') ?>

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
