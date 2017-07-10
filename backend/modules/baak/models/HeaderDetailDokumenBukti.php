<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_header_detail_dokumen_bukti".
 *
 * @property integer $header_detail_dokumen_bukti_id
 * @property string $nama_header_detail_dokumen_bukti
 * @property integer $header_dokumen_bukti_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakDokumenBuktiAsisten[] $baakDokumenBuktiAsistens
 * @property BaakDokumenBuktiBimbinganKuliah[] $baakDokumenBuktiBimbinganKuliahs
 * @property BaakDokumenBuktiDosenMatakuliah[] $baakDokumenBuktiDosenMatakuliahs
 * @property BaakDokumenBuktiHakPaten[] $baakDokumenBuktiHakPatens
 * @property BaakDokumenBuktiJurnalIlmiah[] $baakDokumenBuktiJurnalIlmiahs
 * @property BaakDokumenBuktiKaryaPengabdian[] $baakDokumenBuktiKaryaPengabdians
 * @property BaakDokumenBuktiKegiatanPengabdian[] $baakDokumenBuktiKegiatanPengabdians
 * @property BaakDokumenBuktiMakalahSeminar[] $baakDokumenBuktiMakalahSeminars
 * @property BaakDokumenBuktiMediaMassa[] $baakDokumenBuktiMediaMassas
 * @property BaakDokumenBuktiMengembangkanPrkl[] $baakDokumenBuktiMengembangkanPrkls
 * @property BaakDokumenBuktiMengujiProposal[] $baakDokumenBuktiMengujiProposals
 * @property BaakDokumenBuktiModul[] $baakDokumenBuktiModuls
 * @property BaakDokumenBuktiNaskahBuku[] $baakDokumenBuktiNaskahBukus
 * @property BaakDokumenBuktiOrasiIlmiah[] $baakDokumenBuktiOrasiIlmiahs
 * @property BaakDokumenBuktiPelaksanaanTugas[] $baakDokumenBuktiPelaksanaanTugas
 * @property BaakDokumenBuktiPembimbinganDosen[] $baakDokumenBuktiPembimbinganDosens
 * @property BaakDokumenBuktiPembinaanUnit[] $baakDokumenBuktiPembinaanUnits
 * @property BaakDokumenBuktiPenelitian[] $baakDokumenBuktiPenelitians
 * @property BaakDokumenBuktiSeminarTerjadwal[] $baakDokumenBuktiSeminarTerjadwals
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 */
class HeaderDetailDokumenBukti extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_header_detail_dokumen_bukti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_dokumen_bukti_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_header_detail_dokumen_bukti'], 'string', 'max' => 100],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'header_detail_dokumen_bukti_id' => 'Header Detail Dokumen Bukti ID',
            'nama_header_detail_dokumen_bukti' => 'Nama Header Detail Dokumen Bukti',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti ID',
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
    public function getDokumenBuktiAsistens()
    {
        return $this->hasMany(DokumenBuktiAsisten::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiBimbinganKuliahs()
    {
        return $this->hasMany(DokumenBuktiBimbinganKuliah::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiDosenMatakuliahs()
    {
        return $this->hasMany(DokumenBuktiDosenMatakuliah::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiHakPatens()
    {
        return $this->hasMany(DokumenBuktiHakPaten::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiJurnalIlmiahs()
    {
        return $this->hasMany(DokumenBuktiJurnalIlmiah::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiKaryaPengabdians()
    {
        return $this->hasMany(DokumenBuktiKaryaPengabdian::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiKegiatanPengabdians()
    {
        return $this->hasMany(DokumenBuktiKegiatanPengabdian::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiMakalahSeminars()
    {
        return $this->hasMany(DokumenBuktiMakalahSeminar::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiMediaMassas()
    {
        return $this->hasMany(DokumenBuktiMediaMassa::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiMengembangkanPrkls()
    {
        return $this->hasMany(DokumenBuktiMengembangkanPrkl::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiMengujiProposals()
    {
        return $this->hasMany(DokumenBuktiMengujiProposal::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiModuls()
    {
        return $this->hasMany(DokumenBuktiModul::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiNaskahBukus()
    {
        return $this->hasMany(DokumenBuktiNaskahBuku::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiOrasiIlmiahs()
    {
        return $this->hasMany(DokumenBuktiOrasiIlmiah::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiPelaksanaanTugas()
    {
        return $this->hasMany(DokumenBuktiPelaksanaanTugas::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiPembimbinganDosens()
    {
        return $this->hasMany(DokumenBuktiPembimbinganDosen::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiPembinaanUnits()
    {
        return $this->hasMany(DokumenBuktiPembinaanUnit::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiPenelitians()
    {
        return $this->hasMany(DokumenBuktiPenelitian::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiSeminarTerjadwals()
    {
        return $this->hasMany(DokumenBuktiSeminarTerjadwal::className(), ['header_detail_dokumen_bukti_id' => 'header_detail_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBukti()
    {
        return $this->hasOne(HeaderDokumenBukti::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }
    
    public function getNamaDokumen($header_detail_dokumen_bukti_id){
        $namaDokumen = HeaderDetailDokumenBukti::findOne($header_detail_dokumen_bukti_id);
        return $namaDokumen['nama_header_detail_dokumen_bukti'];
    }
}
