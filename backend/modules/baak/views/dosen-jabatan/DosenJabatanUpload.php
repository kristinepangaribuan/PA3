<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Upload Dokumen Bukti';
$this->params['breadcrumbs'][] = ['label' => 'Frk', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->dosen_jabatan_id, 'url' => ['dosen-jabatan-view', 'id' => $model->dosen_jabatan_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-upload-file">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([ 'enableClientValidation' => true,'options' => ['enctype'=>'multipart/form-data']]);?>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-4">
                <?= $form->field($modelDokumenBukti, 'nama_file')->textInput(['value' => $modelHeaderDetailDokumenBukti->nama_header_detail_dokumen_bukti, 'disable'=>TRUE])?>
              </div>
              <div class="col-md-8">
                <?= $form->field($modelDokumenBukti, 'file')->widget(FileInput::classname(), [
                    'options'=>['accept'=>'file/*'],
                    'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png', 'pdf', 'docx', 'doc', 'xmls']
                ],])
                ?>
              </div>
          </div>
      </div>
      <div class="modal-footer">
       <?php echo Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['dosen-jabatan-view', 'id'=>$model->dosen_jabatan_id], ['class'=>'btn btn-primary'])?>
      </div>
      <?php ActiveForm::end(); ?>
</div>