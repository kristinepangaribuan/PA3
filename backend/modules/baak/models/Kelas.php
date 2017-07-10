<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_adak_kelas".
 *
 * @property integer $kelas_id
 * @property integer $ta
 * @property string $nama
 * @property string $ket
 * @property integer $dosen_wali_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakKelasPengajaran[] $baakKelasPengajarans
 * @property BaakKelasPraktikum[] $baakKelasPraktikums
 */
class Kelas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_adak_kelas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ta', 'dosen_wali_id', 'deleted'], 'integer'],
            [['ket'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama'], 'string', 'max' => 20],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kelas_id' => 'Kelas ID',
            'ta' => 'Ta',
            'nama' => 'Nama',
            'ket' => 'Ket',
            'dosen_wali_id' => 'Dosen Wali ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakKelasPengajarans()
    {
        return $this->hasMany(BaakKelasPengajaran::className(), ['kelas_id' => 'kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakKelasPraktikums()
    {
        return $this->hasMany(BaakKelasPraktikum::className(), ['kelas_id' => 'kelas_id']);
    }
}
