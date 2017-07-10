<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_asesor".
 *
 * @property integer $dosen_asesor_id
 * @property integer $penugasan_asesor_id
 * @property integer $dosen_id
 * @property integer $semester_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property BaakDosen $dosen
 * @property BaakPenugasanAsesor $penugasanAsesor
 * @property BaakRSemester $semester
 */
class DosenAsesor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_asesor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['penugasan_asesor_id', 'dosen_id', 'semester_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            [['penugasan_asesor_id'], 'exist', 'skipOnError' => true, 'targetClass' => PenugasanAsesor::className(), 'targetAttribute' => ['penugasan_asesor_id' => 'penugasan_asesor_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_asesor_id' => 'Dosen Asesor ID',
            'penugasan_asesor_id' => 'Penugasan Asesor ID',
            'dosen_id' => 'Dosen ID',
            'semester_id' => 'Semester ID',
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
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenugasanAsesor()
    {
        return $this->hasOne(PenugasanAsesor::className(), ['penugasan_asesor_id' => 'penugasan_asesor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semester_id' => 'semester_id']);
    }
}
