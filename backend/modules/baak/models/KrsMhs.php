<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_prkl_krs_mhs".
 *
 * @property integer $krs_mhs_id
 * @property integer $dim_id
 * @property string $nim
 * @property string $sem_ta
 * @property string $ta
 * @property integer $tahun_kurikulum_id
 * @property integer $status_approval
 * @property string $status_periode
 * @property integer $approved_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property BaakPrklKrsDetail[] $baakPrklKrsDetails
 * @property BaakDimxDim $dim
 * @property BaakKrkmRTahunKurikulum $tahunKurikulum
 * @property BaakPrklKrsReview[] $baakPrklKrsReviews
 */
class KrsMhs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_prkl_krs_mhs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dim_id', 'nim', 'sem_ta', 'ta', 'tahun_kurikulum_id'], 'required'],
            [['dim_id', 'tahun_kurikulum_id', 'status_approval', 'approved_by', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['nim'], 'string', 'max' => 8],
            [['sem_ta'], 'string', 'max' => 2],
            [['ta'], 'string', 'max' => 5],
            [['status_periode'], 'string', 'max' => 4],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['dim_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakDimxDim::className(), 'targetAttribute' => ['dim_id' => 'dim_id']],
            [['tahun_kurikulum_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakKrkmRTahunKurikulum::className(), 'targetAttribute' => ['tahun_kurikulum_id' => 'tahun_kurikulum_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'krs_mhs_id' => 'Krs Mhs ID',
            'dim_id' => 'Dim ID',
            'nim' => 'Nim',
            'sem_ta' => 'Sem Ta',
            'ta' => 'Ta',
            'tahun_kurikulum_id' => 'Tahun Kurikulum ID',
            'status_approval' => 'Status Approval',
            'status_periode' => 'Status Periode',
            'approved_by' => 'Approved By',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakPrklKrsDetails()
    {
        return $this->hasMany(BaakPrklKrsDetail::className(), ['krs_mhs_id' => 'krs_mhs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDim()
    {
        return $this->hasOne(BaakDimxDim::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunKurikulum()
    {
        return $this->hasOne(BaakKrkmRTahunKurikulum::className(), ['tahun_kurikulum_id' => 'tahun_kurikulum_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakPrklKrsReviews()
    {
        return $this->hasMany(BaakPrklKrsReview::className(), ['krs_mhs_id' => 'krs_mhs_id']);
    }
}
