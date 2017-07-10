<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\JurnalIlmiah;
use backend\modules\baak\models\search\JurnalIlmiahSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\modules\baak\models\Dosen;
use backend\modules\baak\models\TahunAjaran;
use backend\modules\baak\models\Semester;
use backend\modules\baak\models\DosenJurnalIlmiah;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\HeaderDetailDokumenBukti;
/**
 * JurnalIlmiahController implements the CRUD actions for JurnalIlmiah model.
 */
class JurnalIlmiahController extends Controller
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
     * Lists all JurnalIlmiah models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JurnalIlmiahSearch();
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
        $this->redirect(['jurnal-ilmiah-view', 'id'=>$id]);
    }
    /**
     * Displays a single JurnalIlmiah model.
     * @param integer $id
     * @return mixed
     */
    public function actionJurnalIlmiahView($id)
    {
        $model = $this->findModel($id);
        $queryHeaderDetailDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $queryDosenJurnal = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['jurnal_ilmiah_id' => $id]);
        $dataProviderJurnalIlmiah = new ActiveDataProvider([
            'query' => $queryDosenJurnal,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_jurnal_ilmiah' => SORT_DESC,
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
        return $this->render('JurnalIlmiahView', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
            'dataProviderJurnalIlmiah'=>$dataProviderJurnalIlmiah,
        ]);
    }
    
    public function actionJurnalIlmiahViewKprodiFrk($id)
    {
        $model = $this->findModel($id);
        $queryHeaderDetailDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $queryDosenJurnal = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['jurnal_ilmiah_id' => $id]);
        $dataProviderJurnalIlmiah = new ActiveDataProvider([
            'query' => $queryDosenJurnal,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_jurnal_ilmiah' => SORT_DESC,
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
        return $this->render('JurnalIlmiahViewKprodiFrk', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
            'dataProviderJurnalIlmiah'=>$dataProviderJurnalIlmiah,
        ]);
    }
    
    public function actionJurnalIlmiahUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiJurnalIlmiah();
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/jurnal_ilmiah/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->jurnal_ilmiah_id= $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
//            die();
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiJurnal = \backend\modules\baak\models\DokumenBuktiJurnalIlmiah::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['jurnal_ilmiah_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiJurnal) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['jurnal-ilmiah-view', 'id' => $id]);
        } else {
            return $this->render('JurnalIlmiahUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionJurnalIlmiahDownload($id) {
        $dokumenBuktiJurnalIlmiah= \backend\modules\baak\models\DokumenBuktiJurnalIlmiah::find()->where(['dokumen_bukti_jurnal_ilmiah_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiJurnalIlmiah['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Creates a new JurnalIlmiah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionJurnalIlmiahAdd()
    {
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new JurnalIlmiah();
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $dataTahunAjaran = TahunAjaran::find()->asArray()->all();
        $post = Yii::$app->request->post();
        $jlh_sks = 0;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['JurnalIlmiah'];
            $model->header_dokumen_bukti_id = 16;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            $penerbit = null;
            if($model->penerbit_jurnal_ilmiah==1){
                $penerbit = "Diterbitkan oleh Jurnal tidak terakreditasi";
            }else if($model->penerbit_jurnal_ilmiah==2){
                $penerbit = "Diterbitkan oleh Jurnal terakreditasi";
            }else if($model->penerbit_jurnal_ilmiah==3){
                $penerbit = "Diterbitkan oleh Jurnal terakreditasi internasional (dalam bahasa intenasional)";
            }
            $model->penerbit_jurnal_ilmiah = $penerbit;
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->save();
            $postModelDosenJurnalIlmiah = null;
            if(isset($post['dosen_id'])){
                $postModelDosenJurnalIlmiah = $post['dosen_id'];
            }else{
                $postModelDosenJurnalIlmiah = null;
            }
            if (!empty($postModelDosenJurnalIlmiah)) {
                if(count($postModelDosenJurnalIlmiah)>1){
                    foreach ($postModelDosenJurnalIlmiah as $key => $value) {
                        $newModelDosenJurnalIlmiah = new DosenJurnalIlmiah();
                        $newModelDosenJurnalIlmiah->jurnal_ilmiah_id = $model->jurnal_ilmiah_id;
                        $newModelDosenJurnalIlmiah->dosen_id = $value;
                        if($value==$dosen['dosen_id']){
                            $newModelDosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah= 'Ketua';
                            $newModelDosenJurnalIlmiah->jlh_sks_beban_dosen = 0.6*$model->jlh_sks_jurnal;
                        }else{
                            $newModelDosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah= 'Anggota';
                            $newModelDosenJurnalIlmiah->jlh_sks_beban_dosen = 0.4*$model->jlh_sks_jurnal;
                        }
                        $newModelDosenJurnalIlmiah->save();
                    }
                }else{
                    //jurnal Sebagai Ketua
                    $dosenJurnalIlmiah = new DosenJurnalIlmiah();
                    $dosenJurnalIlmiah->dosen_id = $dosen->dosen_id;
                    $dosenJurnalIlmiah->jurnal_ilmiah_id= $model->jurnal_ilmiah_id;
                    $dosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah = 'Ketua';
                    $dosenJurnalIlmiah->jlh_sks_beban_dosen = $model->jlh_sks_jurnal;
                    $dosenJurnalIlmiah->save();
                }
            }else{
                //jurnal Sebagai Ketua
                $dosenJurnalIlmiah = new DosenJurnalIlmiah();
                $dosenJurnalIlmiah->dosen_id = $dosen->dosen_id;
                $dosenJurnalIlmiah->jurnal_ilmiah_id= $model->jurnal_ilmiah_id;
                $dosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah = 'Ketua';
                $dosenJurnalIlmiah->jlh_sks_beban_dosen = $model->jlh_sks_jurnal;
                $dosenJurnalIlmiah->save();
            }
            return $this->redirect(['jurnal-ilmiah-view', 'id' => $model->jurnal_ilmiah_id]);
        } else {
            return $this->render('JurnalIlmiahAdd', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'semester'=>$semester,
                'dosen'=>$dosen,
            ]);
        }
    }

    /**
     * Updates an existing JurnalIlmiah model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionJurnalIlmiahEdit($id){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = $this->findModel($id);
        $rows = (new \yii\db\Query())
        ->select(['bd.dosen_id', 'nama_dosen'])
        ->from('baak_dosen_jurnal_ilmiah as bd')
        ->where(['jurnal_ilmiah_id' => $id])
        ->join('INNER JOIN','baak_dosen d','bd.dosen_id = d.dosen_id')    
        ->all();
        $result = ArrayHelper::getColumn($rows, 'dosen_id');
        $jlh_sks=0;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['JurnalIlmiah'];
            $model->header_dokumen_bukti_id = 12;
            $model->status = 'Rencana Kerja';
            $penerbit = null;
            if($model->penerbit_jurnal_ilmiah==1){
                $penerbit = "Diterbitkan oleh Jurnal tidak terakreditasi";
            }else if($model->penerbit_jurnal_ilmiah==2){
                $penerbit = "Diterbitkan oleh Jurnal terakreditasi";
            }else if($model->penerbit_jurnal_ilmiah==3){
                $penerbit = "Diterbitkan oleh Jurnal terakreditasi internasional (dalam bahasa intenasional)";
            }
            $model->penerbit_jurnal_ilmiah = $penerbit;
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->save();
            DosenJurnalIlmiah::deleteAll(['jurnal_ilmiah_id'=>$id]);
            $postModelDosenJurnalIlmiah = null;
            if(isset($post['dosen_id'])){
                $postModelDosenJurnalIlmiah = $post['dosen_id'];
            }else{
                $postModelDosenJurnalIlmiah = null;
            }
            if (!empty($postModelDosenJurnalIlmiah)) {
                if(count($postModelDosenJurnalIlmiah)>1){
                    foreach ($postModelDosenJurnalIlmiah as $key => $value) {
                        $newModelDosenJurnalIlmiah = new DosenJurnalIlmiah();
                        $newModelDosenJurnalIlmiah->jurnal_ilmiah_id = $model->jurnal_ilmiah_id;
                        $newModelDosenJurnalIlmiah->dosen_id = $value;
                        if($value==$dosen['dosen_id']){
                            $newModelDosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah= 'Ketua';
                            $newModelDosenJurnalIlmiah->jlh_sks_beban_dosen = 0.6*$model->jlh_sks_jurnal;
                        }else{
                            $newModelDosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah= 'Anggota';
                            $newModelDosenJurnalIlmiah->jlh_sks_beban_dosen = 0.4*$model->jlh_sks_jurnal;
                        }
                        $newModelDosenJurnalIlmiah->save();
                    }
                }else{
                    //jurnal Sebagai Ketua
                    $dosenJurnalIlmiah = new DosenJurnalIlmiah();
                    $dosenJurnalIlmiah->dosen_id = $dosen->dosen_id;
                    $dosenJurnalIlmiah->jurnal_ilmiah_id= $model->jurnal_ilmiah_id;
                    $dosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah = 'Ketua';
                    $dosenJurnalIlmiah->jlh_sks_beban_dosen = $model->jlh_sks_jurnal;
                    $dosenJurnalIlmiah->save();
                }
            }else{
                //jurnal Sebagai Ketua
                $dosenJurnalIlmiah = new DosenJurnalIlmiah();
                $dosenJurnalIlmiah->dosen_id = $dosen->dosen_id;
                $dosenJurnalIlmiah->jurnal_ilmiah_id= $model->jurnal_ilmiah_id;
                $dosenJurnalIlmiah->jabatan_dlm_jurnal_ilmiah = 'Ketua';
                $dosenJurnalIlmiah->jlh_sks_beban_dosen = $model->jlh_sks_jurnal;
                $dosenJurnalIlmiah->save();
            }
            return $this->redirect(['jurnal-ilmiah-view', 'id' => $model->jurnal_ilmiah_id]);
        } else {
            return $this->render('JurnalIlmiahEdit', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'semester'=>$semester,
                'result' =>$result,
                'dosen' => $dosen,
            ]);
        }
    }
    
    public function actionJurnalIlmiahEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiJurnalIlmiah::find()
                                            ->where(['jurnal_ilmiah_id' => $model->jurnal_ilmiah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/jurnal_ilmiah/'.$modelDokumenBukti->jurnal_ilmiah_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['jurnal-ilmiah-view', 'id' => $modelDokumenBukti->jurnal_ilmiah_id]);
        } else {
            return $this->render('JurnalIlmiahUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }

    /**
     * Deletes an existing JurnalIlmiah model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionJurnalIlmiahDel($id)
    {
//        DosenJurnalIlmiah::deleteAll(['jurnal_ilmiah_id'=>$id]);
//        \backend\modules\baak\models\DokumenBuktiJurnalIlmiah::deleteAll(['jurnal_ilmiah_id'=>$id]);
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the JurnalIlmiah model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JurnalIlmiah the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JurnalIlmiah::find()->where(['jurnal_ilmiah_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
