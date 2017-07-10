<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_jabatan".
 *
 * @property integer $dosen_jabatan_id
 * @property integer $dosen_id
 * @property integer $struktur_jabatan_id
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property double $jlh_sks_beban_kerja_dosen
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 * @property string $status
 * @property integer $status_realisasi
 * @property integer $status_all_dokumen
 *
 * @property BaakDokumenBuktiDosenJabatan[] $baakDokumenBuktiDosenJabatans
 * @property BaakDosen $dosen
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakRSemester $semester
 * @property BaakInstStrukturJabatan $strukturJabatan
 */
class DosenJabatan extends \yii\db\ActiveRecord
{
    public $tahun_ajaran;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'struktur_jabatan_id', 'semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['struktur_jabatan_id'], 'required', 'message' => 'Jenis Jabatan Harus Dipilih'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['deleted_at', 'updated_at', 'created_at', 'status_realisasi'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by', 'status'], 'string', 'max' => 32],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
            [['struktur_jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstStrukturJabatan::className(), 'targetAttribute' => ['struktur_jabatan_id' => 'struktur_jabatan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_jabatan_id' => 'Dosen Jabatan ID',
            'dosen_id' => 'Dosen ID',
            'struktur_jabatan_id' => 'Struktur Jabatan',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'jlh_sks_beban_kerja_dosen' => 'Jumlah SKS Beban Kerja',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDokumenBuktiDosenJabatans()
    {
        return $this->hasMany(DokumenBuktiDosenJabatan::className(), ['dosen_jabatan_id' => 'dosen_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeaderDokumenBukti()
    {
        return $this->hasOne(HeaderDokumenBukti::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatan()
    {
        return $this->hasOne(InstStrukturJabatan::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }
}
