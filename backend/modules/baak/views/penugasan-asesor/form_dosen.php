<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\PenugasanAsesor */

$this->title = 'List dosen yang akan dicek';
//$this->params['breadcrumbs'][] = ['label' => 'Penugasan Asesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penugasan-asesor-create">
    <div class="row">
        <div class="col-md-12">
            <h1><?= Html::encode($this->title) ?></h1>
            <div class="col-md-10">
                <h3><?= Html::encode('Set Dosen yang akan diasesori pada :'.$semester->tahunAjaran->tahun_ajaran .' Semester '.$semester->semester) ?></h3>
                <?php \yii\widgets\Pjax::begin(); ?>
                <?=Html::beginForm(['dosen-asesor', 'id'=>$model->penugasan_asesor_id, 'post']);?>
                <?= GridView::widget([
                    'dataProvider' => $dataProviderDosen,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'nama_dosen',
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'checkboxOptions' => function($model, $key, $index, $column) {
                                return ['value' => $model->dosen_id];
                            }
                        ],
                    ],
                ]); ?>
                <?=Html::submitButton('Set Dosen ', ['class' => 'btn btn-info',]);?>
                <?= Html::endForm();?> 
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
        </div>
    </div>

</div>
