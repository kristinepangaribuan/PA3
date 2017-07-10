<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\MengujiProposal */

$this->title = 'Tambah Menguji Proposal';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menguji-proposal-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'tahunAjaran'=>$tahunAjaran,
        'jenisProposal' =>$jenisProposal,
        'semester' => $semester,
    ]) ?>

</div>
