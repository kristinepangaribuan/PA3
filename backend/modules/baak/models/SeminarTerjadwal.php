<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_seminar_terjadwal".
 *
 * @property integer $seminar_terjadwal_id
 * @property string $nama_seminar
 * @property integer $jlh_mhs_seminar
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property double $jlh_sks_seminar
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $status
 * @property integer $status_realisasi
 * @property integer $status_all_dokumen
 *
 * @property BaakDokumenBuktiSeminarTerjadwal[] $baakDokumenBuktiSeminarTerjadwals
 * @property BaakDosenSeminarTerjadwal[] $baakDosenSeminarTerjadwals
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakRSemester $semester
 */
class SeminarTerjadwal extends \yii\db\ActiveRecord
{
    public $tahun_ajaran;
    public $dosen_id = [];
    public $isTeam;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_seminar_terjadwal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jlh_mhs_seminar', 'semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at','status_realisasi'], 'safe'],
            [['jlh_sks_seminar'], 'number'],
            [['nama_seminar'], 'required', 'message' => 'Nama Seminar Harus Diisi'],
            [['jlh_mhs_seminar'], 'required', 'message' => 'Jumlah Mahasiswa Peserta Seminar Harus Diisi'],
            [['nama_seminar'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'string', 'max' => 32],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seminar_terjadwal_id' => 'Seminar Terjadwal ID',
            'nama_seminar' => 'Nama Seminar',
            'jlh_mhs_seminar' => 'Jumlah Mahasiswa Seminar',
            'semester_id' => 'Semester',
            'jlh_sks_seminar' => 'Jumlah SKS Seminar',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiSeminarTerjadwals()
    {
        return $this->hasMany(DokumenBuktiSeminarTerjadwal::className(), ['seminar_terjadwal_id' => 'seminar_terjadwal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenSeminarTerjadwals()
    {
        return $this->hasMany(DosenSeminarTerjadwal::className(), ['seminar_terjadwal_id' => 'seminar_terjadwal_id']);
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
    
    public function getJlhSksDosen($id, $dosen_id)
    {
        $dosen = DosenSeminarTerjadwal::find()->where(['seminar_terjadwal_id'=>$id, 'dosen_id'=>$dosen_id])->one();
        return $dosen['jlh_sks_beban_kerja_dosen'];
    }
}
