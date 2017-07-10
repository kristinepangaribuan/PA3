<?php

namespace backend\modules\baak\models;
use yii\helpers\ArrayHelper;

use Yii;
//use common\models\User;

/**
 * This is the model class for table "baak_dosen".
 *
 * @property integer $dosen_id
 * @property string $nama_dosen
 * @property string $email
 * @property string $alamat
 * @property string $nidn
 * @property integer $golongan_kepangkatan_id
 * @property integer $pegawai_id
 * @property string $status_ikatan_kerja
 * @property string $aktif_start
 * @property string $aktif_end
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property integer $user_id
 * @property integer $ref_kbk_id
 * @property string $status
 * @property string $s1
 * @property string $s2
 * @property string $s3
 * @property string $ilmu_yg_ditekuni
 * @property string $no_hp
 *
 * @property BaakBimbinganKuliah[] $baakBimbinganKuliahs
 * @property BaakInstProdi $refKbk
 * @property BaakUser $user
 * @property BaakRGolonganKepangkatan $golonganKepangkatan
 * @property BaakRPegawai $pegawai
 * @property BaakDosenAsistenPraktikum[] $baakDosenAsistenPraktikums
 * @property BaakDosenJabatan[] $baakDosenJabatans
 * @property BaakDosenJurnalIlmiah[] $baakDosenJurnalIlmiahs
 * @property BaakDosenKaryaPengabdian[] $baakDosenKaryaPengabdians
 * @property BaakDosenKegiatanPengabdian[] $baakDosenKegiatanPengabdians
 * @property BaakDosenMakalahSeminar[] $baakDosenMakalahSeminars
 * @property BaakDosenMatakuliah[] $baakDosenMatakuliahs
 * @property BaakDosenMengembangkanPerkuliahan[] $baakDosenMengembangkanPerkuliahans
 * @property BaakDosenModulBahanAjar[] $baakDosenModulBahanAjars
 * @property BaakDosenNaskahBuku[] $baakDosenNaskahBukus
 * @property BaakDosenPenelitian[] $baakDosenPenelitians
 * @property BaakDosenSeminarTerjadwal[] $baakDosenSeminarTerjadwals
 * @property BaakHakPaten[] $baakHakPatens
 * @property BaakMediaMassa[] $baakMediaMassas
 * @property BaakMengujiProposal[] $baakMengujiProposals
 * @property BaakOrasiIlmiah[] $baakOrasiIlmiahs
 * @property BaakPelaksanaanTugasPenunjang[] $baakPelaksanaanTugasPenunjangs
 * @property BaakPembimbinganDosen[] $baakPembimbinganDosens
 */
