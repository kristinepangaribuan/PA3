<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

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
<h1>Matakuliah Has Kelas</h1>
<div class="col-md-8">
<?php $form = ActiveForm::begin(); ?>
    <?=
                                $form->field($model, 'kuliah_id')->dropDownList($matakuliah, [
                                    'prompt' => Yii::t('app', 'Pilih Matakuliah'),
                                    'id' => 'kuliah_id',
                                    'class' => 'dependent-input form-control',
                                    'data-next' => 'kelas_id'
                                ])
                                ?>
                                <?=
                                $form->field($model, 'kelas_id')->checkboxList($kelas,[
                                    'id' => 'kelas_id',
                                    'class' => 'dependent-input form-control',
                                ])
                                ?>
</div>
<?php ActiveForm::end(); ?>
