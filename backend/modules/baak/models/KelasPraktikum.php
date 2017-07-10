<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_kelas_praktikum".
 *
 * @property integer $kelas_praktikum_id
 * @property integer $kelas_id
 * @property integer $asisten_tugas_praktikum_id
 *
 * @property BaakAsistenTugasPraktikum $asistenTugasPraktikum
 * @property BaakRKelas $kelas
 */
class KelasPraktikum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_kelas_praktikum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kelas_praktikum_id', 'kelas_id', 'asisten_tugas_praktikum_id'], 'integer'],
            [['asisten_tugas_praktikum_id'], 'exist', 'skipOnError' => true, 'targetClass' => AsistenTugasPraktikum::className(), 'targetAttribute' => ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']],
            [['kelas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kelas::className(), 'targetAttribute' => ['kelas_id' => 'kelas_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kelas_praktikum_id' => 'Kelas Praktikum ID',
            'kelas_id' => 'Kelas ID',
            'asisten_tugas_praktikum_id' => 'Asisten Tugas Praktikum ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsistenTugasPraktikum()
    {
        return $this->hasOne(AsistenTugasPraktikum::className(), ['asisten_tugas_praktikum_id' => 'asisten_tugas_praktikum_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelas()
    {
        return $this->hasOne(Kelas::className(), ['kelas_id' => 'kelas_id']);
    }
}
