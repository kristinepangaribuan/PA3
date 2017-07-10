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
$this->title = 'Daftar dosen yang Mengajukan Form Rencana Kerja' ;
$this->params['breadcrumbs'][] = $this->title;
?>
 <?php
    $items = [
        [
            'label' => '<i class="glyphicon glyphicon-user"></i> Daftar Dosen yang Merequest',
            'content' => GridView::widget([
                        'dataProvider' => $dataProviderStatusFRK,
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
                                    return Html::a('Lihat FRK', ['frk-dosen-request', 'id'=> $model->dosen_id, 'status'=> 'Pengajuan FRK'], ['class' => 'btn btn-primary']);
                                }
                                ],],
                                    ]),
                    'active' => true
                ],
                                [
                    'label' => '<i class="glyphicon glyphicon-user"></i> Daftar Dosen yang Diapprove',
                    'content' => GridView::widget([
                        'dataProvider' => $dataProviderStatusFRKApprove,
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
                                    return Html::a('Lihat FRK', ['frk-dosen-request', 'id'=> $model->dosen_id, 'status'=> 'Approve FRK'], ['class' => 'btn btn-primary']);
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
