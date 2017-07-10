<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\SeminarTerjadwal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seminar-terjadwal-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Seminar Terjadwal
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?php $form = ActiveForm::begin([]); ?>
    
    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>
    
    <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>


    <?= $form->field($model, 'nama_seminar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jlh_mhs_seminar')->textInput(['type'=>'number', 'min'=>'1']) ?>

    <?= $form->field($model, 'jlh_sks_seminar')->textInput(['disabled' => TRUE]) ?>                        
    
    <?php
        echo '<label class="control-label">Dosen Pembimbing</label>';
        echo Select2::widget([
            'model'=>$model,
            'name' => 'dosen_id',
            'data' => $dataDosen,
            'value'=>(!$model->isNewRecord ? $result : $dosen),
            'options' => [
                'placeholder' => '-- Pilih Dosen --',
                'multiple' => true,
                'id' => 'dosen_id',
            ],
            'pluginOptions' => [
                'maximumSelectionLength'=> 4,
                'allowClear' => true,
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
        $('#seminarterjadwal-jlh_mhs_seminar').change(function(){
        var jlh_mhs = $(this).val();
        if (jlh_mhs>25){
            $('#seminarterjadwal-jlh_sks_seminar').attr('value', (2));
        }else{
            $('#seminarterjadwal-jlh_sks_seminar').attr('value', (1));
        }
   })   
JS;
$this->registerJs($script);
?>
    

