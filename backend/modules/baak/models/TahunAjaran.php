<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_r_tahun_ajaran".
 *
 * @property integer $tahun_ajaran_id
 * @property string $tahun_ajaran
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakRSemester[] $baakRSemesters
 */
class TahunAjaran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_r_tahun_ajaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'tahun_ajaran_aktif'], 'safe'],
            [['tahun_ajaran'], 'string', 'max' => 12],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahun_ajaran_id' => 'Tahun Ajaran ID',
            'tahun_ajaran' => 'Tahun Ajaran',
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
    public function getSemesters()
    {
        return $this->hasMany(Semester::className(), ['tahun_ajaran_id' => 'tahun_ajaran_id']);
    }
}
