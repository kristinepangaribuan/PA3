<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\ModulBahanAjar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modul-bahan-ajar-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Modul Bahan Ajar
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>

        <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>
        
        <?= $form->field($model, 'nama_modul')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'tahapan_modul_id')->label('Tahapan Modul')->dropDownList(ArrayHelper::map($tahapanModul, 'tahapan_modul_id', 'tahapan_modul'), [
            'prompt' => '-- Pilih Tahapan Modul --',
            'id' => 'tahapan_modul'
            ]) ?>

    <?= $form->field($model, 'jlh_targer')->label('Jumlah Persentasi (%)')->textInput(['readonly'=>TRUE]) ?>
        <?= $form->field($model, 'jlh_sks_modul')->label('Jumlah SKS')->textInput(['readonly'=>TRUE]) ?>
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
                'placeholder' => 'Pilih Dosen...',
                'multiple' => true,
                'id' => 'dosen_id',
            ],
            'pluginOptions' => [
                'allowClear' => true,
//                'disabled' => true,
            ],
        ]);
        ?>
    <h5><i><b>Note : </b>yang mengentri data akan menjadi ketua dalam modul bahan ajar</h5></i>
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
     $('#tahapan_modul').change(function(){
        var tahapan_modul_id = $(this).val();
        $.get('index.php?r=baak/modul-bahan-ajar/get-persentasi', {tahapan_modul_id : tahapan_modul_id}, function(data){
            var model_tahapan = JSON.parse(data);
            $('#modulbahanajar-jlh_targer').attr('value', (model_tahapan.jlh_presentasi));
            var jlh_sks=0;
            var ref_jlh_sks_id = 0;
            $('#modulbahanajar-isteam').change(function(){
                if ($(this).is(':checked')){
                    $("#dosen_id").attr('disabled', false);
                    ref_jlh_sks_id = 20;
                    $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
                        var data = JSON.parse(data);
                        jlh_sks =  data.jlh_sks;
                        $('#modulbahanajar-jlh_sks_modul').attr('value', (model_tahapan.jlh_presentasi * jlh_sks / 100));
                    })   
                }else{
                    $("#dosen_id").attr('disabled', true);
                    ref_jlh_sks_id = 20;
                    $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
                        var data = JSON.parse(data);
                        jlh_sks =  data.jlh_sks;
                        $('#modulbahanajar-jlh_sks_modul').attr('value', (model_tahapan.jlh_presentasi * jlh_sks / 100));
                    })       
                }
           })   
            var ref_jlh_sks_id= 20;
            $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
                var data = JSON.parse(data);
                jlh_sks =  data.jlh_sks;
                $('#modulbahanajar-jlh_sks_modul').attr('value', (model_tahapan.jlh_presentasi * jlh_sks / 100));
            })    
        });
   })
        
JS;
$this->registerJs($script);
?>
