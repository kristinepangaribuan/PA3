<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenJabatan */

$this->title = 'Edit Jabatan Dosen: ' . $model->dosen_jabatan_id;
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->dosen_jabatan_id, 'url' => ['dosen-jabatan-view', 'id' => $model->dosen_jabatan_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="dosen-jabatan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataJabatan' => $dataJabatan,
        'semester' => $semester,
    ]) ?>

</div>
