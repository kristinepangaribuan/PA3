<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_inst_prodi".
 *
 * @property integer $ref_kbk_id
 * @property string $kbk_id
 * @property string $kpt_id
 * @property integer $jenjang_id
 * @property string $kbk_ind
 * @property string $singkatan_prodi
 * @property string $kbk_ing
 * @property string $nama_kopertis_ind
 * @property string $nama_kopertis_ing
 * @property string $short_desc_ind
 * @property string $short_desc_ing
 * @property string $desc_ind
 * @property string $desc_ing
 * @property integer $status
 * @property integer $is_jenjang_all
 * @property integer $is_public
 * @property integer $is_hidden
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 *
 * @property BaakDosen[] $baakDosens
 * @property BaakKrkmKuliah[] $baakKrkmKuliahs
 */
class InstProdi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_inst_prodi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenjang_id', 'status', 'is_jenjang_all', 'is_public', 'is_hidden', 'deleted'], 'integer'],
            [['desc_ind', 'desc_ing', 'status', 'is_jenjang_all', 'is_public'], 'required'],
            [['desc_ind', 'desc_ing'], 'string'],
            [['updated_at', 'deleted_at', 'created_at'], 'safe'],
            [['kbk_id'], 'string', 'max' => 20],
            [['kpt_id'], 'string', 'max' => 10],
            [['kbk_ind', 'kbk_ing'], 'string', 'max' => 100],
            [['singkatan_prodi'], 'string', 'max' => 50],
            [['nama_kopertis_ind', 'nama_kopertis_ing', 'short_desc_ind', 'short_desc_ing'], 'string', 'max' => 255],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['kbk_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ref_kbk_id' => 'Ref Kbk ID',
            'kbk_id' => 'Kbk ID',
            'kpt_id' => 'Kpt ID',
            'jenjang_id' => 'Jenjang ID',
            'kbk_ind' => 'Kbk Ind',
            'singkatan_prodi' => 'Singkatan Prodi',
            'kbk_ing' => 'Kbk Ing',
            'nama_kopertis_ind' => 'Nama Kopertis Ind',
            'nama_kopertis_ing' => 'Nama Kopertis Ing',
            'short_desc_ind' => 'Short Desc Ind',
            'short_desc_ing' => 'Short Desc Ing',
            'desc_ind' => 'Desc Ind',
            'desc_ing' => 'Desc Ing',
            'status' => 'Status',
            'is_jenjang_all' => 'Is Jenjang All',
            'is_public' => 'Is Public',
            'is_hidden' => 'Is Hidden',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosens()
    {
        return $this->hasMany(BaakDosen::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakKrkmKuliahs()
    {
        return $this->hasMany(Kuliah::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }
}
