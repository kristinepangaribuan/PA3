<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\MengujiProposalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menguji Proposals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menguji-proposal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Menguji Proposal', ['menguji-proposal-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'menguji_proposal_id',
            'judul_proposal',
            'jlh_mhs_proposal',
            'jlh_sks_menguji_proposal',
            // 'dosen_id',
            // 'semester_id',
            // 'header_dokumen_bukti_id',
            // 'jenis_proposal_id',
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
                       $url = Url::to(['menguji-proposal-view', 'id' => $model->menguji_proposal_id]);
                      return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'view']);
                   },
                   'update' => function ($url, $model) {
                       $url = Url::to(['menguji-proposal-edit', 'id' => $model->menguji_proposal_id]);
                       return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'edit']);
                   },
                   'delete' => function ($url, $model) {
                       $url = Url::to(['menguji-proposal-del', 'id' => $model->menguji_proposal_id]);
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
