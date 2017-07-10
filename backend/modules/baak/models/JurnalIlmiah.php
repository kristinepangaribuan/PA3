<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_jurnal_ilmiah".
 *
 * @property integer $jurnal_ilmiah_id
 * @property string $judul_jurnal_ilmiah
 * @property string $penerbit_jurnal_ilmiah
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property string $tahapan_jurnal_ilmiah
 * @property integer $jlh_target
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
 * @property BaakDokumenBuktiJurnalIlmiah[] $baakDokumenBuktiJurnalIlmiahs
 * @property BaakDosenJurnalIlmiah[] $baakDosenJurnalIlmiahs
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakRSemester $semester
 */
class JurnalIlmiah extends \yii\db\ActiveRecord
{
    public $dosen_id = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_jurnal_ilmiah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['judul_jurnal_ilmiah'], 'required', 'message' => 'Judul Jurnal Ilmiah Harus Diisi'],
            [['penerbit_jurnal_ilmiah'], 'required', 'message' => 'Penerbit Jurnal Ilmiah Harus Dipilih'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['jlh_sks_jurnal'], 'number'],
            [['judul_jurnal_ilmiah', 'penerbit_jurnal_ilmiah', 'tahapan_jurnal_ilmiah'], 'string', 'max' => 100],
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
            'jurnal_ilmiah_id' => 'Jurnal Ilmiah ID',
            'judul_jurnal_ilmiah' => 'Judul Jurnal Ilmiah',
            'penerbit_jurnal_ilmiah' => 'Penerbit Jurnal Ilmiah',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'tahapan_jurnal_ilmiah' => 'Tahapan Jurnal Ilmiah',
            'jlh_target' => 'Jlh Target',
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
    public function getDokumenBuktiJurnalIlmiahs()
    {
        return $this->hasMany(DokumenBuktiJurnalIlmiah::className(), ['jurnal_ilmiah_id' => 'jurnal_ilmiah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenJurnalIlmiahs()
    {
        return $this->hasMany(DosenJurnalIlmiah::className(), ['jurnal_ilmiah_id' => 'jurnal_ilmiah_id']);
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
        $dosen = DosenJurnalIlmiah::find()->where(['jurnal_ilmiah_id'=>$id, 'dosen_id'=>$dosen_id])->one();
        return $dosen['jlh_sks_beban_dosen'];
    }
}
