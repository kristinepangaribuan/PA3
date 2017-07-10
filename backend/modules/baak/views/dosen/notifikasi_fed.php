<?php
use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Notifikasi FED';
$this->params['breadcrumbs'][] = $this->title;
?>
<br><br>
  <div class="row">
        <div class="col-md-12">
          <div class="box box-default">
            <div class="box-header with-border">
              <i class="fa fa-warning"></i>

              <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php if(count($model) && $model->status == 'Reject FED'){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                <h3><?= \yii\bootstrap\Html::encode($model->pesan)?></h3><?= Html::a('View Fed', ['/baak/dosen/fed', 'id' => $dosen->dosen_id], ['class' => 'btn btn-warning']) ?>
              </div>
                <?php } else if(count($model) && $model->status == 'Approve FED'){?>
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i> Alert!</h4>
                <h3><?= \yii\bootstrap\Html::encode($model->pesan)?></h3>
              </div>
                <?php }else{ ?>
              <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Tidak ada notifikasi frk
              </div>
                <?php }?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->