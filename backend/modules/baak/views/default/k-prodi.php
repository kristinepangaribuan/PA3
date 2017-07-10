<div>
 <?php if($dataProviderPenelitian->totalCount >0){?>
    <h2><?= Html::encode('Penelitian') ?></h2>
    <?php \yii\widgets\Pjax::begin(); ?>
    <?=Html::beginForm(['process-penelitian'],'post');?>
    <?=Html::dropDownList('action','',['1'=>'Approve','0'=>'Reject'],['class'=>'dropdown',])?>
    <?=Html::submitButton('Send', ['class' => 'btn btn-info',]);?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderPenelitian,
//        'pagination'=>$dataProvider->pagination->pageSize=5,
//        'filterModel' => $searchModel,
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
                'template' => '{view}',
                'buttons' => [
                   'view' => function ($url, $model) {
                       $url = Url::to(['/baak/penelitian/penelitian-view/', 'id' => $model->penelitian_id]);
                      return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'browse']);
                   }, 
                ]
            ],
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model) {
                    return ['value' => $model->penelitian_id];
                }
            ],
        ],
    ]); ?>
    <?= Html::endForm();?> 
    <?php \yii\widgets\Pjax::end(); ?>
    <?php } ?>
</div>