<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\BimbinganKuliah */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bimbingan-kuliah-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Bimbingan Kuliah
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>
    
    <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>
    
    <?= $form->field($model, 'jenis_bimbingan_id')->label('Jenis Bimbingan')->dropDownList(yii\helpers\ArrayHelper::map($jenisBimbingan, 'jenis_bimbingan_id', 'jenis_bimbingan'), ['prompt' => '-- Pilih Jenis Bimbingan --']) ?>

    <?= $form->field($model, 'topik_bimbingan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jlh_mhs_bimbingan_kuliah')->textInput(['type'=>'number', 'min'=>'1']) ?>
                            
    <!--?= $form->field($model, 'jlh_sks_bimbingan_kuliah')->textInput(['disabled'=>TRUE]) ?-->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Perbaharui ', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
     </div>
                    </div>
                </div>
            </div>

</div>

<?php
$script = <<< JS
//here you reight all your javascript stuff   
    $('#jlh_mhs').change(function(){
        alert($(this).val());
        var jenis_bimbingan_id = $(#bimbingankuliah-jenis_bimbingan_id).val();
        var jlh_sks_default = 0;
        var jlh_mhs = $(this).val();
        if(jenis_bimbingan_id==4 || jenis_bimbingan_id==5 || jenis_bimbingan_id==6){
            //bimbingan TA/skripsi/karya tulis ilmiah
            if(jlh_mhs > 6){
                jlh_sks_default = 1;
            }else{
                jlh_sks_default = (jlh_mhs/6);
            }
            $('#jlh_sks_bimbingan_kuliah').attr('value', (jlh_sks_default));
        }else if(jenis_bimbingan_id==7){
            //bimbingan tesis
            if(jlh_mhs > 3){
                jlh_sks_default = 1;
            }else{
                jlh_sks_default = (jlh_mhs/3);
            }
            $('#jlh_sks_bimbingan_kuliah').attr('value', (jlh_sks_default));
        }else if(jenis_bimbingan_id==8){
            //bimbingan disertasi
            if(jlh_mhs > 2){
                jlh_sks_default = 1;
            }else{
                jlh_sks_default = (jlh_mhs/2);
            }
            $('#jlh_sks_bimbingan_kuliah').attr('value', (jlh_sks_default));
        }
   })   
JS;
$this->registerJs($script);
?>

