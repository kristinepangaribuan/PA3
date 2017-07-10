<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_penelitian".
 *
 * @property integer $dosen_penelitian_id
 * @property integer $dosen_id
 * @property integer $penelitian_id
 * @property string $jabatan_dlm_penelitian
 * @property double $jlh_sks_beban_kerja_dosen
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakDokumenBukti[] $baakDokumenBuktis
 * @property BaakPenelitian $penelitian
 * @property BaakDosen $dosen
 */
class DosenPenelitian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_penelitian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'penelitian_id'], 'required'],
            [['dosen_id', 'penelitian_id', 'deleted'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['jabatan_dlm_penelitian'], 'string', 'max' => 30],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['penelitian_id'], 'exist', 'skipOnError' => true, 'targetClass' => Penelitian::className(), 'targetAttribute' => ['penelitian_id' => 'penelitian_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_penelitian_id' => 'Dosen Penelitian ID',
            'dosen_id' => 'Dosen ID',
            'penelitian_id' => 'Penelitian ID',
            'jabatan_dlm_penelitian' => 'Jabatan Dlm Penelitian',
            'jlh_sks_beban_kerja_dosen' => 'Jlh Sks Beban Kerja Dosen',
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
    public function getBaakDokumenBuktis()
    {
        return $this->hasMany(DokumenBukti::className(), ['penugasan_id' => 'dosen_penelitian_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenelitian()
    {
        return $this->hasOne(Penelitian::className(), ['penelitian_id' => 'penelitian_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }
}
