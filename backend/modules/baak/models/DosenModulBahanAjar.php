<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_modul_bahan_ajar".
 *
 * @property integer $dosen_modul_bahan_ajar_id
 * @property integer $dosen_id
 * @property integer $modul_bahan_ajar_id
 * @property string $jabatan_dlm_modul_bahan_ajar
 * @property integer $jlh_sks_beban_kerja_dosen
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakModulBahanAjar $modulBahanAjar
 * @property BaakDosen $dosen
 */
class DosenModulBahanAjar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_modul_bahan_ajar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'modul_bahan_ajar_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['jabatan_dlm_modul_bahan_ajar'], 'string', 'max' => 30],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['modul_bahan_ajar_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModulBahanAjar::className(), 'targetAttribute' => ['modul_bahan_ajar_id' => 'modul_bahan_ajar_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_modul_bahan_ajar_id' => 'Dosen Modul Bahan Ajar ID',
            'dosen_id' => 'Dosen ID',
            'modul_bahan_ajar_id' => 'Modul Bahan Ajar ID',
            'jabatan_dlm_modul_bahan_ajar' => 'Jabatan Dlm Modul Bahan Ajar',
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
    public function getModulBahanAjar()
    {
        return $this->hasOne(ModulBahanAjar::className(), ['modul_bahan_ajar_id' => 'modul_bahan_ajar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }
}
