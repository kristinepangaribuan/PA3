<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>
<h3>List Dokemen Bukti</h3>
<?=
GridView::widget([
    'dataProvider' => $dataProviderDokumen,
    'columns' => [
        [
            'attribute' => 'header_detail_dokumen_bukti_id',
            'label' => 'Nama Dokumen Bukti',
            'format' => 'raw',
            'value' => function($model){
                return Html::a($model->headerDetailDokumenBukti->nama_header_detail_dokumen_bukti, ['/baak/jurnal-ilmiah/jurnal-ilmiah-download','id'=>$model->dokumen_bukti_jurnal_ilmiah_id], ['class' => '']);
            }
        ],
    ],
]);
?>
        
