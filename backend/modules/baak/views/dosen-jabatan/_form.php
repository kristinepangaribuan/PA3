<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenJabatan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dosen-jabatan-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Jabatan Dosen
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?php $form = ActiveForm::begin(); ?>    

    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>

    <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>
    <?= $form->field($model, 'struktur_jabatan_id')->dropDownList($dataJabatan, [
        'prompt' => '-- Pilih Jabatan --',
        'id' => 'jabatan_id',
    ]) ?>

    <?= $form->field($model, 'jlh_sks_beban_kerja_dosen')->textInput((['placeholder' => 'Jumlah SKS', 'readonly'=>TRUE])) 
    ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Perbaharui', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
     $('#jabatan_id').change(function(){
        var idJabatan = $(this).val();
        $.get('index.php?r=baak/dosen-jabatan/get-jabatan', {idJabatan : idJabatan}, function(data){
            var data = JSON.parse(data);
            //alert(data);  
            var jlh_sks=data.jlh_sks;
            $('#dosenjabatan-jlh_sks_beban_kerja_dosen').attr('value', (jlh_sks));    
        });
   })
JS;
$this->registerJs($script);
    

