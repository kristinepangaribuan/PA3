<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\JurnalIlmiah */

$this->title = 'Perbaharui Jurnal Ilmiah';
$this->params['breadcrumbs'][] = ['label' => 'Form Renacana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->judul_jurnal_ilmiah, 'url' => ['jurnal-ilmiah-view', 'id' => $model->jurnal_ilmiah_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jurnal-ilmiah-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataDosen'=>$dataDosen,
        'semester'=>$semester,
        'result' =>$result,
        'dosen'=>$dosen,
    ]) ?>

</div>
