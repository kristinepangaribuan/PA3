<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penelitians';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penelitian-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Penelitian', ['penelitian-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'penelitian_id',
            'jenis_penelitian',
            'judul_penelitian',
            'semester_id',
            'header_dokumen_bukti_id',
            // 'tahapan_penelitian',
            // 'jlh_target',
            // 'deleted',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                   'view' => function ($url, $model) {
                       $url = Url::to(['penelitian-view', 'id' => $model->penelitian_id]);
                      return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'browse']);
                   },
                   'update' => function ($url, $model) {
                       $url = Url::to(['penelitian-edit', 'id' => $model->penelitian_id]);
                       return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'edit']);
                   },
                   'delete' => function ($url, $model) {
                       $url = Url::to(['penelitian-del', 'id' => $model->penelitian_id]);
                       return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => 'del',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                           ]);
                   },        
                ],
            ],
        ],
    ]); ?>
</div>
