<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\models_search\ModulBahanAjarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modul Bahan Ajars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modul-bahan-ajar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Modul Bahan Ajar', ['modul-bahan-ajar-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'modul_bahan_ajar_id',
            'nama_modul',
            'semester_id',
            'header_dokumen_bukti_id',
            'tahapan_modul',
            // 'jlh_targer',
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
                       $url = Url::to(['modul-bahan-ajar-view', 'id' => $model->modul_bahan_ajar_id]);
                      return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
                   },
                   'update' => function ($url, $model) {
                       $url = Url::to(['modul-bahan-ajar-edit', 'id' => $model->modul_bahan_ajar_id]);
                       return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'edit']);
                   },
                   'delete' => function ($url, $model) {
                       $url = Url::to(['modul-bahan-ajar-del', 'id' => $model->modul_bahan_ajar_id]);
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
