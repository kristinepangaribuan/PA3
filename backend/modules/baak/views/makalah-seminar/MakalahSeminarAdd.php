<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\MakalahSeminar */

$this->title = 'Tambah Makalah Seminar';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="makalah-seminar-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'semester' => $semester,
        'dosen' => $dosen,
    ]) ?>

</div>
