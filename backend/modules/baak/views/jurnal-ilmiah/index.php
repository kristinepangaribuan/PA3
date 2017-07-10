<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\JurnalIlmiahSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jurnal Ilmiahs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jurnal-ilmiah-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jurnal Ilmiah', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'jurnal_ilmiah_id',
            'judul_jurnal_ilmiah',
            'penerbit_jurnal_ilmiah',
            'semester_id',
            'header_dokumen_bukti_id',
            // 'tahapan_jurnal_ilmiah',
            // 'jlh_target',
            // 'deleted',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'deleted_at',
            // 'deleted_by',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
