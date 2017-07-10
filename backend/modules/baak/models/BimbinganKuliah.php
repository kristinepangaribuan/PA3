<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_bimbingan_kuliah".
 *
 * @property integer $bimbingan_kuliah_id
 * @property string $topik_bimbingan
 * @property integer $jlh_mhs_bimbingan_kuliah
 * @property integer $jenis_bimbingan_id
 * @property integer $semester_id
 * @property integer $dosen_id
 * @property double $jlh_sks_bimbingan_kuliah
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property status $status
 * @property integer $status_realisasi
 * @property integer $status_all_dokumen
 * @property BaakDosen $dosen
 * @property BaakRJenisBimbingan $jenisBimbingan
 * @property BaakRSemester $semester
 * @property BaakDokumenBuktiBimbinganKuliah[] $baakDokumenBuktiBimbinganKuliahs
 */
class BimbinganKuliah extends \yii\db\ActiveRecord
{
    public $tahun_ajaran;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_bimbingan_kuliah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jlh_mhs_bimbingan_kuliah','tahun_ajaran', 'jenis_bimbingan_id', 'semester_id', 'dosen_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['jenis_bimbingan_id'], 'required', 'message' => 'Jenis Bimbingan Harus Dipilih'],
            [['topik_bimbingan'], 'required', 'message' => 'Topik Bimbingan Harus Diisi'],
            [['jlh_mhs_bimbingan_kuliah'], 'required', 'message' => 'Jumlah Mahasiswa Peserta Bimbingan Harus Diisi'],
            [['jlh_sks_bimbingan_kuliah'], 'number'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['topik_bimbingan'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'string', 'max' => 32],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            [['jenis_bimbingan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisBimbingan::className(), 'targetAttribute' => ['jenis_bimbingan_id' => 'jenis_bimbingan_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bimbingan_kuliah_id' => 'Bimbingan Kuliah ID',
            'topik_bimbingan' => 'Topik Bimbingan',
            'jlh_mhs_bimbingan_kuliah' => 'Jumlah Mahasiswa Bimbingan Kuliah',
            'jenis_bimbingan_id' => 'Jenis Bimbingan',
            'semester_id' => 'Semester',
            'dosen_id' => 'Dosen',
            'jlh_sks_bimbingan_kuliah' => 'Jumlah SKS Bimbingan Kuliah',
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
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBimbingan()
    {
        return $this->hasOne(JenisBimbingan::className(), ['jenis_bimbingan_id' => 'jenis_bimbingan_id']);
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
    public function getDokumenBuktiBimbinganKuliahs()
    {
        return $this->hasMany(DokumenBuktiBimbinganKuliah::className(), ['bimbingan_kuliah_id' => 'bimbingan_kuliah_id']);
    }
}
