<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_penelitian".
 *
 * @property integer $penelitian_id
 * @property string $jenis_penelitian
 * @property string $judul_penelitian
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property string $tahapan_penelitian
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
 * @property BaakDokumenBukti[] $baakDokumenBuktis
 * @property BaakDosenPenelitian[] $baakDosenPenelitians
 * @property BaakRSemester $semester
 */
class Penelitian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $tahun_ajaran;
    public $dosen_id = [];
    public $tim_dosen;
    public static function tableName()
    {
        return 'baak_penelitian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenis_penelitian_id', 'semester_id', 'header_dokumen_bukti_id', 'jlh_target', 'deleted', 'tahapan_penelitian_id', 'status_all_dokumen'], 'integer'],
            [['jenis_penelitian_id'], 'required', 'message' => 'Jenis Penelitian Harus Dipilih'],
            [['judul_penelitian'], 'required', 'message' => 'Judul Penelitian Harus Diisi'],
            [['tahapan_penelitian_id'], 'required', 'message' => 'Tahapan Penelitian Harus Dipilih'],
            [['jlh_sks_penelitian'], 'number'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['judul_penelitian'], 'string', 'max' => 100],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'string', 'max' => 32],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
            [['tahapan_penelitian_id'], 'exist', 'skipOnError' => true, 'targetClass' => TahapanPenelitian::className(), 'targetAttribute' => ['tahapan_penelitian_id' => 'tahapan_penelitian_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'penelitian_id' => 'Penelitian ID',
            'jenis_penelitian_id' => 'Jenis Penelitian',
            'judul_penelitian' => 'Judul Penelitian',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'tahapan_penelitian_id' => 'Tahapan Pencapaian',
            'jlh_target' => 'Jlh Target',
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
    public function getDokumenBuktiPenelitians()
    {
        return $this->hasMany(DokumenBuktiPenelitian::className(), ['penelitian_id' => 'penelitian_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenPenelitians()
    {
        return $this->hasMany(DosenPenelitian::className(), ['penelitian_id' => 'penelitian_id']);
    }
    public function countJlhPenelitian($dosen_id){
        $semester = Semester::find()->where(['semester_aktif'=>1])->one();
        $dosenPenelitian = DosenPenelitian::find()
                ->where(['dosen_id'=>$dosen_id])->all();
        $idDosenPenelitian= \yii\helpers\ArrayHelper::map($dosenPenelitian, 'penelitian_id', 'penelitian_id');
        $Penelitian = Penelitian::find()->where(['IN', 'penelitian_id', $idDosenPenelitian])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])->all();
        return count($Penelitian);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semester_id' => 'semester_id']);
    }
    
    public function getTahapanPenelitian()
    {
        return $this->hasOne(TahapanPenelitian::className(), ['tahapan_penelitian_id' => 'tahapan_penelitian_id']);
    }
    
    public function getJenisPenelitian()
    {
        return $this->hasOne(TahapanPenelitian::className(), ['jenis_penelitian_id' => 'jenis_penelitian_id']);
    }
    
    public function getJlhSksDosen($id, $dosen_id)
    {
        $dosen = DosenPenelitian::find()->where(['penelitian_id'=>$id, 'dosen_id'=>$dosen_id])->one();
        return $dosen['jlh_sks_beban_kerja_dosen'];
    }
}
