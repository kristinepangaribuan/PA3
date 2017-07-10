<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\Dosen */

$this->title = 'Update Dosen: ' . $model->dosen_id;
$this->params['breadcrumbs'][] = ['label' => 'Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dosen_id, 'url' => ['view', 'id' => $model->dosen_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
