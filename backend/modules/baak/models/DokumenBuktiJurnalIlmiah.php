<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_jurnal_ilmiah".
 *
 * @property integer $dokumen_bukti_jurnal_ilmiah_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $jurnal_ilmiah_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakDosenJurnalIlmiah $jurnalIlmiah
 * @property BaakJurnalIlmiah $jurnalIlmiah0
 */
class DokumenBuktiJurnalIlmiah extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_jurnal_ilmiah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'jurnal_ilmiah_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_file'], 'string', 'max' => 100],
            [['path_dokumen'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['jurnal_ilmiah_id'], 'exist', 'skipOnError' => true, 'targetClass' => JurnalIlmiah::className(), 'targetAttribute' => ['jurnal_ilmiah_id' => 'jurnal_ilmiah_id']],
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
            'dokumen_bukti_jurnal_ilmiah_id' => 'Dokumen Bukti Jurnal Ilmiah ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'jurnal_ilmiah_id' => 'Jurnal Ilmiah ID',
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
    public function getJurnalIlmiah0()
    {
        return $this->hasOne(JurnalIlmiah::className(), ['jurnal_ilmiah_id' => 'jurnal_ilmiah_id']);
    }
    
    public function getAllDokumenBukti($jurnal_ilmiah_id){
        $dokumen = DokumenBuktiJurnalIlmiah::findAll(['jurnal_ilmiah_id'=>$jurnal_ilmiah_id]);
        return $dokumen;
    }
}
