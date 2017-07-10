<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_dosen_matakuliah".
 *
 * @property integer $dokumen_bukti_dosen_matakuliah_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $dosen_matakuliah_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakDosenMatakuliah $dosenMatakuliah
 */
class DokumenBuktiDosenMatakuliah extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_dosen_matakuliah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'dosen_matakuliah_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_file'], 'string', 'max' => 100],
            [['path_dokumen'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['dosen_matakuliah_id'], 'exist', 'skipOnError' => true, 'targetClass' => DosenMatakuliah::className(), 'targetAttribute' => ['dosen_matakuliah_id' => 'dosen_matakuliah_id']],
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
            'dokumen_bukti_dosen_matakuliah_id' => 'Dokumen Bukti Dosen Matakuliah ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'dosen_matakuliah_id' => 'Dosen Matakuliah ID',
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
    public function getDosenMatakuliah()
    {
        return $this->hasOne(DosenMatakuliah::className(), ['dosen_matakuliah_id' => 'dosen_matakuliah_id']);
    }
    
    public function getAllDokumenBukti($dosen_matakuliah_id){
        $dokumen = DokumenBuktiDosenMatakuliah::findAll(['dosen_matakuliah_id'=>$dosen_matakuliah_id]);
        return $dokumen;
    }
}
