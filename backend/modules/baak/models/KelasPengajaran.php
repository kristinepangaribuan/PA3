<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_kelas_pengajaran".
 *
 * @property integer $kelas_pengajaran_id
 * @property integer $kelas_id
 * @property integer $dosen_matakuliah_id
 *
 * @property BaakRKelas $kelas
 * @property BaakDosenMatakuliah $dosenMatakuliah
 */
class KelasPengajaran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_kelas_pengajaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kelas_id', 'dosen_matakuliah_id'], 'integer'],
            [['kelas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kelas::className(), 'targetAttribute' => ['kelas_id' => 'kelas_id']],
            [['dosen_matakuliah_id'], 'exist', 'skipOnError' => true, 'targetClass' => DosenMatakuliah::className(), 'targetAttribute' => ['dosen_matakuliah_id' => 'dosen_matakuliah_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kelas_pengajaran_id' => 'Kelas Pengajaran ID',
            'kelas_id' => 'Kelas ID',
            'dosen_matakuliah_id' => 'Dosen Matakuliah ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelas()
    {
        return $this->hasOne(Kelas::className(), ['kelas_id' => 'kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenMatakuliah()
    {
        return $this->hasOne(DosenMatakuliah::className(), ['dosen_matakuliah_id' => 'dosen_matakuliah_id']);
    }
}
