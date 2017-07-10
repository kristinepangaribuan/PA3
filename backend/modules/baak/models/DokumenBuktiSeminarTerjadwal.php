<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_seminar_terjadwal".
 *
 * @property integer $dokumen_bukti_seminar_terjadwal_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $seminar_terjadwal_id
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakSeminarTerjadwal $seminarTerjadwal
 */
class DokumenBuktiSeminarTerjadwal extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_seminar_terjadwal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'seminar_terjadwal_id'], 'integer'],
            [['nama_file'], 'string', 'max' => 100],
            [['path_dokumen'], 'string', 'max' => 200],
            [['nama_file'], 'required'],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['seminar_terjadwal_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeminarTerjadwal::className(), 'targetAttribute' => ['seminar_terjadwal_id' => 'seminar_terjadwal_id']],
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
            'dokumen_bukti_seminar_terjadwal_id' => 'Dokumen Bukti Seminar Terjadwal ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'seminar_terjadwal_id' => 'Seminar Terjadwal ID',
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
    public function getSeminarTerjadwal()
    {
        return $this->hasOne(SeminarTerjadwal::className(), ['seminar_terjadwal_id' => 'seminar_terjadwal_id']);
    }
    
    public function getAllDokumenBukti($seminar_terjadwal_id){
        $dokumen = DokumenBuktiSeminarTerjadwal::findAll(['seminar_terjadwal_id'=>$seminar_terjadwal_id]);
        return $dokumen;
    }
}
