<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\SeminarTerjadwal */

$this->title = 'Perbaharui Seminar Terjadwal';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_seminar, 'url' => ['seminar-terjadwal-view', 'id' => $model->seminar_terjadwal_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seminar-terjadwal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'dataTahunAjaran'=>$dataTahunAjaran,
        'result'=>$result,
        'semester' => $semester,
    ]) ?>

</div>
