<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "baak_dosen".
 *
 * @property integer $dosen_id
 * @property string $nama_dosen
 * @property string $email
 * @property string $alamat
 * @property string $nidn
 * @property integer $golongan_kepangkatan_id
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
 * @property BaakSysxUser $user
 * @property BaakRGolonganKepangkatan $golonganKepangkatan
 * @property BaakDosenAsesor[] $baakDosenAsesors
 * @property BaakDosenAsistenPraktikum[] $baakDosenAsistenPraktikums
 * @property BaakDosenJabatan[] $baakDosenJabatans
 * @property BaakDosenJurnalIlmiah[] $baakDosenJurnalIlmiahs
 * @property BaakDosenKegiatanPengabdian[] $baakDosenKegiatanPengabdians
 * @property BaakDosenMakalahSeminar[] $baakDosenMakalahSeminars
 * @property BaakDosenMatakuliah[] $baakDosenMatakuliahs
 * @property BaakDosenModulBahanAjar[] $baakDosenModulBahanAjars
 * @property BaakDosenPenelitian[] $baakDosenPenelitians
 * @property BaakDosenSeminarTerjadwal[] $baakDosenSeminarTerjadwals
 * @property BaakMengujiProposal[] $baakMengujiProposals
 * @property BaakPenugasanAsesor[] $baakPenugasanAsesors
 * @property BaakStatusFedDosen[] $baakStatusFedDosens
 * @property BaakStatusFedDosen[] $baakStatusFedDosens0
 * @property BaakStatusFrkDosen[] $baakStatusFrkDosens
 * @property BaakStatusFrkDosen[] $baakStatusFrkDosens0
 */
class Dosen extends \yii\db\ActiveRecord
{
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
            [['aktif_start', 'aktif_end', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_dosen', 'email'], 'string', 'max' => 30],
            [['alamat'], 'string', 'max' => 50],
            [['nidn'], 'string', 'max' => 11],
            [['status_ikatan_kerja', 's1', 's2', 's3', 'ilmu_yg_ditekuni'], 'string', 'max' => 100],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['status'], 'string', 'max' => 200],
            [['no_hp'], 'string', 'max' => 12],
            [['ref_kbk_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakInstProdi::className(), 'targetAttribute' => ['ref_kbk_id' => 'ref_kbk_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakSysxUser::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['golongan_kepangkatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakRGolonganKepangkatan::className(), 'targetAttribute' => ['golongan_kepangkatan_id' => 'golongan_kepangkatan_id']],
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
            'status' => 'Status',
            's1' => 'S1',
            's2' => 'S2',
            's3' => 'S3',
            'ilmu_yg_ditekuni' => 'Ilmu Yg Ditekuni',
            'no_hp' => 'No Hp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakBimbinganKuliahs()
    {
        return $this->hasMany(BaakBimbinganKuliah::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKbk()
    {
        return $this->hasOne(BaakInstProdi::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(BaakSysxUser::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolonganKepangkatan()
    {
        return $this->hasOne(BaakRGolonganKepangkatan::className(), ['golongan_kepangkatan_id' => 'golongan_kepangkatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenAsesors()
    {
        return $this->hasMany(BaakDosenAsesor::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenAsistenPraktikums()
    {
        return $this->hasMany(BaakDosenAsistenPraktikum::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenJabatans()
    {
        return $this->hasMany(BaakDosenJabatan::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenJurnalIlmiahs()
    {
        return $this->hasMany(BaakDosenJurnalIlmiah::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenKegiatanPengabdians()
    {
        return $this->hasMany(BaakDosenKegiatanPengabdian::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenMakalahSeminars()
    {
        return $this->hasMany(BaakDosenMakalahSeminar::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenMatakuliahs()
    {
        return $this->hasMany(BaakDosenMatakuliah::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenModulBahanAjars()
    {
        return $this->hasMany(BaakDosenModulBahanAjar::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenPenelitians()
    {
        return $this->hasMany(BaakDosenPenelitian::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenSeminarTerjadwals()
    {
        return $this->hasMany(BaakDosenSeminarTerjadwal::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakMengujiProposals()
    {
        return $this->hasMany(BaakMengujiProposal::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakPenugasanAsesors()
    {
        return $this->hasMany(BaakPenugasanAsesor::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakStatusFedDosens()
    {
        return $this->hasMany(BaakStatusFedDosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakStatusFedDosens0()
    {
        return $this->hasMany(BaakStatusFedDosen::className(), ['dosen_k_prodi_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakStatusFrkDosens()
    {
        return $this->hasMany(BaakStatusFrkDosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakStatusFrkDosens0()
    {
        return $this->hasMany(BaakStatusFrkDosen::className(), ['dosen_k_prodi_id' => 'dosen_id']);
    }
}
