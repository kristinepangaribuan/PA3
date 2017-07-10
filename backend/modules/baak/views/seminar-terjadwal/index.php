<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\SeminarTerjadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seminar Terjadwals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seminar-terjadwal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Seminar Terjadwal', ['seminar-terjadwal-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'seminar_terjadwal_id',
            'nama_seminar',
            'jlh_mhs_seminar',
            'semester_id',
            'header_dokumen_bukti_id',
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
                       $url = Url::to(['seminar-terjadwal-view', 'id' => $model->seminar_terjadwal_id]);
                      return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
                   },
                   'update' => function ($url, $model) {
                       $url = Url::to(['seminar-terjadwal-edit', 'id' => $model->seminar_terjadwal_id]);
                       return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'edit']);
                   },
                   'delete' => function ($url, $model) {
                       $url = Url::to(['seminar-terjadwal-del', 'id' => $model->seminar_terjadwal_id]);
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
