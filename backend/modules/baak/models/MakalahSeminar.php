<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_makalah_seminar".
 *
 * @property integer $makalah_seminar_id
 * @property string $judul_makalah
 * @property string $tingkatan_makalah
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
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
 * @property BaakDokumenBuktiMakalahSeminar[] $baakDokumenBuktiMakalahSeminars
 * @property BaakDosenMakalahSeminar[] $baakDosenMakalahSeminars
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakRSemester $semester
 */
class MakalahSeminar extends \yii\db\ActiveRecord
{
    public $isTeam;
    public $dosen_id = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_makalah_seminar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['judul_makalah'], 'required', 'message' => 'Judul Makalah Harus Diisi'],
            [['tingkatan_makalah'], 'required', 'message' => 'Tingkatan Makalah Harus Dipilih'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['jlh_sks_makalah_seminar'], 'number'],
            [['judul_makalah', 'tingkatan_makalah'], 'string', 'max' => 100],
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
            'makalah_seminar_id' => 'Makalah Seminar ID',
            'judul_makalah' => 'Judul Makalah',
            'tingkatan_makalah' => 'Tingkatan Makalah',
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
    public function getBaakDokumenBuktiMakalahSeminars()
    {
        return $this->hasMany(DokumenBuktiMakalahSeminar::className(), ['makalah_seminar_id' => 'makalah_seminar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenMakalahSeminars()
    {
        return $this->hasMany(DosenMakalahSeminar::className(), ['makalah_seminar_id' => 'makalah_seminar_id']);
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
        $dosen = DosenMakalahSeminar::find()->where(['makalah_seminar_id'=>$id, 'dosen_id'=>$dosen_id])->one();
        return $dosen['jlh_sks_beban_kerja_dosen'];
    }
}
