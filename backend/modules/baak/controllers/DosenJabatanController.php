<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\DosenJabatan;
use backend\modules\baak\models\DosenJabatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\baak\models\InstStrukturJabatan;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\HeaderDetailDokumenBukti;

/**
 * DosenJabatanController implements the CRUD actions for DosenJabatan model.
 */
class DosenJabatanController extends Controller
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
     * Lists all DosenJabatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DosenJabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionProcessRealisasiFrk($id){
        $action=Yii::$app->request->post('action');
        $model = $this->findModel($id);
        if($action==1){
            $model->status_realisasi = 1;
            Yii::$app->session->setFlash('success', 'status frk sudah terealisasi');
        }else{
            $model->status_realisasi = 0;
        }
        $model->save();
        $this->redirect(['dosen-jabatan-view', 'id'=>$id]);
    }

    /**
     * Displays a single DosenJabatan model.
     * @param integer $id
     * @return mixed
     */
    public function actionDosenJabatanView($id)
    {
        $model = $this->findModel($id);
        $queryHeaderDetailDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        
        $dataProviderHeaderDetailDokumenBukti = new ActiveDataProvider([
            'query' => $queryHeaderDetailDokumen,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                ]
            ],
        ]);
        
        return $this->render('DosenJabatanView', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
        ]);
    }
    
    public function actionDosenJabatanViewKprodiFrk($id)
    {
        $model = $this->findModel($id);
        $queryHeaderDetailDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        
        $dataProviderHeaderDetailDokumenBukti = new ActiveDataProvider([
            'query' => $queryHeaderDetailDokumen,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        return $this->render('DosenJabatanViewKprodiFrk', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
        ]);
    }

    public function actionDosenJabatanUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiDosenJabatan();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/jabatan/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->dosen_jabatan_id= $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiJabatan = \backend\modules\baak\models\DokumenBuktiDosenJabatan::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['dosen_jabatan_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiJabatan) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['dosen-jabatan-view', 'id' => $id]);
        } else {
            return $this->render('DosenJabatanUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionDosenJabatanEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiDosenJabatan::find()
                                            ->where(['dosen_jabatan_id' => $model->dosen_jabatan_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/jabatan/'.$modelDokumenBukti->dosen_jabatan_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['dosen-jabatan-view', 'id' => $modelDokumenBukti->dosen_jabatan_id]);
        } else {
            return $this->render('DosenJabatanUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionDosenJabatanDownload($id) {
        $dokumenBuktiJabatan = \backend\modules\baak\models\DokumenBuktiDosenJabatan::find()->where(['dokumen_bukti_dosen_jabatan_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiJabatan['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Creates a new DosenJabatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDosenJabatanAdd()
    {
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id'=> Yii::$app->user->id])->one();
        $model = new DosenJabatan();
        $dataJabatan = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\InstStrukturJabatan::find()->all(), 'struktur_jabatan_id', 'jabatan');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post())) {
            $model->semester_id = $semester['semester_id'];
            $model->dosen_id = $dosen['dosen_id'];
            $model->header_dokumen_bukti_id = 31;
            $model->status = 'Rencana Kerja';
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['dosen-jabatan-view', 'id' => $model->dosen_jabatan_id]);
        } else {
            return $this->render('DosenJabatanAdd', [
                'model' => $model,
                'dataJabatan' => $dataJabatan,
                'semester' => $semester,
            ]);
        }
    }
    public function actionGetJabatan($idJabatan){  
        $struktur_jabatan = InstStrukturJabatan::findOne($idJabatan);      
        echo \yii\helpers\json::encode($struktur_jabatan);
    }

    /**
     * Updates an existing DosenJabatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDosenJabatanEdit($id)
    {
        $model = $this->findModel($id);
        $dataJabatan = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\InstStrukturJabatan::find()->all(), 'struktur_jabatan_id', 'jabatan');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post())) {
            $model->status = 'Rencana Kerja';
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['dosen-jabatan-view', 'id' => $model->dosen_jabatan_id]);
        } else {
            return $this->render('DosenJabatanEdit', [
                'model' => $model,
                'dataJabatan' => $dataJabatan,
                'semester' => $semester,
            ]);
        }
    }

    /**
     * Deletes an existing DosenJabatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDosenJabatanDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the DosenJabatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DosenJabatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DosenJabatan::find()->where(['dosen_jabatan_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
