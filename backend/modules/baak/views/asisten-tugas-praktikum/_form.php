<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\AsistenTugasPraktikum */
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

<div class="asisten-tugas-praktikum-form">
    <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Asistensi Tugas Praktikum
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tahun_ajaran_id')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>
    
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
            'value'=>(!$model->isNewRecord ? $resultKelas : ''),
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
    
    <?php
        echo '<label class="control-label">Dosen/Tim Dosen</label>';
        echo Select2::widget([
            'model'=>$model,
            'name' => 'dosen_id',
            'data' => $dataDosen,
            'value'=>(!$model->isNewRecord ? $resultDosen : $dosen),
            'options' => [
                'placeholder' => '',
                'multiple' => true,
                'id' => 'dosen_id',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    <br>
    
    <?= $form->field($model, 'jlh_mhs_praktikum')->textInput(['placeholder' => 'Jumlah Mahasiswa yang Diajar', 'readonly'=>TRUE]) ?>
    
    <?= $form->field($model, 'jlh_sks_asistensi')->textInput(['placeholder' => 'Jumlah SKS', 'readonly'=>TRUE]) ?>

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
            var jlh_mhs = JSON.parse(data);
            $('#asistentugaspraktikum-jlh_mhs_praktikum').attr('value', (jlh_mhs));
            var jlh_sks=0;
            var ref_jlh_sks_id= 2;
            var total_persen_sks = 0;
            if(jlh_mhs > 25){
            //150%
            total_persen_sks = 1.5;
            }else if(jlh_mhs >0 && jlh_sks<=25){
                //100%
                total_persen_sks = 1;
            }
            $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
                var data = JSON.parse(data);
                jlh_sks =  data.jlh_sks;
                $('#asistentugaspraktikum-jlh_sks_asistensi').attr('value', (total_persen_sks * jlh_sks));
            })    
        });
   })
JS;
$this->registerJs($script);


