<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_makalah_seminar".
 *
 * @property integer $dokumen_bukti_makalah_seminar_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $makalah_seminar_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakMakalahSeminar $makalahSeminar
 */
class DokumenBuktiMakalahSeminar extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_makalah_seminar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'makalah_seminar_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_file'], 'string', 'max' => 100],
            [['nama_file'], 'required'],
            [['path_dokumen'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['makalah_seminar_id'], 'exist', 'skipOnError' => true, 'targetClass' => MakalahSeminar::className(), 'targetAttribute' => ['makalah_seminar_id' => 'makalah_seminar_id']],
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
            'dokumen_bukti_makalah_seminar_id' => 'Dokumen Bukti Makalah Seminar ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'makalah_seminar_id' => 'Makalah Seminar ID',
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
    public function getMakalahSeminar()
    {
        return $this->hasOne(MakalahSeminar::className(), ['makalah_seminar_id' => 'makalah_seminar_id']);
    }
    
    public function getAllDokumenBukti($makalah_seminar_id){
        $dokumen = DokumenBuktiMakalahSeminar::findAll(['makalah_seminar_id'=>$makalah_seminar_id]);
        return $dokumen;
    }
}
