<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\Penelitian */

$this->title = 'Perbaharui Penelitian';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->judul_penelitian, 'url' => ['view', 'id' => $model->penelitian_id]];
$this->params['breadcrumbs'][] = 'Perbaharui Penelitian';
?>
<div class="penelitian-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'result' =>$result,
        'semester' => $semester,
        'jenisPenelitian' => $jenisPenelitian,
        'tahapanPenelitian' => $tahapanPenelitian,
    ]) ?>

</div>
