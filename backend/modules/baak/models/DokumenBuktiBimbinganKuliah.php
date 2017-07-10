<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_bimbingan_kuliah".
 *
 * @property integer $dokumen_bukti_bimbingan_kuliah_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $bimbingan_kuliah_id
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakBimbinganKuliah $bimbinganKuliah
 */
class DokumenBuktiBimbinganKuliah extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_bimbingan_kuliah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'bimbingan_kuliah_id'], 'integer'],
            [['nama_file'], 'string', 'max' => 100],
            [['nama_file'], 'required'],
            [['path_dokumen'], 'string', 'max' => 200],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['bimbingan_kuliah_id'], 'exist', 'skipOnError' => true, 'targetClass' => BimbinganKuliah::className(), 'targetAttribute' => ['bimbingan_kuliah_id' => 'bimbingan_kuliah_id']],
            [['file'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['jpg','gif','png', 'pdf', 'docx', 'doc', 'xmls'], 'maxSize' => 1024 * 1024 * 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dokumen_bukti_bimbingan_kuliah_id' => 'Dokumen Bukti Bimbingan Kuliah ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'bimbingan_kuliah_id' => 'Bimbingan Kuliah ID',
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
    public function getBimbinganKuliah()
    {
        return $this->hasOne(BimbinganKuliah::className(), ['bimbingan_kuliah_id' => 'bimbingan_kuliah_id']);
    }
    
    public function getAllDokumenBukti($bimbingan_kuliah_id){
        $dokumen = DokumenBuktiBimbinganKuliah::findAll(['bimbingan_kuliah_id'=>$bimbingan_kuliah_id]);
        return $dokumen;
    }
}
