<?php
use kartik\sidenav\SideNav;
use yii\helpers\Url;
$type = SideNav::TYPE_PRIMARY;
echo SideNav::widget([
    'type' => $type,
    'encodeLabels' => false,
//    'heading' => $heading,
    'items' => [
        ['label' => 'Pengajaran', 'icon' => 'file', 'url' => Url::to(['/baak/dosen/frk-saya-pengajaran']), 'active' => ($item == 'pengajaran')],
        ['label' => '<span class="pull-right badge"></span>Penelitian', 'icon' => 'file', 'url' => Url::to(['/baak/dosen/frk-saya-penelitian']), 'active' => ($item == 'penelitian')],
        ['label' => 'Pengabdian Masyarakat', 'icon' => 'tags', 'url' => Url::to(['/baak/dosen/frk-saya-pengabdian-masyarakat']), 'active' => ($item == '_p_masyarakat')],
        ['label' => 'Pengembangan Instansi', 'icon' => 'user', 'url' => Url::to(['/baak/dosen/frk-saya-pengembangan-instansi']), 'active' => ($item == '_p_instansi')],
    ],
]);

$status  = backend\modules\baak\models\Dosen::getStatusFrk($dosen['dosen_id']);
if($status){
    if($total_sks>0){
        echo '<center>'.\yii\helpers\Html::a('Submit Semua FRK', ['/baak/dosen/submit-all-frk', 'status' => 'Pengajuan FRK'], [
    'class' => 'btn btn-primary',
    'data' => [
                'confirm' => 'Apakah Anda yakin ingin mensubmit semua FRK Anda?',
                'method' => 'post',
            ],
    ]).'</center>';
    }
}

?>

