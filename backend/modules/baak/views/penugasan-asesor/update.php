<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\PenugasanAsesor */

$this->title = 'Update Penugasan Asesor: ' . $model->penugasan_asesor_id;
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Asesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->penugasan_asesor_id, 'url' => ['view', 'id' => $model->penugasan_asesor_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="penugasan-asesor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
