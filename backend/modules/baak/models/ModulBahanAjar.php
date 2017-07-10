<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_modul_bahan_ajar".
 *
 * @property integer $modul_bahan_ajar_id
 * @property string $nama_modul
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property string $tahapan_modul
 * @property integer $jlh_targer
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
 * @property BaakDokumenBuktiModul[] $baakDokumenBuktiModuls
 * @property BaakDosenModulBahanAjar[] $baakDosenModulBahanAjars
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakRSemester $semester
 */
class ModulBahanAjar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $dosen_id;
    public $isTeam;
    public static function tableName()
    {
        return 'baak_modul_bahan_ajar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semester_id', 'header_dokumen_bukti_id', 'jlh_targer', 'deleted', 'status_all_dokumen'], 'integer'],
            [['nama_modul'], 'required', 'message' => 'Nama Modul Harus Diisi'],
            [['tahapan_modul_id'], 'required', 'message' => 'Tahapan Modul Harus Diisi'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['jlh_sks_modul'], 'number'],
            [['nama_modul',], 'string', 'max' => 100],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'string', 'max' => 32],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
            [['tahapan_modul_id'], 'exist', 'skipOnError' => true, 'targetClass' => TahapanModul::className(), 'targetAttribute' => ['tahapan_modul_id' => 'tahapan_modul_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'modul_bahan_ajar_id' => 'Modul Bahan Ajar ID',
            'nama_modul' => 'Nama Modul',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'tahapan_modul' => 'Tahapan Modul',
            'jlh_targer' => 'Jlh Targer',
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
    public function getDokumenBuktiModuls()
    {
        return $this->hasMany(DokumenBuktiModul::className(), ['modul_bahan_ajar_id' => 'modul_bahan_ajar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenModulBahanAjars()
    {
        return $this->hasMany(DosenModulBahanAjar::className(), ['modul_bahan_ajar_id' => 'modul_bahan_ajar_id']);
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
    
    public function getTahapanModul()
    {
        return $this->hasOne(TahapanModul::className(), ['tahapan_modul_id' => 'tahapan_modul_id']);
    }
    
    public function getJlhSksDosen($id, $dosen_id)
    {
        $dosen = DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id'=>$id, 'dosen_id'=>$dosen_id])->one();
        return $dosen['jlh_sks_beban_kerja_dosen'];
    }
}
