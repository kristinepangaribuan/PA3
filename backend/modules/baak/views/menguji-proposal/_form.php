<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\MengujiProposal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menguji-proposal-form">
<div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Menguji Proposal
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>
    
    <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>

    <?= $form->field($model, 'jenis_proposal_id')->label('Jenis Proposal')->dropDownList(yii\helpers\ArrayHelper::map($jenisProposal, 'jenis_proposal_id', 'jenis_proposal'), ['prompt' => '-- Pilih Jenis Proposal --']) ?>

    <?= $form->field($model, 'judul_proposal')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'jlh_mhs_proposal')->label('Jumlah Mahasiswa')->textInput(['type' => 'number', 'min'=>1]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Perbaharui', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
                </div>
                    </div>
                </div>
            </div>
</div>
