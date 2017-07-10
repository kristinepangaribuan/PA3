<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_asisten".
 *
 * @property integer $dokumen_bukti_asisten_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $asisten_tugas_praktikum_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakAsistenTugasPraktikum $asistenTugasPraktikum
 */
class DokumenBuktiAsisten extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_asisten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'asisten_tugas_praktikum_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_file', 'path_dokumen'], 'string', 'max' => 100],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['asisten_tugas_praktikum_id'], 'exist', 'skipOnError' => true, 'targetClass' => AsistenTugasPraktikum::className(), 'targetAttribute' => ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']],
            [['file'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['jpg','gif','png', 'pdf', 'docx', 'doc', 'xlsx']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dokumen_bukti_asisten_id' => 'Dokumen Bukti Asisten ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'asisten_tugas_praktikum_id' => 'Asisten Tugas Praktikum ID',
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
    public function getAsistenTugasPraktikum()
    {
        return $this->hasOne(AsistenTugasPraktikum::className(), ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']);
    }
    
    public function getAllDokumenBukti($asisten_tugas_praktikum_id){
        $dokumen = DokumenBuktiAsisten::findAll(['asisten_tugas_praktikum_id'=>$asisten_tugas_praktikum_id]);
        return $dokumen;
    }
}
