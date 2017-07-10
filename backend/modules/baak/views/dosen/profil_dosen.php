<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\Dosen */

$this->title = 'Profil '.$model->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>  
<div class="container">
    <h1><?=  Html::encode($this->title)?></h1>
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?= $directoryAsset ?>/img/user2-160x160.jpg" alt="User profile picture">

              <h3 class="profile-username text-center"><?=  Html::encode($model->nama_dosen)?></h3>

              <?= Html::a('Perbaharui Profil', ['update', 'id' => $model->dosen_id], ['class' => 'btn btn-primary btn-block']) ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Profil</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <!-- Post -->
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
//                        'dosen_id',
                        'nama_dosen',
                        'email:email',
                        'alamat',
                        'nidn',
                        [
                            'attribute'=>'golongan_kepangkatan_id',
                            'format' => 'raw',
                            'value' =>$model->golonganKepangkatan->nama_golongan_kepangkatan,
                        ],
//                        'golongan_kepangkatan_id',
//                        'pegawai_id',
                        'status_ikatan_kerja',
                        'aktif_start',
                        'aktif_end',
                        [
                            'attribute'=>'ref_kbk_id',
                            'label' => 'Program Studi',
                            'format' => 'raw',
                            'value' =>$model->refKbk->desc_ind,
                        ],
                        
                        'status',
                        's1',
                        's2',
                        's3',
                        'ilmu_yg_ditekuni',
                        'no_hp',
//                        'deleted_at',
//                        'deleted_by',
//                        'user_id',
//                        'ref_kbk_id',
                        
                    ],
                ]) ?>
                <!-- /.post -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
    
  </div>