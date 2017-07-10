<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\BimbinganKuliah */

$this->title = 'Tambah Bimbingan Kuliah';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bimbingan-kuliah-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'tahunAjaran'=>$tahunAjaran,
        'jenisBimbingan'=>$jenisBimbingan,
        'semester'=> $semester,
    ]) ?>

</div>
