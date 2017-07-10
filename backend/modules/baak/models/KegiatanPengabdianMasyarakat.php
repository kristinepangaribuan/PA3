<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_kegiatan_pengabdian_masyarakat".
 *
 * @property integer $kegiatan_pengabdian_masyarakat_id
 * @property string $nama_kegiatan
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property string $kategori_kegiatan
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
 * @property BaakDokumenBuktiKegiatanPengabdian[] $baakDokumenBuktiKegiatanPengabdians
 * @property BaakDosenKegiatanPengabdian[] $baakDosenKegiatanPengabdians
 * @property BaakRSemester $semester
 */
class KegiatanPengabdianMasyarakat extends \yii\db\ActiveRecord
{
  public $jlh_sks_pengabdian;
  public $dosen_id = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_kegiatan_pengabdian_masyarakat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['nama_kegiatan'], 'required', 'message' => 'Nama Kegiatan Harus Diisi'],
            [['kategori_kegiatan'], 'required', 'message' => 'Kategori Kegiatan Harus Dipilih'],
            [['jlh_sks_pengabdian'], 'number'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['nama_kegiatan', 'kategori_kegiatan'], 'string', 'max' => 100],
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
            'kegiatan_pengabdian_masyarakat_id' => 'Kegiatan Pengabdian Masyarakat ID',
            'nama_kegiatan' => 'Nama Kegiatan',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'kategori_kegiatan' => 'Kategori Kegiatan',
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
    public function getDokumenBuktiKegiatanPengabdians()
    {
        return $this->hasMany(DokumenBuktiKegiatanPengabdian::className(), ['kegiatan_pengabdian_masyarakat_id' => 'kegiatan_pengabdian_masyarakat_id']);
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
    public function getDosenKegiatanPengabdians()
    {
        return $this->hasMany(DosenKegiatanPengabdian::className(), ['kegiatan_pengabdian_masyarakat_id' => 'kegiatan_pengabdian_masyarakat_id']);
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
        $dosen = DosenKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id'=>$id, 'dosen_id'=>$dosen_id])->one();
        return $dosen['jlh_sks_beban_kerja_dosen'];
    }
}
