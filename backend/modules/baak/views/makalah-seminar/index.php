<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\MakalahSeminarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Makalah Seminars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="makalah-seminar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Makalah Seminar', ['makalah-seminar-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'makalah_seminar_id',
            'judul_makalah',
            'tingkatan_makalah',
            'semester_id',
            'header_dokumen_bukti_id',
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
