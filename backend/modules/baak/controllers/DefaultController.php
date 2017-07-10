<?php

namespace backend\modules\baak\controllers;

use yii\web\Controller;
use Yii;



/**
 * Default controller for the `baak` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
        return $this->redirect(['dosen/report', 'id'=>$dosen['dosen_id'], 'tahun_ajaran_id'=>$semester->tahunAjaran->tahun_ajaran_id, 'semester_id'=>$semester->semester_id]);
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionGetRefSks($ref_jlh_sks_id){
        $data = \backend\modules\baak\models\RefJlhSks::findOne($ref_jlh_sks_id);
        echo \yii\helpers\Json::encode($data);
    }
}
