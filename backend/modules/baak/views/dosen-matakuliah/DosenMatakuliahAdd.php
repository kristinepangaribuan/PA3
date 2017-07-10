<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenMatakuliah */

$this->title = 'Tambah matakuliah yang Diajarkan';
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-matakuliah-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'dataKuliah' => $dataKuliah,
        'dataKelas' => $dataKelas,
        'semester' => $semester,
        'prodi' => $prodi,
    ]) ?>

</div>
