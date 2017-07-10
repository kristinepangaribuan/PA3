<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_dosen_jabatan".
 *
 * @property integer $dokumen_bukti_dosen_jabatan_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $dosen_jabatan_id
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_by
 * @property string $created_at
 *
 * @property BaakDosenJabatan $dosenJabatan
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 */
class DokumenBuktiDosenJabatan extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_dosen_jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'dosen_jabatan_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_file'], 'string', 'max' => 100],
            [['path_dokumen'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['dosen_jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => DosenJabatan::className(), 'targetAttribute' => ['dosen_jabatan_id' => 'dosen_jabatan_id']],
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
            'dokumen_bukti_dosen_jabatan_id' => 'Dokumen Bukti Dosen Jabatan ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'dosen_jabatan_id' => 'Dosen Jabatan ID',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenJabatan()
    {
        return $this->hasOne(DosenJabatan::className(), ['dosen_jabatan_id' => 'dosen_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeaderDetailDokumenBukti()
    {
        return $this->hasOne(HeaderDetailDokumenBukti::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }
    
    public function getAllDokumenBukti($dosen_jabatan_id){
        $dokumen = DokumenBuktiDosenJabatan::findAll(['dosen_jabatan_id'=>$dosen_jabatan_id]);
        return $dokumen;
    }
}
