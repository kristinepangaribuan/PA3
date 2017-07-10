<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\PenugasanAsesor;
use backend\modules\baak\models\search\PenugasanAsesorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PenugasanAsesorController implements the CRUD actions for PenugasanAsesor model.
 */
class PenugasanAsesorController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PenugasanAsesor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PenugasanAsesorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'semester' => $semester,
        ]);
    }

    /**
     * Displays a single PenugasanAsesor model.
     * @param integer $id
     * @return mixed
     */
    public function actionPenugasanAsesorView($id)
    {
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $modelDosenAsesor = \backend\modules\baak\models\DosenAsesor::find()
                ->andWhere(['penugasan_asesor_id' => $id])
                ->andWhere(['semester_id' => $semester['semester_id']]);
        $dataProviderDosenAsesor = new \yii\data\ActiveDataProvider([
            'query' => $modelDosenAsesor,
        ]);
        return $this->render('PenugasanAsesorView', [
            'model' => $this->findModel($id),
            'dataProviderDosenAsesor'=>$dataProviderDosenAsesor,
            'semester' => $semester,
        ]);
    }
    
    public function actionPenugasanAsesorViewDosen($id)
    {
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $modelDosenAsesor = \backend\modules\baak\models\DosenAsesor::find()
                ->andWhere(['penugasan_asesor_id' => $id])
                ->andWhere(['semester_id' => $semester['semester_id']]);
        $dataProviderDosenAsesor = new \yii\data\ActiveDataProvider([
            'query' => $modelDosenAsesor,
        ]);
        return $this->render('PenugasanAsesorViewDosen', [
            'model' => $this->findModel($id),
            'dataProviderDosenAsesor'=>$dataProviderDosenAsesor,
            'semester' => $semester,
        ]);
    }

    /**
     * Creates a new PenugasanAsesor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPenugasanAsesorAdd()
    {
        $penugasanAsesor = PenugasanAsesor::find()->all();
        $dosenAsesor = \backend\modules\baak\models\DosenAsesor::find()->all();
        $idDosenAsesor = \yii\helpers\ArrayHelper::map($dosenAsesor, 'dosen_id', 'dosen_id');
        $idPenugasanDosen = \yii\helpers\ArrayHelper::map($penugasanAsesor, 'dosen_id', 'dosen_id');
        $idTemp = $idDosenAsesor + $idPenugasanDosen;
        $modelDosen = \backend\modules\baak\models\Dosen::find()->where(['NOT IN', 'dosen_id', $idTemp]);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $dataProviderDosen = new \yii\data\ActiveDataProvider([
            'query' =>$modelDosen,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'nama_dosen' => SORT_ASC,
                ]
            ],
        ]);
        if(Yii::$app->request->post('selection')){
            $selection=(array)Yii::$app->request->post('selection');//typecasting
            foreach($selection as $dosen_id){
                $model = new PenugasanAsesor();
                $model->dosen_id = $dosen_id;
                $model->semester_id = $semester['semester_id'];
                $model->save();
            }
            return $this->redirect(['index']);
        }else {
            return $this->render('PenugasanAsesorAdd', [
                'dataProviderDosen'=>$dataProviderDosen,
                'semester' => $semester,
            ]);
        }
    }
    
    public function actionPenugasanAsesorDosenAsesor($id)
    {
        $model = $this->findModel($id);
        $penugasanAsesor = PenugasanAsesor::find()->all();
        $dosenAsesor = \backend\modules\baak\models\DosenAsesor::find()->all();
        $idDosenAsesor = \yii\helpers\ArrayHelper::map($dosenAsesor, 'dosen_id', 'dosen_id');
        $idPenugasanDosen = \yii\helpers\ArrayHelper::map($penugasanAsesor, 'dosen_id', 'dosen_id');
        $idTemp = $idDosenAsesor + $idPenugasanDosen;
        $modelDosen = \backend\modules\baak\models\Dosen::find()
                ->where(['NOT IN', 'dosen_id', $idTemp]);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $dataProviderDosen = new \yii\data\ActiveDataProvider([
            'query' =>$modelDosen,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'nama_dosen' => SORT_ASC,
                ]
            ],
        ]);
        if(Yii::$app->request->post('selection')){
            $selection=(array)Yii::$app->request->post('selection');//typecasting
            foreach($selection as $dosen_id){
                $modelAsesor = new \backend\modules\baak\models\DosenAsesor();
                $modelAsesor->dosen_id = $dosen_id;
                $modelAsesor->penugasan_asesor_id = $id;
                $modelAsesor->semester_id = $semester['semester_id'];
                $modelAsesor->save();
            }
            return $this->redirect(['penugasan-asesor-view', 'id'=>$id]);
        }else {
            return $this->render('PenugasanAsesorDosenAsesor', [
                'dataProviderDosen'=>$dataProviderDosen,
                'semester' => $semester,
                'model'=>$model,
            ]);
        }
    }

    /**
     * Updates an existing PenugasanAsesor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->penugasan_asesor_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PenugasanAsesor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPenugasanAsesorDel($id)
    {
        \backend\modules\baak\models\DosenAsesor::deleteAll(['penugasan_asesor_id'=>$id]);
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    
    public function actionPenugasanAsesorDosenAsesorDel($id,$id_penugasan_asesor){
        \backend\modules\baak\models\DosenAsesor::findOne($id)->delete();
        return $this->redirect(['penugasan-asesor-view', 'id'=>$id_penugasan_asesor]);
    }

    /**
     * Finds the PenugasanAsesor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PenugasanAsesor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PenugasanAsesor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
