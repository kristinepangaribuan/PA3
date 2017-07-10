<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dokumen_bukti_menguji_proposal".
 *
 * @property integer $dokumen_bukti_menguji_proposal_id
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_file
 * @property string $path_dokumen
 * @property integer $menguji_proposal_id
 *
 * @property BaakHeaderDetailDokumenBukti $headerDetailDokumenBukti
 * @property BaakMengujiProposal $mengujiProposal
 */
class DokumenBuktiMengujiProposal extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dokumen_bukti_menguji_proposal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_detail_dokumen_bukti_id', 'menguji_proposal_id'], 'integer'],
            [['nama_file'], 'string', 'max' => 100],
            [['path_dokumen'], 'string', 'max' => 200],
            [['nama_file'], 'required'],
            [['header_detail_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDetailDokumenBukti::className(), 'targetAttribute' => ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']],
            [['menguji_proposal_id'], 'exist', 'skipOnError' => true, 'targetClass' => MengujiProposal::className(), 'targetAttribute' => ['menguji_proposal_id' => 'menguji_proposal_id']],
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
            'dokumen_bukti_menguji_proposal_id' => 'Dokumen Bukti Menguji Proposal ID',
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_file' => 'Nama File',
            'path_dokumen' => 'Path Dokumen',
            'menguji_proposal_id' => 'Menguji Proposal ID',
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
    public function getMengujiProposal()
    {
        return $this->hasOne(MengujiProposal::className(), ['menguji_proposal_id' => 'menguji_proposal_id']);
    }
    
    public function getAllDokumenBukti($menguji_proposal_id){
        $dokumen = DokumenBuktiMengujiProposal::findAll(['menguji_proposal_id'=>$menguji_proposal_id]);
        return $dokumen;
    }
}
