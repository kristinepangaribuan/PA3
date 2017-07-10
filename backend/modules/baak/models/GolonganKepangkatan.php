<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_r_golongan_kepangkatan".
 *
 * @property integer $golongan_kepangkatan_id
 * @property string $nama_golongan_kepangkatan
 *
 * @property BaakDosen[] $baakDosens
 */
class GolonganKepangkatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_r_golongan_kepangkatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_golongan_kepangkatan'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'golongan_kepangkatan_id' => 'Golongan Kepangkatan ID',
            'nama_golongan_kepangkatan' => 'Nama Golongan Kepangkatan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosens()
    {
        return $this->hasMany(BaakDosen::className(), ['golongan_kepangkatan_id' => 'golongan_kepangkatan_id']);
    }
}
