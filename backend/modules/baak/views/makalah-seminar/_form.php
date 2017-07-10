<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\MakalahSeminar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="makalah-seminar-form">
<div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Makalah Seminar
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">

    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>
    
    
    
    <?= $form->field($model, 'judul_makalah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jlh_sks_makalah_seminar')->label('Jumlah SKS Makalah Seminar')->textInput(['readonly'=>TRUE]) ?>
                </div>
            <div class="col-md-6">
                <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>
    <?= $form->field($model, 'tingkatan_makalah')->dropDownList(['1'=>'Tingkat Regional/ minimal fakultas', '2'=>'Tingkat nasional', '3'=>'Tingkat internasional'], ['prompt'=>'Pilih Tingkatan Makalah...']) ?>
           
    <!--?= $form->field($model, 'isTeam')->label('Apakah Pertim')->checkbox(['options'=>['id' => 'isTeamCheck']]) ?-->        
    <?php
        echo '<label class="control-label">Perorangan/Tim</label>';
        echo Select2::widget([
            'model'=>$model,
//            'attribute'=>'dosen_id',
            'name' => 'dosen_id',
//            'attribute'=>'dosen_id',
            'data' => $dataDosen,
            'value'=>(!$model->isNewRecord ? $result : $dosen),
            'options' => [
                'placeholder' => '-- Pilih Dosen --',
                'multiple' => true,
                'id' => 'dosen_id',
            ],
            'pluginOptions' => [
                'allowClear' => true,
//                'disabled' => true,
            ],
        ]);
        ?>
    <br>
    <h5><i><b>Note : </b>yang mengentri data akan menjadi ketua dalam makalah seminar</h5></i>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Perbaharui', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
            </div>
                    </div>
                </div>
            </div>
        </div></div>
</div>

<?php
$script = <<< JS
//here you reight all your javascript stuff
     $('#makalahseminar-tingkatan_makalah').change(function(){
        var tingkatan_makalah = $(this).val();
        var ref_jlh_sks_id =0;
        if(tingkatan_makalah==1){
            ref_jlh_sks_id = 33;
        }else if(tingkatan_makalah == 2){
            ref_jlh_sks_id = 34;
        }else{
            ref_jlh_sks_id = 35;
        }
        $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
            var data = JSON.parse(data);
            jlh_sks =  data.jlh_sks;
            $('#makalahseminar-jlh_sks_makalah_seminar').attr('value', jlh_sks);
        })
   })    
   $('#makalahseminar-isteam').change(function(){
        if ($(this).is(':checked')){
            $("#dosen_id").attr('disabled', false);   
        }else{
            $("#dosen_id").attr('disabled', true);       
        }
    })        
JS;
$this->registerJs($script);
?>
