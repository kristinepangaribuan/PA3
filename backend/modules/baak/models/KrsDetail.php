<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_prkl_krs_detail".
 *
 * @property integer $krs_detail_id
 * @property integer $krs_mhs_id
 * @property integer $kuliah_id
 * @property integer $pengajaran_id
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property BaakPrklKrsMhs $krsMhs
 * @property BaakKrkmKuliah $kuliah
 */
class KrsDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_prkl_krs_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['krs_mhs_id', 'kuliah_id', 'pengajaran_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['krs_mhs_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakPrklKrsMhs::className(), 'targetAttribute' => ['krs_mhs_id' => 'krs_mhs_id']],
            [['kuliah_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakKrkmKuliah::className(), 'targetAttribute' => ['kuliah_id' => 'kuliah_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'krs_detail_id' => 'Krs Detail ID',
            'krs_mhs_id' => 'Krs Mhs ID',
            'kuliah_id' => 'Kuliah ID',
            'pengajaran_id' => 'Pengajaran ID',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrsMhs()
    {
        return $this->hasOne(BaakPrklKrsMhs::className(), ['krs_mhs_id' => 'krs_mhs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKuliah()
    {
        return $this->hasOne(BaakKrkmKuliah::className(), ['kuliah_id' => 'kuliah_id']);
    }
}
