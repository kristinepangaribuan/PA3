<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_modul".
 *
 * @property integer $dokumen_bukti_modul_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $modul_bahan_ajar_id
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakModulBahanAjar $modulBahanAjar
 */
class DokumenBuktiModul extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file;
    public static function tableName()
    {
        return 'baak_dokumen_bukti_modul';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'modul_bahan_ajar_id'], 'integer'],
            [['nama_file'], 'string', 'max' => 100],
            [['path_dokumen'], 'string', 'max' => 200],
            [['nama_file'], 'required'],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['modul_bahan_ajar_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModulBahanAjar::className(), 'targetAttribute' => ['modul_bahan_ajar_id' => 'modul_bahan_ajar_id']],
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
            'dokumen_bukti_modul_id' => 'Dokumen Bukti Modul ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'modul_bahan_ajar_id' => 'Modul Bahan Ajar ID',
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
    public function getModulBahanAjar()
    {
        return $this->hasOne(ModulBahanAjar::className(), ['modul_bahan_ajar_id' => 'modul_bahan_ajar_id']);
    }
    
    public function getAllDokumenBukti($modul_bahan_ajar_id){
        $dokumen = DokumenBuktiModul::findAll(['modul_bahan_ajar_id'=>$modul_bahan_ajar_id]);
        return $dokumen;
    }
}
