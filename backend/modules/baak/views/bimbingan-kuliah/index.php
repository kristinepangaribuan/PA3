<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\BimbinganKuliahSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bimbingan Kuliahs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bimbingan-kuliah-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Bimbingan Kuliah', ['bimbingan-kuliah-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bimbingan_kuliah_id',
            'topik_bimbingan',
            'jlh_mhs_bimbingan_kuliah',
            'jenis_bimbingan_id',
            'semester_id',
            // 'dosen_id',
            // 'jlh_sks_bimbingan_kuliah',
            // 'deleted',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',

//            ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                   'view' => function ($url, $model) {
                       $url = Url::to(['bimbingan-kuliah-view', 'id' => $model->bimbingan_kuliah_id]);
                      return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
                   },
                   'update' => function ($url, $model) {
                       $url = Url::to(['bimbingan-kuliah-edit', 'id' => $model->bimbingan_kuliah_id]);
                       return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'edit']);
                   },
                   'delete' => function ($url, $model) {
                       $url = Url::to(['bimbingan-kuliah-del', 'id' => $model->bimbingan_kuliah_id]);
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
