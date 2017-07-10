<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_asisten_praktikum".
 *
 * @property integer $dosen_asisten_praktikum_id
 * @property integer $dosen_id
 * @property integer $asisten_tugas_praktikum_id
 * @property double $jlh_sks_beban_kerja_dosen
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakDosen $dosen
 * @property BaakAsistenTugasPraktikum $asistenTugasPraktikum
 */
class DosenAsistenPraktikum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_asisten_praktikum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'asisten_tugas_praktikum_id', 'deleted'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            [['asisten_tugas_praktikum_id'], 'exist', 'skipOnError' => true, 'targetClass' => AsistenTugasPraktikum::className(), 'targetAttribute' => ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_asisten_praktikum_id' => 'Dosen Asisten Praktikum ID',
            'dosen_id' => 'Dosen ID',
            'asisten_tugas_praktikum_id' => 'Asisten Tugas Praktikum ID',
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
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsistenTugasPraktikum()
    {
        return $this->hasOne(AsistenTugasPraktikum::className(), ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']);
    }
}
