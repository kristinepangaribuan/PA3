<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_krkm_kuliah".
 *
 * @property integer $kuliah_id
 * @property integer $id_kur
 * @property string $kode_mk
 * @property string $nama_kul_ind
 * @property string $nama_kul_ing
 * @property string $short_name
 * @property string $kbk_id
 * @property string $course_group
 * @property integer $sks
 * @property integer $sem
 * @property string $meetings
 * @property integer $urut_dlm_sem
 * @property integer $sifat
 * @property string $tipe
 * @property string $level
 * @property string $key_topics_ind
 * @property string $key_topics_ing
 * @property string $objektif_ind
 * @property string $objektif_ing
 * @property integer $lab_hour
 * @property integer $tutorial_hour
 * @property integer $course_hour
 * @property integer $course_hour_in_week
 * @property integer $lab_hour_in_week
 * @property integer $number_week
 * @property string $other_activity
 * @property integer $other_activity_hour
 * @property integer $knowledge
 * @property integer $skill
 * @property integer $attitude
 * @property integer $uts
 * @property integer $uas
 * @property integer $tugas
 * @property integer $quiz
 * @property string $whiteboard
 * @property string $lcd
 * @property string $courseware
 * @property string $lab
 * @property string $elearning
 * @property integer $status
 * @property string $prerequisites
 * @property string $course_description
 * @property string $course_objectives
 * @property string $learning_outcomes
 * @property string $course_format
 * @property string $grading_procedure
 * @property string $course_content
 * @property integer $ref_kbk_id
 * @property string $web_page
 * @property integer $tahun_kurikulum_id
 * @property integer $ekstrakurikuler
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $course_group_id
 *
 * @property BaakAsistenTugasPraktikum[] $baakAsistenTugasPraktikums
 * @property BaakDosenMatakuliah[] $baakDosenMatakuliahs
 * @property BaakInstProdi $refKbk
 * @property BaakKrkmRTahunKurikulum $tahunKurikulum
 * @property BaakPrklKrsDetail[] $baakPrklKrsDetails
 */
class Kuliah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_krkm_kuliah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kur', 'sks', 'sem', 'urut_dlm_sem', 'sifat', 'lab_hour', 'tutorial_hour', 'course_hour', 'course_hour_in_week', 'lab_hour_in_week', 'number_week', 'other_activity_hour', 'knowledge', 'skill', 'attitude', 'uts', 'uas', 'tugas', 'quiz', 'status', 'ref_kbk_id', 'tahun_kurikulum_id', 'ekstrakurikuler', 'deleted', 'course_group_id'], 'integer'],
            [['key_topics_ind', 'key_topics_ing', 'objektif_ind', 'objektif_ing', 'prerequisites', 'course_description', 'course_objectives', 'learning_outcomes', 'course_format', 'grading_procedure', 'course_content'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['kode_mk'], 'string', 'max' => 8],
            [['nama_kul_ind', 'nama_kul_ing'], 'string', 'max' => 255],
            [['short_name', 'kbk_id', 'course_group'], 'string', 'max' => 20],
            [['meetings'], 'string', 'max' => 100],
            [['tipe'], 'string', 'max' => 25],
            [['level'], 'string', 'max' => 15],
            [['other_activity'], 'string', 'max' => 50],
            [['whiteboard', 'lcd', 'courseware', 'lab', 'elearning'], 'string', 'max' => 1],
            [['web_page'], 'string', 'max' => 150],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['ref_kbk_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakInstProdi::className(), 'targetAttribute' => ['ref_kbk_id' => 'ref_kbk_id']],
            [['tahun_kurikulum_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaakKrkmRTahunKurikulum::className(), 'targetAttribute' => ['tahun_kurikulum_id' => 'tahun_kurikulum_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kuliah_id' => 'Kuliah ID',
            'id_kur' => 'Id Kur',
            'kode_mk' => 'Kode Mk',
            'nama_kul_ind' => 'Nama Kul Ind',
            'nama_kul_ing' => 'Nama Kul Ing',
            'short_name' => 'Short Name',
            'kbk_id' => 'Kbk ID',
            'course_group' => 'Course Group',
            'sks' => 'Sks',
            'sem' => 'Sem',
            'meetings' => 'Meetings',
            'urut_dlm_sem' => 'Urut Dlm Sem',
            'sifat' => 'Sifat',
            'tipe' => 'Tipe',
            'level' => 'Level',
            'key_topics_ind' => 'Key Topics Ind',
            'key_topics_ing' => 'Key Topics Ing',
            'objektif_ind' => 'Objektif Ind',
            'objektif_ing' => 'Objektif Ing',
            'lab_hour' => 'Lab Hour',
            'tutorial_hour' => 'Tutorial Hour',
            'course_hour' => 'Course Hour',
            'course_hour_in_week' => 'Course Hour In Week',
            'lab_hour_in_week' => 'Lab Hour In Week',
            'number_week' => 'Number Week',
            'other_activity' => 'Other Activity',
            'other_activity_hour' => 'Other Activity Hour',
            'knowledge' => 'Knowledge',
            'skill' => 'Skill',
            'attitude' => 'Attitude',
            'uts' => 'Uts',
            'uas' => 'Uas',
            'tugas' => 'Tugas',
            'quiz' => 'Quiz',
            'whiteboard' => 'Whiteboard',
            'lcd' => 'Lcd',
            'courseware' => 'Courseware',
            'lab' => 'Lab',
            'elearning' => 'Elearning',
            'status' => 'Status',
            'prerequisites' => 'Prerequisites',
            'course_description' => 'Course Description',
            'course_objectives' => 'Course Objectives',
            'learning_outcomes' => 'Learning Outcomes',
            'course_format' => 'Course Format',
            'grading_procedure' => 'Grading Procedure',
            'course_content' => 'Course Content',
            'ref_kbk_id' => 'Ref Kbk ID',
            'web_page' => 'Web Page',
            'tahun_kurikulum_id' => 'Tahun Kurikulum ID',
            'ekstrakurikuler' => 'Ekstrakurikuler',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'course_group_id' => 'Course Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakAsistenTugasPraktikums()
    {
        return $this->hasMany(BaakAsistenTugasPraktikum::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakDosenMatakuliahs()
    {
        return $this->hasMany(BaakDosenMatakuliah::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKbk()
    {
        return $this->hasOne(BaakInstProdi::className(), ['ref_kbk_id' => 'ref_kbk_id']);
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
    public function getBaakPrklKrsDetails()
    {
        return $this->hasMany(BaakPrklKrsDetail::className(), ['kuliah_id' => 'kuliah_id']);
    }
    
    public function getNamaMatakuliah($kuliah_id){
        return Kuliah::findOne($kuliah_id);
    }
}
