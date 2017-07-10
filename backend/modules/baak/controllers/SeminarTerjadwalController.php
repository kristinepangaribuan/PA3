<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\SeminarTerjadwal;
use backend\modules\baak\models\search\SeminarTerjadwalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\baak\models\HeaderDetailDokumenBukti;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\Dosen;
use backend\modules\baak\models\TahunAjaran;
use backend\modules\baak\models\Semester;

/**
 * SeminarTerjadwalController implements the CRUD actions for SeminarTerjadwal model.
 */
class SeminarTerjadwalController extends Controller
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
     * Lists all SeminarTerjadwal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SeminarTerjadwalSearch();
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
        $this->redirect(['seminar-terjadwal-view', 'id'=>$id]);
    }
    
    public function actionSeminarTerjadwalUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiSeminarTerjadwal();
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/seminar_terjadwal/'.$id.'_'.$id_dokumen .$modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->seminar_terjadwal_id= $id;
            $modelDokumenBukti->nama_file = $id.'_'.$id_dokumen.$modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiSeminar = \backend\modules\baak\models\DokumenBuktiSeminarTerjadwal::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['seminar_terjadwal_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiSeminar) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['seminar-terjadwal-view', 'id' => $id]);
        } else {
            return $this->render('SeminarTerjadwalUpload', [
                        'modelDokumenBukti' => $modelDokumenBukti,
                        'model'=>$model,
                        'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionSeminarTerjadwalEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiSeminarTerjadwal::find()
                                            ->where(['seminar_terjadwal_id' => $model->seminar_terjadwal_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/seminar_terjadwal/'.$modelDokumenBukti->seminar_terjadwal_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['seminar-terjadwal-view', 'id' => $modelDokumenBukti->seminar_terjadwal_id]);
        } else {
            return $this->render('SeminarTerjadwalUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model'=>$model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionSeminarTerjadwalDownload($id) {
        $dokumenBuktiSeminarTerjadwal= \backend\modules\baak\models\DokumenBuktiSeminarTerjadwal::find()->where(['dokumen_bukti_seminar_terjadwal_id' => $id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path .$dokumenBuktiSeminarTerjadwal['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Displays a single SeminarTerjadwal model.
     * @param integer $id
     * @return mixed
     */
    public function actionSeminarTerjadwalView($id)
    {
        $model = $this->findModel($id);
        $queryHeaderDetailDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $queryDokumenBukti = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['seminar_terjadwal_id' => $id]);
        $dataProviderDokumenBukti = new ActiveDataProvider([
            'query' => $queryDokumenBukti,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'dosen_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        $dataProviderHeaderDetailDokumenBukti = new ActiveDataProvider([
            'query' => $queryHeaderDetailDokumen,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('SeminarTerjadwalView', [
            'model' => $model,
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
            'dataProviderHeaderDetailDokumenBukti' => $dataProviderHeaderDetailDokumenBukti,
        ]);
    }
    
    public function actionSeminarTerjadwalViewKprodiFrk($id)
    {
        $model = $this->findModel($id);
        $queryHeaderDetailDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $queryDokumenBukti = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['seminar_terjadwal_id' => $id]);
        $dataProviderDokumenBukti = new ActiveDataProvider([
            'query' => $queryDokumenBukti,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'dosen_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        $dataProviderHeaderDetailDokumenBukti = new ActiveDataProvider([
            'query' => $queryHeaderDetailDokumen,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('SeminarTerjadwalViewKprodiFrk', [
            'model' => $model,
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
            'dataProviderHeaderDetailDokumenBukti' => $dataProviderHeaderDetailDokumenBukti,
        ]);
    }

    /**
     * Creates a new SeminarTerjadwal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionSeminarTerjadwalAdd(){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new SeminarTerjadwal();
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $dataTahunAjaran = TahunAjaran::find()->asArray()->all();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['SeminarTerjadwal'];
            $model->header_dokumen_bukti_id = 6;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            if($model->jlh_mhs_seminar > 26){
                $model->jlh_sks_seminar = 2;
            }else{
                $model->jlh_sks_seminar = 1;
            }
            $model->save();
            if(isset($post['dosen_id'])){
                $postModelDosenSeminarTerjadwal = $post['dosen_id'];
                if (!empty($postModelDosenSeminarTerjadwal)) {
                    foreach ($postModelDosenSeminarTerjadwal as $key => $value) {
                        $newModelDosenSeminarTerjadwal = new \backend\modules\baak\models\DosenSeminarTerjadwal();
                        $newModelDosenSeminarTerjadwal->dosen_id = $value;
                        $newModelDosenSeminarTerjadwal->seminar_terjadwal_id= $model->seminar_terjadwal_id;
                        if(count($post['dosen_id'])>1){
                            $newModelDosenSeminarTerjadwal->jlh_sks_beban_kerja_dosen = ($model->jlh_sks_seminar/count($post['dosen_id']));
                        }else{
                            $newModelDosenSeminarTerjadwal->jlh_sks_beban_kerja_dosen = $model->jlh_sks_seminar;
                        }
                        $newModelDosenSeminarTerjadwal->save();
                    }
                }
            }
            return $this->redirect(['seminar-terjadwal-view', 'id' => $model->seminar_terjadwal_id]);
        } else {
            return $this->render('SeminarTerjadwalAdd', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'dataTahunAjaran'=>$dataTahunAjaran, 
                'semester'=> $semester, 
                'dosen' => $dosen,
            ]);
        }
    }
    
    /**
     * Updates an existing SeminarTerjadwal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSeminarTerjadwalEdit($id){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = $this->findModel($id);
        $model->tahun_ajaran = $model->semester->tahun_ajaran_id;
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $dataTahunAjaran = TahunAjaran::find()->asArray()->all();
        $post = Yii::$app->request->post();
        $rows = (new \yii\db\Query())
        ->select(['baak_dosen_seminar_terjadwal.dosen_id', 'nama_dosen'])
        ->from('baak_dosen_seminar_terjadwal')
        ->where(['seminar_terjadwal_id' => $id])
        ->join('INNER JOIN','baak_dosen','baak_dosen.dosen_id = baak_dosen_seminar_terjadwal.dosen_id')    
        ->all();
        $result = ArrayHelper::getColumn($rows, 'dosen_id');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['SeminarTerjadwal'];
            $postModelDosenSeminarTerjadwal = null;
            $model->header_dokumen_bukti_id = 6;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            if($model->jlh_mhs_seminar > 26){
                $model->jlh_sks_seminar = 2;
            }else{
                $model->jlh_sks_seminar = 1;
            }
            $model->save();
            \backend\modules\baak\models\DosenSeminarTerjadwal::deleteAll(['seminar_terjadwal_id'=>$id]);
            if(isset($post['dosen_id'])){
                $postModelDosenSeminarTerjadwal = $post['dosen_id'];
                if (!empty($postModelDosenSeminarTerjadwal)) {
                    foreach ($postModelDosenSeminarTerjadwal as $key => $value) {
                        $newModelDosenSeminarTerjadwal = new \backend\modules\baak\models\DosenSeminarTerjadwal();
                        $newModelDosenSeminarTerjadwal->dosen_id = $value;
                        $newModelDosenSeminarTerjadwal->seminar_terjadwal_id= $model->seminar_terjadwal_id;
                        if(count($post['dosen_id'])>1){
                            $newModelDosenSeminarTerjadwal->jlh_sks_beban_kerja_dosen = ($model->jlh_sks_seminar/count($post['dosen_id']));
                        }else{
                            $newModelDosenSeminarTerjadwal->jlh_sks_beban_kerja_dosen = $model->jlh_sks_seminar;
                        }
                        $newModelDosenSeminarTerjadwal->save();
                    }
                }
            }
            return $this->redirect(['seminar-terjadwal-view', 'id' => $model->seminar_terjadwal_id]);
        } else {
            return $this->render('SeminarTerjadwalEdit', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'dataTahunAjaran'=>$dataTahunAjaran,
                'result'=>$result,
                'semester' => $semester,
            ]);
        }
    }

    /**
     * Deletes an existing SeminarTerjadwal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSeminarTerjadwalDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the SeminarTerjadwal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SeminarTerjadwal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SeminarTerjadwal::find()->where(['seminar_terjadwal_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
