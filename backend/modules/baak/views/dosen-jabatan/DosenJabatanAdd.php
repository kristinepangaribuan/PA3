<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenJabatan */

$this->title = 'Tambah Jabatan Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Dosen', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-jabatan-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataJabatan' => $dataJabatan,
        'semester' => $semester,
    ]) ?>

</div>
