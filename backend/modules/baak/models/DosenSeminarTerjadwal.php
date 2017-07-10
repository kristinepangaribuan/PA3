<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_seminar_terjadwal".
 *
 * @property integer $dosen_seminar_terjadwal_id
 * @property integer $dosen_id
 * @property double $jlh_sks_beban_kerja_dosen
 * @property integer $seminar_terjadwal_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakSeminarTerjadwal $seminarTerjadwal
 * @property BaakDosen $dosen
 */
class DosenSeminarTerjadwal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_seminar_terjadwal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'seminar_terjadwal_id', 'deleted'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['seminar_terjadwal_id'], 'exist', 'skipOnError' => true, 'targetClass' => SeminarTerjadwal::className(), 'targetAttribute' => ['seminar_terjadwal_id' => 'seminar_terjadwal_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_seminar_terjadwal_id' => 'Dosen Seminar Terjadwal ID',
            'dosen_id' => 'Dosen ID',
            'jlh_sks_beban_kerja_dosen' => 'Jlh Sks Beban Kerja Dosen',
            'seminar_terjadwal_id' => 'Seminar Terjadwal ID',
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
    public function getSeminarTerjadwal()
    {
        return $this->hasOne(SeminarTerjadwal::className(), ['seminar_terjadwal_id' => 'seminar_terjadwal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }
}
