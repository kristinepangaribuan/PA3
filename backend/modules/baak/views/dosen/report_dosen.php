<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baak\models\search\DosenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LAPORAN BEBAN KERJA DAN EVALUASI DOSEN SEMESTER II TAHUN 2016/2017';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-index">
    <div class="box box-solid">
        <div class="col-md-12">
            <div class="box-header with-border" style="text-align: center">
                <h3><b><?= Html::encode($this->title)?></b></h3>
            </div>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">
            <div class="col-md-12">
                <h3><b><?= Html::encode('I. IDENTITAS')?></b></h3>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => 'Nama',
                            'format' => 'raw',
                            'value' => $model->nama_dosen,
                        ],
                        [
                            'label' => 'No. Sertifikat',
                            'format' => 'raw',
                            'value' => 'xxx-xxx-xxx-xxx',
                        ],
                        [
                            'label' => 'Perguruan Tinggi',
                            'format' => 'raw',
                            'value' => 'INSTITUT TEKNOLOGI DEL',
                        ],
                        [
                            'label' => 'Status',
                            'format' => 'raw',
                            'value' =>  function($model){
                                if($model->status != NULL)
                                    return $model->status;
                                else
                                    return 'Not Set';
                            },
                        ],
                        [
                            'label' => 'Alamat Perg. Tinggi',
                            'format' => 'raw',
                            'value' => function (){
                                return 'Jl. Sisingamangaraja, Sitoluama'.'<br>'. 'Laguboti, Toba Samosir' .'<br>'. 'Sumatera Utara, Indonesia';
                            }
                        ],
                        [
                            'label' => 'Fakultas/Departemen',
                            'format' => 'raw',
                            'value' => 'FTIE',
                        ], 
                        [
                            'label' => 'Jurusan/Prodi',
                            'format' => 'raw',
                            'value' => $model->refKbk->desc_ind,
                        ],
                        [
                            'attribute'=>'golongan_kepangkatan_id',
                            'label' => 'Pangkat/Golongan',
                            'format' => 'raw',
                            'value' =>$model->golonganKepangkatan->nama_golongan_kepangkatan,
                        ],
                        [
                            'label' => 'Tempat, Tgl Lahir',
                            'format' => 'raw',
                            'value' =>  function($model){
                                if($model->tempat_tgl_lahir != NULL)
                                    return $model->tempat_tgl_lahir;
                                else
                                    return 'Not Set';
                            },
                        ],
                        [
                            'label' => 'S1',
                            'format' => 'raw',
                            'value' =>  function($model){
                                if($model->s1 != NULL)
                                    return $model->s1;
                                else
                                    return 'Not Set';
                            },
                        ],
                        [
                            'label' => 'S2',
                            'format' => 'raw',
                            'value' =>  function($model){
                                if($model->s2 != NULL)
                                    return $model->s2;
                                else
                                    return 'Not Set';
                            },
                        ],
                        [
                            'label' => 'S3',
                            'format' => 'raw',
                            'value' =>  function($model){
                                if($model->s3 != NULL)
                                    return $model->s3;
                                else
                                    return 'Not Set';
                            },
                        ],         
                        [
                            'label' => 'Ilmi yang ditekuni',
                            'format' => 'raw',
                            'value' =>  function($model){
                                if($model->ilmu_yg_ditekuni != NULL)
                                    return $model->ilmu_yg_ditekuni;
                                else
                                    return 'Not Set';
                            },
                        ],
                        [
                            'label' => 'No. HP',
                            'format' => 'raw',
                            'value' =>  function($model){
                                if($model->no_hp != NULL)
                                    return $model->no_hp;
                                else
                                    return 'Not Set';
                            },
                        ],        
                    ],
                ]) ?>
                
                <br>
                <h3><b><?= Html::encode('II. BIDANG PENDIDIKAN')?></b></h3>
                <table style="width:120%; border: 2px solid black;">
                    <tr>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">No</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Jenis Kegiatan</th>
                        <th colspan="2" style="text-align:center; border: 1px solid black;">Beban Kerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Masa Pelaksanaan Tugas</th>
                        <th colspan="4" style="text-align:center; border: 1px solid black;">Kinerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Penilaian/Rekomendasi Asesor</th>
                    </tr>
                <tr>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">Bukti Penugasan</th>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">SKS</th>
                    <th rowspan="2" colspan="2" style="text-align:center; border: 1px solid black;">Bukti Dokumen</th>
                    <th colspan="2" style="text-align:center; border: 1px solid black;">Capaian</th>
                </tr>
                <tr>
                    <th style="text-align:center; border: 1px solid black;">%</th>
                    <th style="text-align:center; border: 1px solid black;">SKS</th>
                </tr>
                <?php 
                $i=0;
                $jlh_beban_kerja = 0;
                $jlh_kinerja = 0;
                if(count($dataProviderDosenMatkuliah) > 0){ 
                    foreach ($dataProviderDosenMatkuliah as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i ?></td>
                            <td style="border: 1px solid black;"> <?php $matakkuliah = backend\modules\baak\models\Kuliah::getNamaMatakuliah($data['kuliah_id']); echo 'Mengajar '.$matakkuliah['nama_kul_ind'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?= $data['jlh_sks_beban_kerja_dosen'];?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiDosenMatakuliah::getAllDokumenBukti($data['dosen_matakuliah_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja += $data['jlh_sks_beban_kerja_dosen'];?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $data['jlh_sks_beban_kerja_dosen']?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja += $data['jlh_sks_beban_kerja_dosen'];}} ?>
                        
                <?php
                if(count($dataProviderAsistenTugas) > 0){ 
                    foreach ($dataProviderAsistenTugas as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i ?></td>
                            <td style="border: 1px solid black;"> <?php $matakkuliah = backend\modules\baak\models\Kuliah::getNamaMatakuliah($data['kuliah_id']); echo 'Asisten Tugas Praktikum: '.$matakkuliah['nama_kul_ind'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks =  \backend\modules\baak\models\AsistenTugasPraktikum::getJlhSksDosen($data['asisten_tugas_praktikum_id'], $model->dosen_id); echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiAsisten::getAllDokumenBukti($data['asisten_tugas_praktikum_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja += $jlh_sks; ?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{ ?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja += $jlh_sks; }} ?>
                        
                <?php
                if(count($dataProviderBimbinganKuliah) > 0){ 
                    foreach ($dataProviderBimbinganKuliah as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i ?></td>
                            <td style="border: 1px solid black;"> <?= 'Melakukan Bimbingan: '.$data['topik_bimbingan'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?= $data['jlh_sks_bimbingan_kuliah'] ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiBimbinganKuliah::getAllDokumenBukti($data['bimbingan_kuliah_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja += $data['jlh_sks_bimbingan_kuliah']; ?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $data['jlh_sks_bimbingan_kuliah'] ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja += $data['jlh_sks_bimbingan_kuliah']; }} ?> 
                        
                <?php
                if(count($dataProviderSeminarTerjadwal) > 0){ 
                    foreach ($dataProviderSeminarTerjadwal as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i ?></td>
                            <td style="border: 1px solid black;"> <?=  'Melakukan Seminar: '.$data['nama_seminar'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks = \backend\modules\baak\models\SeminarTerjadwal::getJlhSksDosen($data['seminar_terjadwal_id'], $model->dosen_id); echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiSeminarTerjadwal::getAllDokumenBukti($data['seminar_terjadwal_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja += $jlh_sks; ?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja +=$jlh_sks;  }} ?>
                        
                <?php
                if(count($dataProviderMengujiProposal) > 0){ 
                    foreach ($dataProviderMengujiProposal as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i ?></td>
                            <td style="border: 1px solid black;"> <?= 'Menguji Proposal: '.$data['judul_proposal'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?= $data['jlh_sks_menguji_proposal']?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiMengujiProposal::getAllDokumenBukti($data['menguji_proposal_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja += $data['jlh_sks_menguji_proposal'];?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $data['jlh_sks_menguji_proposal']?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja += $data['jlh_sks_menguji_proposal']; }} ?>
                        
                        <tr>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black; text-align: center">Jumlah Beban Kerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_beban_kerja?></td>
                            <td colspan="3" style="border: 1px solid black; text-align: center">Jumlah Kinerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_kinerja?></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
              </table>
                    
              <br>
                <h3><b><?= Html::encode('III. BIDANG PENELITIAN DAN PENGEMBANGAN ILMU')?></b></h3>
                <table style="width:120%; border: 2px solid black;">
                    <tr>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">No</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Jenis Kegiatan</th>
                        <th colspan="2" style="text-align:center; border: 1px solid black;">Beban Kerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Masa Pelaksanaan Tugas</th>
                        <th colspan="4" style="text-align:center; border: 1px solid black;">Kinerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Penilaian/Rekomendasi Asesor</th>
                    </tr>
                <tr>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">Bukti Penugasan</th>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">SKS</th>
                    <th rowspan="2" colspan="2" style="text-align:center; border: 1px solid black;">Bukti Dokumen</th>
                    <th colspan="2" style="text-align:center; border: 1px solid black;">Capaian</th>
                </tr>
                <tr>
                    <th style="text-align:center; border: 1px solid black;">%</th>
                    <th style="text-align:center; border: 1px solid black;">SKS</th>
                </tr>
                <?php 
                $i_penelitian=0;
                $jlh_beban_kerja_penelitian = 0;
                $jlh_kinerja_penelitian = 0;
                if(count($dataProviderPenelitian) > 0){ 
                    foreach ($dataProviderPenelitian as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i_penelitian ?></td>
                            <td style="border: 1px solid black;"> <?= 'Melakukan Penelitian: '.$data['judul_penelitian'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks = \backend\modules\baak\models\Penelitian::getJlhSksDosen($data['penelitian_id'], $model->dosen_id); echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiPenelitian::getAllDokumenBukti($data['penelitian_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja_penelitian += $jlh_sks;?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja_penelitian += $jlh_sks;}} ?>
                        
                <?php
                if(count($dataProviderModul) > 0){ 
                    foreach ($dataProviderModul as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i_penelitian ?></td>
                            <td style="border: 1px solid black;"> <?= 'Mengembangkan Bahan Modul Bahan Ajar:' .$data['nama_modul']?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks = \backend\modules\baak\models\ModulBahanAjar::getJlhSksDosen($data['modul_bahan_ajar_id'], $model->dosen_id); echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiModul::getAllDokumenBukti($data['modul_bahan_ajar_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja_penelitian += $jlh_sks; ?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{ ?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja_penelitian += $jlh_sks; }} ?>
                        
                <?php
                if(count($dataProviderMakalahSeminar) > 0){ 
                    foreach ($dataProviderMakalahSeminar as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i_penelitian ?></td>
                            <td style="border: 1px solid black;"> <?= 'Membuat Makalah Seminar: '.$data['judul_makalah'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks = \backend\modules\baak\models\MakalahSeminar::getJlhSksDosen($data['makalah_seminar_id'], $model->dosen_id); echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiMakalahSeminar::getAllDokumenBukti($data['makalah_seminar_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja_penelitian += $jlh_sks; ?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja_penelitian += $jlh_sks; }} ?> 
                        
                <?php
                if(count($dataProviderJurnalIlmiah) > 0){ 
                    foreach ($dataProviderJurnalIlmiah as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i_penelitian ?></td>
                            <td style="border: 1px solid black;"> <?=  'Membuat Jurnal Ilmiah: '.$data['judul_jurnal_ilmiah'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks = \backend\modules\baak\models\JurnalIlmiah::getJlhSksDosen($data['jurnal_ilmiah_id'], $model->dosen_id); echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiSeminarTerjadwal::getAllDokumenBukti($data['jurnal_ilmiah_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja_penelitian += $jlh_sks; ?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja_penelitian +=$jlh_sks;  }} ?>
                        <tr>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black; text-align: center">Jumlah Beban Kerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_beban_kerja_penelitian?></td>
                            <td colspan="3" style="border: 1px solid black; text-align: center">Jumlah Kinerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_kinerja_penelitian?></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
              </table>
                    
              <br>
                <h3><b><?= Html::encode('IV. BIDANG PENGABDIAN KEPADA MASYARAKAT')?></b></h3>
                <table style="width:120%; border: 2px solid black;">
                    <tr>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">No</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Jenis Kegiatan</th>
                        <th colspan="2" style="text-align:center; border: 1px solid black;">Beban Kerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Masa Pelaksanaan Tugas</th>
                        <th colspan="4" style="text-align:center; border: 1px solid black;">Kinerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Penilaian/Rekomendasi Asesor</th>
                    </tr>
                <tr>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">Bukti Penugasan</th>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">SKS</th>
                    <th rowspan="2" colspan="2" style="text-align:center; border: 1px solid black;">Bukti Dokumen</th>
                    <th colspan="2" style="text-align:center; border: 1px solid black;">Capaian</th>
                </tr>
                <tr>
                    <th style="text-align:center; border: 1px solid black;">%</th>
                    <th style="text-align:center; border: 1px solid black;">SKS</th>
                </tr>
                <?php 
                $i_pengabdian = 0;
                $jlh_beban_kerja_pengabdian = 0;
                $jlh_kinerja_pengabdian = 0;
                if(count($dataProviderKegiatanPengabdian) > 0){ 
                    foreach ($dataProviderKegiatanPengabdian as $data){ ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i_pengabdian ?></td>
                            <td style="border: 1px solid black;"> <?= 'Melakukan Kegiatan Pengabdian Masyarakat: '.$data['nama_kegiatan'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks = \backend\modules\baak\models\KegiatanPengabdianMasyarakat::getJlhSksDosen($data['kegiatan_pengabdian_masyarakat_id'], $model->dosen_id); echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiKegiatanPengabdian::getAllDokumenBukti($data['kegiatan_pengabdian_masyarakat_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja_pengabdian += $jlh_sks;?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                <?php $jlh_beban_kerja_pengabdian += $jlh_sks;}} ?>
               
                        <tr>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black; text-align: center">Jumlah Beban Kerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_beban_kerja_pengabdian?></td>
                            <td colspan="3" style="border: 1px solid black; text-align: center">Jumlah Kinerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_kinerja_pengabdian?></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
              </table>
                
                
                <br>
                <h3><b><?= Html::encode('V. BIDANG PENGEMBANGAN INSTANSI')?></b></h3>
                <table style="width:120%; border: 2px solid black;">
                    <tr>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">No</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Jenis Kegiatan</th>
                        <th colspan="2" style="text-align:center; border: 1px solid black;">Beban Kerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Masa Pelaksanaan Tugas</th>
                        <th colspan="4" style="text-align:center; border: 1px solid black;">Kinerja</th>
                        <th rowspan="3" style="text-align:center; border: 1px solid black;">Penilaian/Rekomendasi Asesor</th>
                    </tr>
                <tr>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">Bukti Penugasan</th>
                    <th rowspan="2" style="text-align:center; border: 1px solid black;">SKS</th>
                    <th rowspan="2" colspan="2" style="text-align:center; border: 1px solid black;">Bukti Dokumen</th>
                    <th colspan="2" style="text-align:center; border: 1px solid black;">Capaian</th>
                </tr>
                <tr>
                    <th style="text-align:center; border: 1px solid black;">%</th>
                    <th style="text-align:center; border: 1px solid black;">SKS</th>
                </tr>
                <?php 
                $i_instansi = 0;
                $jlh_beban_kerja_instansi= 0;
                $jlh_kinerja_instansi = 0;
                if(count($dataProviderDosenJabatan) > 0){ 
                    foreach ($dataProviderDosenJabatan as $data){ 
                        $strukturJabatan = \backend\modules\baak\models\InstStrukturJabatan::getJabatan($data['struktur_jabatan_id']);
                        ?>
                        <tr>
                            <td style="border: 1px solid black;"><?= ++$i_instansi ?></td>
                            <td style="border: 1px solid black;"> <?= 'Menjabat Sebagai: '.$strukturJabatan['jabatan'] ?></td>
                            <td style="border: 1px solid black;">Surat Tugas dari Pimpinan</td>
                            <td style="border: 1px solid black;"><?php $jlh_sks = $strukturJabatan['jlh_sks']; echo $jlh_sks; ?></td>
                            <td style="border: 1px solid black;">1 Semester</td>
                            <td colspan="2" style="border: 1px solid black;">
                                <?php $dokumen = \backend\modules\baak\models\DokumenBuktiDosenJabatan::getAllDokumenBukti($data['dosen_jabatan_id']);
                                $j=0;
                                    foreach ($dokumen as $value){
                                        echo ++$j .' '. \backend\modules\baak\models\HeaderDetailDokumenBukti::getNamaDokumen($value['header_detail_dokumen_bukti_id']) .'<br>';
                                    }
                                ?>
                            </td>
                            <?php if($data['status_realisasi']==1){ $jlh_kinerja_instansi += $jlh_sks;?>
                                <td style="border: 1px solid black;">100</td>
                                <td style="border: 1px solid black;"><?= $jlh_sks ?></td>
                                <td style="border: 1px solid black;">Diterima</td>
                            <?php }else{?>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">0</td>
                                <td style="border: 1px solid black;">Ditolak</td>
                            <?php }?>
                        </tr>
                        <?php $jlh_beban_kerja_instansi += $jlh_sks;}} ?>
               
                        <tr>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black; text-align: center">Jumlah Beban Kerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_beban_kerja_instansi?></td>
                            <td colspan="3" style="border: 1px solid black; text-align: center">Jumlah Kinerja</td>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"><?=$jlh_kinerja_instansi?></td>
                            <td style="border: 1px solid black;"></td>
                        </tr>
              </table>
                <br>
                <div style="width: 100%;*zoom: 1;">
                    <div style="margin:10px 0;padding: 5px; text-align: center; font-family:monotype-cors">
                        <span style="margin-top:20px;font-weight:bold;line-height:1.1;color:#444;font-size: 18px;"> PERNYATAAN DOSEN</span><br><br>
                        
                    </div>
                    <p>
                        Saya dosen yang membuat kinerja ini menyatakan bahwa semua aktivitas dan bukti pendukungnya adalah benar aktivitas saya dan
                        saya sanggup menerima sanksi apapun termasuk penghentian tunjungan dan mengembalikan yang sudah diterima apabila pernyataan 
                        ini dikemudian hari terbukti tidak benar.
                        </p>
                    
                </div>
                <div class="col-md-12">
                        <table>
                            <tr>
                                <td colspan="5">
                                    <center><span class="description-text">Mengesahkan,</span></center>
                                    <center><span class="description-text">Dekan Fakultas...</span></center>
                                </td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                
                                <td  colspan="5">
                                    <center><span class="description-text">Lokasi, Tanggal</span></center>
                                    <center><span class="description-text">Dosen Yang Membuat</span></center>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <br><br>
                                    <center><span class="description-text">Tanda tangan dan cap</span></center>
                                    <center><span class="description-text">(Nama beserta gelar)</span></center>
                                    <center><span class="description-text"></span>Nip:</center>
                                </td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                               <td  colspan="5">
                                   <br><br>
                                    <center><span class="description-text"></span></center>
                                    <br>
                                    <center><span class="description-text"></span>(Nama beserta gelar)</center>
                                    <center><span class="description-text"></span>Nip:</center>
                                </td>
                            </tr>
                        
                        </table>
                        <!-- /.description-block -->
                    </div>
                
                <br><br>
                <div style="width: 100%;*zoom: 1;">
                    <div style="margin:10px 0;padding: 5px; text-align: center; font-family:monotype-cors">
                        <span style="margin-top:20px;font-weight:bold;line-height:1.1;color:#444;font-size: 18px;"> PERNYATAAN ASESOR</span><br><br>
                        
                    </div>
                    <p>
                        Saya sudah memeriksa kebenaran dokumen yang ditunjukkan dan bisa menyetujui laporan evaluasi ini
                        </p>
                    
                </div>
                <div class="col-md-12">
                        <table>
                            <tr>
                                <td colspan="5">
                                    <center><span class="description-text">Mengesahkan</span></center>
                                    <center><span class="description-text">Asesor I</span></center>
                                </td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                
                                <td  colspan="5">
                                    <center><span class="description-text">Mengesahkan</span></center>
                                    <center><span class="description-text">Asesor II</span></center>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <br><br>
                                    <center><span class="description-text">Tanda tangan dan cap</span></center>
                                    <center><span class="description-text">(Nama beserta gelar)</span></center>
                                    <center><span class="description-text"></span>Nip:</center>
                                </td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                               <td  colspan="5">
                                   <br><br>
                                    <center><span class="description-text"></span></center>
                                    <br>
                                    <center><span class="description-text"></span>(Nama beserta gelar)</center>
                                    <center><span class="description-text"></span>Nip:</center>
                                </td>
                            </tr>
                        
                        </table>
                        <!-- /.description-block -->
                    </div>
                
            </div>
            
            <!-- /.col -->
        </div>
    <!-- /.box-body -->
    </div>
          <!-- /.box -->
</div>