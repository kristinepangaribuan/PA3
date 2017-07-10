<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\KegiatanPengabdianMasyarakat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-pengabdian-masyarakat-form">
<div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                            Kegiatan Pengabdian Masyarakat
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-12">
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'tahun_ajaran')->label('Tahun Ajaran')->textInput(['value' => $semester->tahunAjaran->tahun_ajaran,'maxlength' => true, 'disabled' => TRUE]) ?>

    <?= $form->field($model, 'semester_id')->label('Semester')->textInput(['value' => $semester->semester,'maxlength' => true, 'disabled' => TRUE]) ?>

    <?= $form->field($model, 'nama_kegiatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kategori_kegiatan')->dropDownList(['1' => 'Kegiatan Setara 50 Jam Kerja per Semester','2'=>'Penyuluhan/Penataran kepada Masyarakat','3'=>'Memberikan Jasa Konsultan sesuai dengan Kepakaran', ],['prompt'=> '-- Pilih Kategori Kegiatan --', 'id'=>'kategori_id']) ?>

    <?= $form->field($model, 'jlh_sks_pengabdian')->textInput(['placeholder' => 'Jumlah SKS Kegiatan Pengabdian Masyarakat', 'readonly'=>TRUE]) ?>

    <?php
        echo '<label class="control-label">Perorangan/Tim</label>';
        echo Select2::widget([
            'model'=>$model,
            'name' => 'dosen_id',
            'data' => $dataDosen,
            'value'=>(!$model->isNewRecord ? $resultDosen : $dosen),
            'options' => [
                'placeholder' => '-- Pilih Dosen --',
                'multiple' => true,
                'id' => 'dosen_id',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'disabled'=> TRUE,
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
     $('#kategori_id').change(function(){
        var idKategori = $(this).val();
        var ref_jlh_sks_id = 0;
        if(idKategori == 1){
          $('#dosen_id').attr("disabled", "disabled");
          ref_jlh_sks_id = 36;
        }else if(idKategori==2){
          $('#dosen_id').attr("disabled", "disabled");
          ref_jlh_sks_id = 37;
        }else if(idKategori==3){
          $('#dosen_id').removeAttr('disabled');
          ref_jlh_sks_id = 38;
        }
        $.get('index.php?r=baak/default/get-ref-sks', {ref_jlh_sks_id : ref_jlh_sks_id}, function(data){
            var data = JSON.parse(data);
            jlh_sks =  data.jlh_sks;
            $('#kegiatanpengabdianmasyarakat-jlh_sks_pengabdian').attr('value', (jlh_sks));
        })
   })
JS;
$this->registerJs($script);