class Dosen extends \yii\db\ActiveRecord
{
    public $username;
    public $password;
    public $passwordconf;
    public $role_user_id;
    public $semester_id;
    public $tahun_ajaran_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['golongan_kepangkatan_id', 'deleted', 'user_id', 'ref_kbk_id'], 'integer'],
            [['aktif_start', 'aktif_end', 'created_at', 'updated_at', 'deleted_at', 'tempat_tgl_lahir'], 'safe'],
            [['nama_dosen', 'email'], 'string', 'max' => 30],
            [['alamat'], 'string', 'max' => 50],
            [['nidn'], 'string', 'max' => 11],
            [['status'], 'string', 'max' => 200],
            [['no_hp'], 'string', 'max' => 12],
            [['status_ikatan_kerja', 's1', 's2', 's3', 'ilmu_yg_ditekuni'], 'string', 'max' => 100],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['ref_kbk_id'], 'exist', 'skipOnError' => false, 'targetClass' => InstProdi::className(), 'targetAttribute' => ['ref_kbk_id' => 'ref_kbk_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['golongan_kepangkatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => GolonganKepangkatan::className(), 'targetAttribute' => ['golongan_kepangkatan_id' => 'golongan_kepangkatan_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => DosenJabatan::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            ['ref_kbk_id', 'required'],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 30],
            ['email', 'unique', 'targetClass' => 'User', 'message' => 'This email address has already been taken.'],

            [['password', 'passwordconf'], 'required'],
            [['password', 'passwordconf'], 'string', 'min' => 6],
            [['passwordconf'], 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_id' => 'Dosen ID',
            'nama_dosen' => 'Nama Dosen',
            'email' => 'Email',
            'alamat' => 'Alamat',
            'nidn' => 'Nidn',
            'golongan_kepangkatan_id' => 'Golongan Kepangkatan ID',
            'pegawai_id' => 'Pegawai ID',
            'status_ikatan_kerja' => 'Status Ikatan Kerja',
            'aktif_start' => 'Aktif Start',
            'aktif_end' => 'Aktif End',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'user_id' => 'User ID',
            'ref_kbk_id' => 'Ref Kbk ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBimbinganKuliahs()
    {
        return $this->hasMany(BimbinganKuliah::className(), ['dosen_id' => 'dosen_id']);
    }
    
    public function getDosenJabatan()
    {
        return $this->hasMany(DosenJabatan::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKbk()
    {
        return $this->hasOne(InstProdi::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolonganKepangkatan()
    {
        return $this->hasOne(GolonganKepangkatan::className(), ['golongan_kepangkatan_id' => 'golongan_kepangkatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenAsistenPraktikums()
    {
        return $this->hasMany(DosenAsistenPraktikum::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenJabatans()
    {
        return $this->hasMany(DosenJabatan::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenJurnalIlmiahs()
    {
        return $this->hasMany(DosenJurnalIlmiah::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenMakalahSeminars()
    {
        return $this->hasMany(DosenMakalahSeminar::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenMatakuliahs()
    {
        return $this->hasMany(DosenMatakuliah::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenModulBahanAjars()
    {
        return $this->hasMany(DosenModulBahanAjar::className(), ['dosen_id' => 'dosen_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenPenelitians()
    {
        return $this->hasMany(DosenPenelitian::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenSeminarTerjadwals()
    {
        return $this->hasMany(DosenSeminarTerjadwal::className(), ['dosen_id' => 'dosen_id']);
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMengujiProposals()
    {
        return $this->hasMany(MengujiProposal::className(), ['dosen_id' => 'dosen_id']);
    }
    
    public function getAllFrkFed($status, $dosen_id, $status_realisasi, $semester_id){
        $dosen = Dosen::findOne($dosen_id);
        $semester = \backend\modules\baak\models\Semester::findOne($semester_id);
        $total_sks= 0;
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $matakuliah = $DosenMatakuliah->all();
        
        foreach ($matakuliah as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        
        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $idAsistenTugas = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        // add conditions that should always apply here
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenTugas])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        
        foreach ($AsistenTugas as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        //bimbingan kuliah
        $queryBimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks+=$data['jlh_sks_bimbingan_kuliah'];
        }
        
        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($SeminarTerjadwal as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        
        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $MengujiProposal = $queryMengujiProposal->all();
        foreach ($MengujiProposal as $data){
            $total_sks+=$data['jlh_sks_menguji_proposal'];
        }
        
        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        
        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()
                ->where(['IN', 'modul_bahan_ajar_id', $idModul])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Modul as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        
        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()
                ->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        
        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()
                ->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks+=$data['jlh_sks_beban_dosen'];
        }
        
        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        
        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>$status_realisasi])
                ->andWhere(['status' =>$status]);
        $dosenJabatan = $queryDosenJabatan->all();
        foreach ($dosenJabatan as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        return $total_sks;
    }
    
    public function getStatusFrk($dosen_id){
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $statusFrk = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['dosen_id'=>$dosen_id])
                ->andWhere(['IN','status', ['Pengajuan FRK', 'Approve FRK']])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
        if(count($statusFrk)>0){
            return false;
        }
        $statusFed = StatusFedDosen::find()
                ->where(['dosen_id'=>$dosen_id])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->one();
        if(count($statusFed)>0){
            return false;
        }
        return true;
    }
    
    public function getStatusFed($dosen_id){
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $status = StatusFedDosen::find()->where(['dosen_id'=>$dosen_id])
                ->andWhere(['status'=>'Pengajuan FED'])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->one();
        return count($status);
    }
    
    public function getStatusFedSelesai($dosen_id){
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $status = StatusFedDosen::find()->where(['dosen_id'=>$dosen_id])
                ->andWhere(['status'=>'Approve FED'])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->one();
        return count($status);
    }
    
}
