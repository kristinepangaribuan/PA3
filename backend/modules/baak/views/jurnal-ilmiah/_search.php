<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\search\JurnalIlmiahSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jurnal-ilmiah-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'jurnal_ilmiah_id') ?>

    <?= $form->field($model, 'judul_jurnal_ilmiah') ?>

    <?= $form->field($model, 'penerbit_jurnal_ilmiah') ?>

    <?= $form->field($model, 'semester_id') ?>

    <?= $form->field($model, 'header_dokumen_bukti_id') ?>

    <?php // echo $form->field($model, 'tahapan_jurnal_ilmiah') ?>

    <?php // echo $form->field($model, 'jlh_target') ?>

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
