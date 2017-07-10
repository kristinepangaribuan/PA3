<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\MakalahSeminar */

$this->title = 'Perbaharui Makalah Seminar' . $model->judul_makalah;
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->judul_makalah, 'url' => ['makalah-seminar-view', 'id' => $model->makalah_seminar_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="makalah-seminar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'result' =>$result,
        'semester' => $semester,
        'dosen' => $dosen,
    ]) ?>

</div>
