<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\tabs\TabsX;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Daftar Dosen yang mengajukan Form Evaluasi Diri' ;
$this->params['breadcrumbs'][] = $this->title;
?>
 <?php
    $items = [
        [
            'label' => '<i class="glyphicon glyphicon-user"></i> Dosen yang merequest',
            'content' => GridView::widget([
                        'dataProvider' => $dataProviderStatusFED,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'dosen_id',
                                'label' => 'Nama Dosen',
                                'format' => 'raw',
                                'value' => function ($model){
                                    return $model->nama_dosen;
                                }
                                ],
                                        [
//                                'attribute' => 'dosen_id',
                                'label' => 'Rincian',
                                'format' => 'raw',
                                'value' => function ($model){
                                    return Html::a('Lihat FED', ['fed-dosen-request', 'id'=> $model->dosen_id, 'status'=> 'Pengajuan FED'], ['class' => 'btn btn-primary']);
                                }
                                ],],
                                    ]),
                    'active' => true
                ],
                                [
                    'label' => '<i class="glyphicon glyphicon-user"></i> Dosen yang telah Diapprove',
                    'content' => GridView::widget([
                        'dataProvider' => $dataProviderStatusFEDApprove,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'dosen_id',
                                'label' => 'Nama Dosen',
                                'format' => 'raw',
                                'value' => function ($model){
                                    return $model->nama_dosen;
                                }
                                ],
                                        [
//                                'attribute' => 'dosen_id',
                                'label' => 'Action',
                                'format' => 'raw',
                                'value' => function ($model){
                                    return Html::a('Lihat FED', ['fed-dosen-request', 'id'=> $model->dosen_id, 'status'=> 'Approve FED'], ['class' => 'btn btn-primary']);
                                }
                                ],],
                                    ]),
                ],
            ];
            ?>

 <h2><?= Html::encode($this->title) ?></h2>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode('List dosen') ?></h3>
                    <div class="box-tools pull-right">
                        
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                 <div class="box-body">
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
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
