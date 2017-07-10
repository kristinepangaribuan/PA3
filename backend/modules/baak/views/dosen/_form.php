<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\baak\models\GolonganKepangkatan;
use backend\modules\baak\models\Pegawai;
use backend\modules\baak\models\Prodi;
use backend\modules\baak\models\JabatanAkademik;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\Dosen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dosen-form">
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>    
            <div class="col-md-6">
                <?= $form->field($model, 'nama_dosen')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'nidn')->label('NIDN   ')->textInput(['maxlength' => true]) ?>

                <?=$form->field($model, 'aktif_start')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => 'Enter aktif start...',
                        //'value' => '0000-00-00',
                        ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);?>

                <?=$form->field($model, 'aktif_end')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => 'Enter aktif end...',
                        //'value' => '0000-00-00',
                        ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);?>
                
                <?=$form->field($model, 'tempat_tgl_lahir')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => 'Tempat Tanggal Lahir...',
                        //'value' => '0000-00-00',
                        ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);?>
                <?= $form->field($model, 'role_user_id')->label('Role User')->dropDownList(ArrayHelper::map(backend\modules\baak\models\RoleUser::find()->all(), 'role_user_id', 'role_user'), ['prompt'=>'Pilih Role Dosen...']) ?>
                <?= $form->field($model, 'ref_kbk_id')->label('Prodi')->dropDownList(ArrayHelper::map(backend\modules\baak\models\InstProdi::find()->all(), 'ref_kbk_id', 'desc_ind'), ['prompt'=>'Pilih Prodi...']) ?>

                <?= $form->field($model, 'golongan_kepangkatan_id')->label('Golongan Kepangkatan')->dropDownList(ArrayHelper::map(GolonganKepangkatan::find()->all(), 'golongan_kepangkatan_id', 'nama_golongan_kepangkatan'), ['prompt'=>'Pilih Golongan Kepangkatan...']) ?>
                
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'no_hp')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'status_ikatan_kerja')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'status')->label('Status Dosen')->dropDownList(['DS' => 'Dosen Biasa', 'PR'=>'Profesor', 'DT'=>'Dosen dengan tugas tambahan rektor', 'PT'=>'Profesor dengan tugas tambahan rektor']) ?>
                <?= $form->field($model, 's1')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 's2')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 's3')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'ilmu_yg_ditekuni')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'username')->textInput() ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'passwordconf')->label('Konfimasi Password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
