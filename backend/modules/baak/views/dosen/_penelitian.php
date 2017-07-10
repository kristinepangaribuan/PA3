<?php
use yii\helpers\Html;
?>
<div class="baak-default-index">
    <h1><?= Html::encode('Bidang Penelitian') ?></h1>
    <div class="row">
        <div class="col-md-3">
            <?= $this->render('_menu', ['item'=>'penelitian','dosen'=>$dosen,'total_sks'=>$total_sks]) ?>
        </div>
        <div class="col-md-9">
            <div class="box box-solid">
                <div class="box-body">
                        <?= $this->render('frk_saya_penelitian', [
                            'dataProviderPenelitian'=>$dataProviderPenelitian,
//                            'dataProviderNaskah'=>$dataProviderNaskah,
//                            'dataProviderMediaMassa'=>$dataProviderMediaMassa,
                            'dataProviderModul'=>$dataProviderModul,
//                            'dataProviderHakPaten'=>$dataProviderHakPaten,
                            'dataProviderMakalahSeminar'=>$dataProviderMakalahSeminar,
//                            'dataProviderOrasiIlmiah' => $dataProviderOrasiIlmiah,
                            'dataProviderJurnalIlmiah' => $dataProviderJurnalIlmiah,
                            'dosen'=>$dosen,
                            
                        ]) ?>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
