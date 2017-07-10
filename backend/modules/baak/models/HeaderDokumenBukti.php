<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_header_dokumen_bukti".
 *
 * @property integer $header_dokumen_bukti_id
 * @property string $nama_header_dokumen_bukti
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakAsistenTugasPraktikum[] $baakAsistenTugasPraktikums
 * @property BaakBimbinganKuliah[] $baakBimbinganKuliahs
 * @property BaakHakPaten[] $baakHakPatens
 * @property BaakHeaderDetailDokumenBukti[] $baakHeaderDetailDokumenBuktis
 * @property BaakMediaMassa[] $baakMediaMassas
 * @property BaakModulBahanAjar[] $baakModulBahanAjars
 * @property BaakNaskahBuku[] $baakNaskahBukus
 * @property BaakOrasiIlmiah[] $baakOrasiIlmiahs
 */
class HeaderDokumenBukti extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_header_dokumen_bukti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_header_dokumen_bukti'], 'string', 'max' => 30],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti ID',
            'nama_header_dokumen_bukti' => 'Nama Header Dokumen Bukti',
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
    public function getAsistenTugasPraktikums()
    {
        return $this->hasMany(AsistenTugasPraktikum::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBimbinganKuliahs()
    {
        return $this->hasMany(BimbinganKuliah::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHakPatens()
    {
        return $this->hasMany(HakPaten::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeaderDetailDokumenBuktis()
    {
        return $this->hasMany(HeaderDetailDokumenBukti::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaMassas()
    {
        return $this->hasMany(MediaMassa::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModulBahanAjars()
    {
        return $this->hasMany(ModulBahanAjar::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNaskahBukus()
    {
        return $this->hasMany(NaskahBuku::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrasiIlmiahs()
    {
        return $this->hasMany(OrasiIlmiah::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }
}
