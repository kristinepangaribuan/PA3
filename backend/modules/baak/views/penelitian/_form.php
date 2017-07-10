<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\Penelitian */
/* @var $form yii\widgets\ActiveForm */
$js = '$(".dependent-input").on("change", function() {
	var value = $(this).val(),
		obj = $(this).attr("id"),
		next = $(this).attr("data-next");
	$.ajax({
		url: "' . Yii::$app->urlManager->createUrl('baak/penelitian/get-tahapan') . '",
		data: {value: value, obj: obj},
		type: "POST",
		success: function(data) {
			$("#" + next).html(data);
		}
	});
});';
$this->registerJs($js);

?> 

<div class="penelitian-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Penelitian
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="col-lg-6">
                
            <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>
            <?= $form->field($model, 'judul_penelitian')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'jenis_penelitian_id')->dropDownList(ArrayHelper::map($jenisPenelitian, 'tahapan_penelitian_id', 'tahapan_penelitian'), [
                'prompt' => 'Pilih Jenis Penelitian...',
                'class' => 'dependent-input form-control',
                'id' => 'tahapan_penelitian_id',
                'data-next' => 'tahapan_penelitian'
            ]) ?>
            <?= $form->field($model, 'tahapan_penelitian_id')->dropDownList(ArrayHelper::map($tahapanPenelitian, 'tahapan_penelitian_id', 'tahapan_penelitian'), [
            'prompt' => 'Pilih Tahapan Pencapaian...',
            'id' => 'tahapan_penelitian'
            ]) ?>
            
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>
                
            <!--?= $form->field($model, 'tim_dosen')->label('Apakah Pertim')->checkbox(['options'=>['id' => 'isTeamCheck']]) ?-->        
            <?= $form->field($model, 'jlh_target')->label('Jumlah Persentasi(%)')->textInput(['readonly'=>TRUE]) ?>
                <?php
            echo '<label class="control-label">Perorangan/Tim</label>';
//                var_dump($dosen);
//                die();
            echo Select2::widget([
                'model'=>$model,
                'name' => 'dosen_id',
                'data' => $dataDosen,
                'value'=>(!$model->isNewRecord ? $result : $dosen),
                'options' => [
                    'placeholder' => 'Pilih Dosen...',
                    'multiple' => true,
                    'id' => 'dosen_id',
                ],
                'pluginOptions' => [
                    'maximumSelectionLength'=> 4,
                    'allowClear' => true,
//                    'disabled' => true,
                ],
            ]);
            ?>
            <h5><i><b>Note : </b>yang mengentri data akan menjadi ketua dalam penelitian</h5></i>
            <br>
        <!--?= $form->field($model, 'jlh_sks_penelitian')->label('Jumlah Sks Penelitian(sks)')->textInput(['readonly'=>TRUE]) ?-->        
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Perbaharui', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
        </div>

    <?php ActiveForm::end(); ?>
        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
<?php
$script = <<< JS
//here you reight all your javascript stuff
     $('#tahapan_penelitian').change(function(){
        var tahapan_penelitian_id = $(this).val();
        $.get('index.php?r=baak/penelitian/get-persentasi', {tahapan_penelitian_id : tahapan_penelitian_id}, function(data){
            var model_tahapan = JSON.parse(data);
            $('#penelitian-jlh_target').attr('value', (model_tahapan.jlh_persentasi));
            var jlh_sks=0;
            var ref_jlh_sks_id = 0;
            $('#penelitian-tim_dosen').change(function(){
                if ($(this).is(':checked')){
                    $("#dosen_id").attr('disabled', false);
                    ref_jlh_sks_id = 14;
                    $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
                        var data = JSON.parse(data);
                        jlh_sks =  data.jlh_sks;
                        $('#penelitian-jlh_sks_penelitian').attr('value', (model_tahapan.jlh_persentasi * jlh_sks / 100));
                    })   
                }else{
                    $("#dosen_id").attr('disabled', true);
                    ref_jlh_sks_id = 15;
                    $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
                        var data = JSON.parse(data);
                        jlh_sks =  data.jlh_sks;
                        $('#penelitian-jlh_sks_penelitian').attr('value', (model_tahapan.jlh_persentasi * jlh_sks / 100));
                    })       
                }
           })   
            var ref_jlh_sks_id= 15;
            $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
                var data = JSON.parse(data);
                jlh_sks =  data.jlh_sks;
                $('#penelitian-jlh_sks_penelitian').attr('value', (model_tahapan.jlh_persentasi * jlh_sks / 100));
            })    
        });
   })
        
JS;
$this->registerJs($script);
?>