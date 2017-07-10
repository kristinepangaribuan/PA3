<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_jurnal_ilmiah".
 *
 * @property integer $dosen_jurnal_ilmiah_id
 * @property integer $dosen_id
 * @property integer $jurnal_ilmiah_id
 * @property double $jlh_sks_beban_dosen
 * @property string $jabatan_dlm_jurnal_ilmiah
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakDokumenBuktiJurnalIlmiah[] $baakDokumenBuktiJurnalIlmiahs
 * @property BaakJurnalIlmiah $jurnalIlmiah
 * @property BaakDosen $dosen
 */
class DosenJurnalIlmiah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_jurnal_ilmiah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'jurnal_ilmiah_id', 'deleted'], 'integer'],
            [['jlh_sks_beban_dosen'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['jabatan_dlm_jurnal_ilmiah'], 'string', 'max' => 30],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['jurnal_ilmiah_id'], 'exist', 'skipOnError' => true, 'targetClass' => JurnalIlmiah::className(), 'targetAttribute' => ['jurnal_ilmiah_id' => 'jurnal_ilmiah_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_jurnal_ilmiah_id' => 'Dosen Jurnal Ilmiah ID',
            'dosen_id' => 'Dosen ID',
            'jurnal_ilmiah_id' => 'Jurnal Ilmiah ID',
            'jlh_sks_beban_dosen' => 'Jlh Sks Beban Dosen',
            'jabatan_dlm_jurnal_ilmiah' => 'Jabatan Dlm Jurnal Ilmiah',
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
    public function getDokumenBuktiJurnalIlmiahs()
    {
        return $this->hasMany(DokumenBuktiJurnalIlmiah::className(), ['jurnal_ilmiah_id' => 'dosen_jurnal_ilmiah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJurnalIlmiah()
    {
        return $this->hasOne(JurnalIlmiah::className(), ['jurnal_ilmiah_id' => 'jurnal_ilmiah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }
}
