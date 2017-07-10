<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\DosenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="dosen-index">
    <div class="row">
    <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-header with-border">
                <div class="col-md-10">
                    <h2 class="box-title"><?= Html::encode($this->title) ?></h2>    
                </div>
               
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            
            <!-- /.box-body -->
<div class="col-md-12">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'dosen_id',
            'nama_dosen',
            'nidn',
            [
                'label' => 'FRK (SKS)',
                'format' => 'raw',
                'value' => function ($model)use($semester){
                    return \backend\modules\baak\models\Dosen::getAllFrkFed('Approve FED',$model->dosen_id, 0, $semester->semester_id);
                }
            ],
            [
                'label' => 'FED (SKS)',
                'format' => 'raw',
                'value' => function ($model)use ($semester){
                    return \backend\modules\baak\models\Dosen::getAllFrkFed('Approve FED', $model->dosen_id, 1, $semester->semester_id);
                }
            ],
        ],
    ]); ?>
            </div></div>
    </div>
        </div>
    </div>
</div>
