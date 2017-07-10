<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenMatakuliah */
/* @var $form yii\widgets\ActiveForm */

$js = '$(".dependent-input").on("change", function() {
	var value = $(this).val(),
		obj = $(this).attr("id"),
		next = $(this).attr("data-next");
	$.ajax({
		url: "' . Yii::$app->urlManager->createUrl('baak/dosen-matakuliah/get') . '",
		data: {value: value, obj: obj},
		type: "POST",
		success: function(data) {
			$("#" + next).html(data);
		}
	});
});';
$this->registerJs($js);
?>
<div class="dosen-matakuliah-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Pengajaran
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
                            <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>
    
    <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>

    <?= $form->field($model, 'ref_kbk_id')->label('Program Studi')->dropDownList($prodi, [
        'prompt' => '-- Pilih Program Studi --',
        'id' => 'ref_kbk_id',
        'class' => 'dependent-input form-control',
        'data-next' => 'kuliah_id'
    ]) ?>
    
    <?= $form->field($model, 'kuliah_id')->dropDownList($dataKuliah, [
        'prompt' => '-- Pilih Matakuliah --',
        'id' => 'kuliah_id',
        'class' => 'dependent-input form-control',
        'data-next' => 'kelas_id'
    ]) ?>

    <?php
        echo '<label class="control-label">Kelas Yang Diajar</label>';
        echo Select2::widget([
            'model'=>$model,
            'name' => 'kelas_id',
            'data' => $dataKelas,
            'value'=>(!$model->isNewRecord ? $result : ''),
            'options' => [
                'placeholder' => '-- Pilih Kelas --',
                'multiple' => true,
                'id' => 'kelas_id',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    <br>

    <?= $form->field($model, 'jlh_mhs_matakuliah')->textInput(['placeholder' => 'Jumlah Mahasiswa yang Diajar', 'readonly'=>TRUE]) ?>
    
    <?= $form->field($model, 'jlh_tatap_muka_dosen')->dropDownList(
        [
            '1' => '1 x pertemuan',
            '2' => '2 x pertemuan',
            '3' => '3 x pertemuan',
            '4' => '4 x pertemuan',
            '5' => '5 x pertemuan',
            '6' => '6 x pertemuan',
            '7' => '7 x pertemuan',
            '8' => '8 x pertemuan',
            '9' => '9 x pertemuan',
            '10' => '10 x pertemuan',
            '11' => '11 x pertemuan',
            '12' => '12 x pertemuan',
            '13' => '13 x pertemuan',
            '14' => '14 x pertemuan'
        ], 
        ['prompt'=>'-- Pilih Jumlah Pertemuan --', 'id'=>'jlh_pertemuan']) 
    ?>

    <?= $form->field($model, 'jlh_sks_beban_kerja_dosen')->textInput(['placeholder' => 'Jumlah SKS', 'readonly'=>TRUE]) ?>
    
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
     $('#kelas_id').change(function(){
        var idKelas = $(this).val();
        $.get('index.php?r=baak/dosen-matakuliah/get-mahasiswa', {idKelas : idKelas}, function(data){
               var data = JSON.parse(data);
                $('#dosenmatakuliah-jlh_mhs_matakuliah').attr('value', (data));
           });
   })    
        
    $('#jlh_pertemuan').change(function(){
        var jlh_pertemuan = $(this).val();
        var total_sks = 0;
        var jlh_sks=0;
        var kuliah_id = $('#kuliah_id').val();
        var jlh_mhs = $('#dosenmatakuliah-jlh_mhs_matakuliah').val();
        var total_persen_sks = 0;
        if(jlh_mhs > 80){
            //200%
            total_persen_sks = 2;
        }else if(jlh_mhs >40 && jlh_sks<=80){
            //150%
            total_persen_sks = 1.5;
        }else{
            total_persen_sks = 1;
        }
        total_persen = jlh_pertemuan/14;
        $.get('index.php?r=baak/dosen-matakuliah/get-matakuliah', {kuliah_id : kuliah_id}, function(data){
            var data = JSON.parse(data);
            jlh_sks =  data.sks;
            var number = total_persen * total_persen_sks * jlh_sks;
            var new_number = number.toFixed(2);
            $('#dosenmatakuliah-jlh_sks_beban_kerja_dosen').attr('value', (new_number));
        });
   })
JS;
$this->registerJs($script);
?>
    
