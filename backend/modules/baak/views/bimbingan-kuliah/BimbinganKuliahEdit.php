<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\BimbinganKuliah */

$this->title = 'Edit Bimbingan Kuliah: ' . $model->bimbingan_kuliah_id;
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->bimbingan_kuliah_id, 'url' => ['bimbingan-kuliah-view', 'id' => $model->bimbingan_kuliah_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="bimbingan-kuliah-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
            'tahunAjaran'=>$tahunAjaran,
            'jenisBimbingan'=>$jenisBimbingan,
            'semester'=>$semester,
    ]) ?>

</div>
