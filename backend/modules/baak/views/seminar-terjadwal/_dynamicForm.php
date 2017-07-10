<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $modelCustomer app\modules\yii2extensions\models\Customer */
/* @var $modelsAddress app\modules\yii2extensions\models\Address */

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Anggota: " + (index + 1))
    });
});


jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Anggota: " + (index + 1))
    });
});
';
$this->registerJs($js);
?>
<div class="penelitian-form">
<?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    
    <?= $form->field($modelSeminarTerjadwal, 'nama_seminar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelSeminarTerjadwal, 'jlh_mhs_seminar')->textInput() ?>

    <?= $form->field($modelSeminarTerjadwal, 'tahun_ajaran')->label('Tahun Ajaran')->dropDownList([yii\helpers\ArrayHelper::map($tahunAjaran, 'tahun_ajaran_id', 'tahun_ajaran')], ['prompt' => '-Select Tahun Ajaran-']) ?>

    <?= $form->field($modelSeminarTerjadwal, 'semester_id')->label('Semester')->dropDownList(['1'=>'Gasal', '2'=>'Genap'], ['prompt'=>'Select']) ?>
    
    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>

<?php
DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 4, // the maximum times, an element can be cloned (default 999)
    'min' => 0, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => $modelsDosenSeminar[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'dosen_id',
    ],
]);
?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-envelope"></i> Dosen Pembimbing
            <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Tambah Dosen Pembimbing</button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body container-items"><!-- widgetContainer -->
    <?php foreach ($modelsDosenSeminar as $index => $modelDosenSeminar): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <span class="panel-title-address">Dosen Pembimbing: <?= ($index + 1) ?></span>
                        <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                    <?php
                    // necessary for update action.
                    if (!$modelDosenSeminar->isNewRecord) {
                        echo Html::activeHiddenInput($modelDosenSeminar, "[{$index}]dosen_seminar_terjadwal_id");
                    }
//                    $dosen = backend\modules\baak\models\Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
//                    $rows = (new \yii\db\Query())
//                            ->select(['dosen_id', 'nama_dosen'])
//                            ->from('baak_dosen')
//                            ->where(['NOT IN', 'dosen_id', [$dosen['dosen_id']]])
//                            ->all();
                    $rows = backend\modules\baak\models\Dosen::find()
//                            ->where(['NOT IN', 'dosen_id', [$dosen['dosen_id']]])
                            ->all();  
                    ?>
                        <div class="row">
                            
                    <?= $form->field($modelDosenSeminar, "[{$index}]dosen_id")->dropDownList(yii\helpers\ArrayHelper::map($rows, 'dosen_id', 'nama_dosen'))?>
                            
                           
                        </div><!-- end:row -->
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
                            <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
                            <?= Html::submitButton($modelDosenSeminar->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>