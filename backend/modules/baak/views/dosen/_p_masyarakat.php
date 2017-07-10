<?php
use yii\helpers\Html;
?>
<div class="baak-default-index">
    <h1><?= Html::encode('Bidang Pengabdian Masyarakat') ?></h1>
    <div class="row">
        <div class="col-md-3">
            <?= $this->render('_menu',['item'=>'_p_masyarakat','dosen'=>$dosen,'total_sks'=>$total_sks]) ?>
        </div>
        <div class="col-md-9">
            <div class="box box-solid">
                <div class="box-body">
                        <?= $this->render('frk_saya_p_masyarakat', [
                            'dataProviderKegiatanPengabdian'=>$dataProviderKegiatanPengabdian,
                            'dosen'=>$dosen,
                        ]) ?>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
