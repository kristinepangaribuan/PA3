<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_makalah_seminar".
 *
 * @property integer $dosen_makalah_seminar_id
 * @property integer $dosen_id
 * @property integer $makalah_seminar_id
 * @property double $jlh_sks_beban_kerja_dosen
 * @property string $jabatan_dlm_makalah_seminar
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakMakalahSeminar $makalahSeminar
 * @property BaakDosen $dosen
 */
class DosenMakalahSeminar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_makalah_seminar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'makalah_seminar_id', 'deleted'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['jabatan_dlm_makalah_seminar'], 'string', 'max' => 30],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['makalah_seminar_id'], 'exist', 'skipOnError' => true, 'targetClass' => MakalahSeminar::className(), 'targetAttribute' => ['makalah_seminar_id' => 'makalah_seminar_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_makalah_seminar_id' => 'Dosen Makalah Seminar ID',
            'dosen_id' => 'Dosen ID',
            'makalah_seminar_id' => 'Makalah Seminar ID',
            'jlh_sks_beban_kerja_dosen' => 'Jlh Sks Beban Kerja Dosen',
            'jabatan_dlm_makalah_seminar' => 'Jabatan Dlm Makalah Seminar',
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
    public function getMakalahSeminar()
    {
        return $this->hasOne(MakalahSeminar::className(), ['makalah_seminar_id' => 'makalah_seminar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }
}
