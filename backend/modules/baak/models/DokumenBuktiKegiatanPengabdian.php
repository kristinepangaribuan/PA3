<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_kegiatan_pengabdian".
 *
 * @property integer $dokumen_bukti_kegiatan_pengabdian_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $kegiatan_pengabdian_masyarakat_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakKegiatanPengabdianMasyarakat $kegiatanPengabdianMasyarakat
 */
class DokumenBuktiKegiatanPengabdian extends \yii\db\ActiveRecord
{
  public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_kegiatan_pengabdian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'kegiatan_pengabdian_masyarakat_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_file'], 'string', 'max' => 100],
            [['path_dokumen'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['kegiatan_pengabdian_masyarakat_id'], 'exist', 'skipOnError' => true, 'targetClass' => KegiatanPengabdianMasyarakat::className(), 'targetAttribute' => ['kegiatan_pengabdian_masyarakat_id' => 'kegiatan_pengabdian_masyarakat_id']],
            [['file'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['jpg','gif','png', 'pdf', 'docx', 'doc', 'xmls']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dokumen_bukti_kegiatan_pengabdian_id' => 'Dokumen Bukti Kegiatan Pengabdian ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'kegiatan_pengabdian_masyarakat_id' => 'Kegiatan Pengabdian Masyarakat ID',
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
    public function getHeaderDetailDokumenBukti()
    {
        return $this->hasOne(HeaderDetailDokumenBukti::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatanPengabdianMasyarakat()
    {
        return $this->hasOne(KegiatanPengabdianMasyarakat::className(), ['kegiatan_pengabdian_masyarakat_id' => 'kegiatan_pengabdian_masyarakat_id']);
    }
    
    public function getAllDokumenBukti($kegiatan_pengabdian_masyarakat_id){
        $dokumen = DokumenBuktiKegiatanPengabdian::findAll(['kegiatan_pengabdian_masyarakat_id'=>$kegiatan_pengabdian_masyarakat_id]);
        return $dokumen;
    }
}
