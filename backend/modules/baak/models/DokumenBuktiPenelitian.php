<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_penelitian".
 *
 * @property integer $dokumen_bukti_penelitian_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $penelitian_id
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakPenelitian $penelitian
 */
class DokumenBuktiPenelitian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file;
    public static function tableName()
    {
        return 'baak_dokumen_bukti_penelitian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'penelitian_id'], 'integer'],
            [['nama_file', 'path_dokumen'], 'string', 'max' => 200],
            [['nama_file'], 'required'],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['penelitian_id'], 'exist', 'skipOnError' => true, 'targetClass' => Penelitian::className(), 'targetAttribute' => ['penelitian_id' => 'penelitian_id']],
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
            'dokumen_bukti_penelitian_id' => 'Dokumen Bukti Penelitian ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'penelitian_id' => 'Penelitian ID',
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
    public function getPenelitian()
    {
        return $this->hasOne(Penelitian::className(), ['penelitian_id' => 'penelitian_id']);
    }
    
    public function getAllDokumenBukti($penelitian_id){
        $dokumen = DokumenBuktiPenelitian::findAll(['penelitian_id'=>$penelitian_id]);
        return $dokumen;
    }
}
