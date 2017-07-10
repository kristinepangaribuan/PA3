<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\SeminarTerjadwal */

$this->title = 'Tambah Seminar Terjadwal';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seminar-terjadwal-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'dataTahunAjaran'=>$dataTahunAjaran,
        'semester' => $semester,
        'dosen' => $dosen,
    ]) ?>

</div>
