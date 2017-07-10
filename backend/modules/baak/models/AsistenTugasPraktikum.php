<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_asisten_tugas_praktikum".
 *
 * @property integer $asisten_tugas_praktikum_id
 * @property double $jlh_sks_asistensi
 * @property integer $jlh_mhs_praktikum
 * @property integer $kuliah_id
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property integer $deleted
 * @property integer $status_realisasi
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $status
 * @property integer $status_realisasi
 * @property integer $status_all_dokumen
 * @property BaakRSemester $semester
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakKrkmKuliah $kuliah
 * @property BaakDokumenBuktiAsisten[] $baakDokumenBuktiAsistens
 * @property BaakDosenAsistenPraktikum[] $baakDosenAsistenPraktikums
 */
class AsistenTugasPraktikum extends \yii\db\ActiveRecord
{
    public $ref_kbk_id;
    public $dosen_id = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_asisten_tugas_praktikum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jlh_sks_asistensi'], 'number'],
            [['jlh_mhs_praktikum', 'kuliah_id', 'semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['kuliah_id'], 'required', 'message' => 'Matakuliah Harus Dipilih'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'string', 'max' => 32],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
            [['kuliah_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kuliah::className(), 'targetAttribute' => ['kuliah_id' => 'kuliah_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asisten_tugas_praktikum_id' => 'Asisten Tugas Praktikum ID',
            'jlh_sks_asistensi' => 'Jumlah SKS Beban Kerja Dosen',
            'jlh_mhs_praktikum' => 'Jumlah Mahasiswa',
            'kuliah_id' => 'Nama Matakuliah',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'status' => 'Status',
        ];
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
    public function getHeaderDokumenBukti()
    {
        return $this->hasOne(HeaderDokumenBukti::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKuliah()
    {
        return $this->hasOne(Kuliah::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiAsistens()
    {
        return $this->hasMany(DokumenBuktiAsisten::className(), ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']);
    }
    
    public function getKelasPraktikums()
    {
        return $this->hasMany(KelasPraktikum::className(), ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenAsistenPraktikums()
    {
        return $this->hasMany(DosenAsistenPraktikum::className(), ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']);
    }
    
    public function getJlhSksDosen($id, $dosen_id)
    {
        $dosen = DosenAsistenPraktikum::find()->where(['asisten_tugas_praktikum_id'=>$id, 'dosen_id'=>$dosen_id])->one();
        return $dosen['jlh_sks_beban_kerja_dosen'];
    }
}
