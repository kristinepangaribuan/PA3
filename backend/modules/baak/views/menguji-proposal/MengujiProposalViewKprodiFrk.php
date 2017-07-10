<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\tabs\TabsX;
/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\MengujiProposal */

$this->title = 'Menguji Proposal';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="menguji-proposal-view">
<div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="false" class="collapsed">
                             <?= Html::encode($this->title) ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'judul_proposal',
            'jlh_mhs_proposal',
            'jlh_sks_menguji_proposal',
            [
                'attribute'=>'jenis_proposal_id',
                'format' =>'raw',
                'value'=>$model->jenisProposal->jenis_proposal,
            ],
            [
                'attribute'=>'tahun_ajaran',
                'label'=>'Tahun Ajaran',
                'format' =>'raw',
                'value'=>$model->semester->tahunAjaran->tahun_ajaran,
            ],
            [
                'attribute'=>'semester_id',
                'format' =>'raw',
                'value'=>$model->semester->semester,
            ],
        ],
    ]) ?>
    </div>
                        </div>
                    </div>
                </div>

<?php
    $items = [
        [
            'label' => '<i class="glyphicon glyphicon-home"></i> Dokumen Bukti',
            'content' => GridView::widget([
                'dataProvider' => $dataProviderDokumenBukti,
//        'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nama_header_detail_dokumen_bukti',
                    [
                        'attribute' => 'Action',
                        'format' => 'raw',
                        'value' => function ($data)use($model) {
                            $dokumen = \backend\modules\baak\models\DokumenBuktiMengujiProposal::find()
                                            ->where(['menguji_proposal_id' => $model->menguji_proposal_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $data['header_detail_dokumen_bukti_id']])->one();
                            if ($dokumen['nama_file'] != NULL) {
                                return Html::a($dokumen['nama_file'], ['menguji-proposal-download','id'=>$dokumen['dokumen_bukti_menguji_proposal_id']], ['class' => '']);
                            }else{
                                return 'Dokumen Belum Diupload';
                            }
                        },
                            ],
                        ],
                    ]),
                    'active' => true
                ],
            ];
            ?>
<div class="panel box box-primary">
            <div id="collapse3" class="panel-collapse collapse in">
                <div class="box-body">
                    <div class="col-md-12">
            <?=
            TabsX::widget([
                'items' => $items,
                'position' => TabsX::POS_ABOVE,
                'align' => TabsX::ALIGN_LEFT,
                'bordered' => true,
                'encodeLabels' => false
            ]);
            ?>
</div>
                </div>
            </div>
        </div>
</div>