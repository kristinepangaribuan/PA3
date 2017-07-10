<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_status_frk_dosen".
 *
 * @property integer $status_frk_dosen_id
 * @property integer $dosen_id
 * @property string $status
 * @property integer $semester_id
 * @property string $pesan
 * @property integer $status_read
 * @property integer $dosen_k_prodi_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property BaakDosen $dosen
 * @property BaakDosen $dosenKProdi
 * @property BaakRSemester $semester
 */
class StatusFrkDosen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_status_frk_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'semester_id', 'status_read', 'dosen_k_prodi_id', 'deleted'], 'integer'],
            [['pesan'], 'string'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['status', 'deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            [['dosen_k_prodi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_k_prodi_id' => 'dosen_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_frk_dosen_id' => 'Status Frk Dosen ID',
            'dosen_id' => 'Dosen ID',
            'status' => 'Status',
            'semester_id' => 'Semester ID',
            'pesan' => 'Pesan',
            'status_read' => 'Status Read',
            'dosen_k_prodi_id' => 'Dosen K Prodi ID',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
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
    public function getDosenKProdi()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_k_prodi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semester_id' => 'semester_id']);
    }
    
    public function requestingFrk($dosen_id){
        $dosen = Dosen::findOne($dosen_id);
        $queryStatusDosen = \backend\modules\baak\models\StatusFrkDosen::find()->where(['status'=>'Pengajuan FRK'])->all();
        $idDosen = \yii\helpers\ArrayHelper::map($queryStatusDosen, 'dosen_id', 'dosen_id');
        $queryDosen = Dosen::find()->where(['IN', 'dosen_id', $idDosen])->andWhere(['ref_kbk_id'=>$dosen['ref_kbk_id']])->all();
        $count = count($queryDosen);
        return $count;
    }
    
    public function notifikasiFrk($dosen_id){
        $status_approve = 'Approve FRK';
        $status_reject = 'Reject FRK';
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $queryStatusDosen = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['IN', 'status', [$status_approve, $status_reject]])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['dosen_id'=>$dosen_id])
                ->andWhere(['status_read'=>0])->one();
        $count = count($queryStatusDosen);
        return $count;
    }
}
