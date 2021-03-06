<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = $modelHeaderDetailDokumenBukti->nama_header_detail_dokumen_bukti;
$this->params['breadcrumbs'][] = ['label' => 'Form Rencana Kerja', 'url' => ['dosen/frk']];
$this->params['breadcrumbs'][] = ['label' => $model->kuliah->nama_kul_ind, 'url' => ['dosen-matakuliah-view', 'id' => $model->dosen_matakuliah_id]];
$this->params['breadcrumbs'][] = 'Unggah Dokumen Bukti';
?>
<div class="form-upload-file">
    <h3>Unggah Dokumen Bukti <?= Html::encode($model->kuliah->nama_kul_ind) ?></h3>
    <h4><?= Html::encode($this->title) ?> </h4>
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
       <?php echo Html::submitButton(Yii::t('app', 'Unggah'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['dosen-matakuliah-view', 'id'=>$model->dosen_matakuliah_id], ['class'=>'btn btn-primary'])?>
      </div>
      <?php ActiveForm::end(); ?>
</div>