<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenMatakuliah */

$this->title = 'Perbaharui Matakuliah Yang Diajarkan';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->kuliah->nama_kul_ind, 'url' => ['dosen-matakuliah-view', 'id' => $model->dosen_matakuliah_id]];
$this->params['breadcrumbs'][] = 'Perbaharui Matakuliah';
?>
<div class="dosen-matakuliah-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataKuliah' => $dataKuliah,
        'dataKelas' => $dataKelas,
        'result' => $result,
        'semester'=>$semester,
        'prodi'=>$prodi,
    ]) ?>

</div>
