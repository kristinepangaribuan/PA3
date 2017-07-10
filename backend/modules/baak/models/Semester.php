<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_r_semester".
 *
 * @property integer $semester_id
 * @property string $semester
 * @property integer $tahun_ajaran_id
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
 * @property BaakDosenMatakuliah[] $baakDosenMatakuliahs
 * @property BaakHakPaten[] $baakHakPatens
 * @property BaakJurnalIlmiah[] $baakJurnalIlmiahs
 * @property BaakKaryaPengabdianMasyarakat[] $baakKaryaPengabdianMasyarakats
 * @property BaakKegiatanPengabdianMasyarakat[] $baakKegiatanPengabdianMasyarakats
 * @property BaakMakalahSeminar[] $baakMakalahSeminars
 * @property BaakMediaMassa[] $baakMediaMassas
 * @property BaakMengembangkanProgramPerkuliahan[] $baakMengembangkanProgramPerkuliahans
 * @property BaakMengujiProposal[] $baakMengujiProposals
 * @property BaakModulBahanAjar[] $baakModulBahanAjars
 * @property BaakNaskahBuku[] $baakNaskahBukus
 * @property BaakOrasiIlmiah[] $baakOrasiIlmiahs
 * @property BaakPelaksanaanTugasPenunjang[] $baakPelaksanaanTugasPenunjangs
 * @property BaakPembimbinganDosen[] $baakPembimbinganDosens
 * @property BaakPenelitian[] $baakPenelitians
 * @property BaakRTahunAjaran $tahunAjaran
 * @property BaakSeminarTerjadwal[] $baakSeminarTerjadwals
 */
class Semester extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_r_semester';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun_ajaran_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'semester_aktif'], 'safe'],
            [['semester'], 'string', 'max' => 10],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['tahun_ajaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TahunAjaran::className(), 'targetAttribute' => ['tahun_ajaran_id' => 'tahun_ajaran_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'semester_id' => 'Semester ID',
            'semester' => 'Semester',
            'tahun_ajaran_id' => 'Tahun Ajaran ID',
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
    public function getBaakAsistenTugasPraktikums()
    {
        return $this->hasMany(BaakAsistenTugasPraktikum::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakBimbinganKuliahs()
    {
        return $this->hasMany(BaakBimbinganKuliah::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenMatakuliahs()
    {
        return $this->hasMany(BaakDosenMatakuliah::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakHakPatens()
    {
        return $this->hasMany(BaakHakPaten::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakJurnalIlmiahs()
    {
        return $this->hasMany(BaakJurnalIlmiah::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakKaryaPengabdianMasyarakats()
    {
        return $this->hasMany(BaakKaryaPengabdianMasyarakat::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakKegiatanPengabdianMasyarakats()
    {
        return $this->hasMany(BaakKegiatanPengabdianMasyarakat::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakMakalahSeminars()
    {
        return $this->hasMany(BaakMakalahSeminar::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakMediaMassas()
    {
        return $this->hasMany(BaakMediaMassa::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakMengembangkanProgramPerkuliahans()
    {
        return $this->hasMany(BaakMengembangkanProgramPerkuliahan::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakMengujiProposals()
    {
        return $this->hasMany(BaakMengujiProposal::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakModulBahanAjars()
    {
        return $this->hasMany(BaakModulBahanAjar::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakNaskahBukus()
    {
        return $this->hasMany(BaakNaskahBuku::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakOrasiIlmiahs()
    {
        return $this->hasMany(BaakOrasiIlmiah::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakPelaksanaanTugasPenunjangs()
    {
        return $this->hasMany(BaakPelaksanaanTugasPenunjang::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakPembimbinganDosens()
    {
        return $this->hasMany(BaakPembimbinganDosen::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakPenelitians()
    {
        return $this->hasMany(BaakPenelitian::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunAjaran()
    {
        return $this->hasOne(TahunAjaran::className(), ['tahun_ajaran_id' => 'tahun_ajaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakSeminarTerjadwals()
    {
        return $this->hasMany(BaakSeminarTerjadwal::className(), ['semester_id' => 'semester_id']);
    }
}
