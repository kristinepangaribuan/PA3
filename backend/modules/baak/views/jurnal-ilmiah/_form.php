<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\JurnalIlmiah */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jurnal-ilmiah-form">

    <div class="dosen-matakuliah-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Jurnal Ilmiah
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>

    <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>
    <?= $form->field($model, 'judul_jurnal_ilmiah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'penerbit_jurnal_ilmiah')->dropDownList(['1' => 'Diterbitkan oleh Jurnal tidak terakreditasi', '2'=>'Diterbitkan oleh Jurnal terakreditasi',
        '3'=>'Diterbitkan oleh Jurnal terakreditasi internasional (dalam bahasa intenasional)'], ['prompt'=>'-- Pilih Penerbit --']) ?>
    <?= $form->field($model, 'jlh_sks_jurnal')->label('Jumlah SKS Jurnal Ilmiah')->textInput(['readonly'=>TRUE]) ?>
    <?php
        echo '<label class="control-label">Anggota</label>';
        echo Select2::widget([
            'model'=>$model,
            'name' => 'dosen_id',
            'data' => $dataDosen,
            'value'=>(!$model->isNewRecord ? $result : $dosen),
            'options' => [
                'placeholder' => '-- Pilih Dosen --',
                'multiple' => true
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

    <br>
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
     $('#jurnalilmiah-penerbit_jurnal_ilmiah').change(function(){
        var penerbit = $(this).val();
        var ref_jlh_sks_id =0;
        if(penerbit==1){
            ref_jlh_sks_id = 23;
        }else if(penerbit == 2){
            ref_jlh_sks_id = 24;
        }else{
            ref_jlh_sks_id = 25;
        }
        $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
            var data = JSON.parse(data);
            jlh_sks =  data.jlh_sks;
            $('#jurnalilmiah-jlh_sks_jurnal').attr('value', jlh_sks);
        })
   })      
JS;
$this->registerJs($script);
?>
