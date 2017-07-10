<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_matakuliah".
 *
 * @property integer $dosen_matakuliah_id
 * @property integer $dosen_id
 * @property integer $kuliah_id
 * @property integer $jlh_tatap_muka_dosen
 * @property double $jlh_sks_beban_kerja_dosen
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property integer $jlh_mhs_matakuliah
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
 * @property BaakDokumenBuktiDosenMatakuliah[] $baakDokumenBuktiDosenMatakuliahs
 * @property BaakKrkmKuliah $kuliah
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakDosen $dosen
 * @property BaakRSemester $semester
 */
class DosenMatakuliah extends \yii\db\ActiveRecord
{
    public $kelas_id;
    public $ref_kbk_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_matakuliah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'kuliah_id', 'jlh_tatap_muka_dosen', 'semester_id', 'header_dokumen_bukti_id', 'jlh_mhs_matakuliah', 'deleted', 'status_all_dokumen'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['jlh_tatap_muka_dosen'], 'required', 'message' => 'Jumlah Tatap Muka Harus Diisi'],
            [['kuliah_id'], 'required', 'message' => 'Matakuliah Harus Dipilih'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'string', 'max' => 32],
            [['kuliah_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kuliah::className(), 'targetAttribute' => ['kuliah_id' => 'kuliah_id']],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_matakuliah_id' => 'Dosen Matakuliah ID',
            'dosen_id' => 'Dosen',
            'kuliah_id' => 'Nama Matakuliah',
            'jlh_tatap_muka_dosen' => 'Jumlah Tatap Muka',
            'jlh_sks_beban_kerja_dosen' => 'Jumlah SKS Beban Kerja Dosen',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'jlh_mhs_matakuliah' => 'Jumlah Mahasiswa',
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
    public function getDokumenBuktiDosenMatakuliahs()
    {
        return $this->hasMany(DokumenBuktiDosenMatakuliah::className(), ['dosen_matakuliah_id' => 'dosen_matakuliah_id']);
    }
    
    public function getKelasPengajaran()
    {
        return $this->hasMany(KelasPengajaran::className(), ['dosen_matakuliah_id' => 'dosen_matakuliah_id']);
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
    public function getHeaderDokumenBukti()
    {
        return $this->hasOne(HeaderDokumenBukti::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
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
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semester_id' => 'semester_id']);
    }
}
