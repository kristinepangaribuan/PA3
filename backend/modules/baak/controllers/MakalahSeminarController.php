<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\MakalahSeminar;
use backend\modules\baak\models\search\MakalahSeminarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\modules\baak\models\Dosen;
use backend\modules\baak\models\DokumenBuktiMakalahSeminar;
use backend\modules\baak\models\TahunAjaran;
use backend\modules\baak\models\Semester;
use backend\modules\baak\models\DosenMakalahSeminar;
use backend\modules\baak\models\HeaderDetailDokumenBukti;
use yii\data\ActiveDataProvider;

/**
 * MakalahSeminarController implements the CRUD actions for MakalahSeminar model.
 */
class MakalahSeminarController extends Controller
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
     * Lists all MakalahSeminar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MakalahSeminarSearch();
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
        $this->redirect(['makalah-seminar-view', 'id'=>$id]);
    }

    /**
     * Displays a single MakalahSeminar model.
     * @param integer $id
     * @return mixed
     */
    public function actionMakalahSeminarView($id)
    {
        $model = $this->findModel($id);
        $queryDosenMakalahSeminar = DosenMakalahSeminar::find()->where(['makalah_seminar_id' => $id]);
        $queryHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $dataProviderDosenMakalahSeminar = new ActiveDataProvider([
            'query' => $queryDosenMakalahSeminar,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_makalah_seminar' => SORT_DESC,
                ]
            ],
        ]);

        $dataProviderDokumenBukti = new ActiveDataProvider([
            'query' => $queryHeaderDetailDokumenBukti,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('MakalahSeminarView', [
            'model' => $model,
            'dataProviderDosenMakalahSeminar' => $dataProviderDosenMakalahSeminar,
            'dataProviderDokumenBukti' => $dataProviderDokumenBukti,
        ]);
    }
    
    public function actionMakalahSeminarViewKprodiFrk($id)
    {
        $model = $this->findModel($id);
        $queryDosenMakalahSeminar = DosenMakalahSeminar::find()->where(['makalah_seminar_id' => $id]);
        $queryHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $dataProviderDosenMakalahSeminar = new ActiveDataProvider([
            'query' => $queryDosenMakalahSeminar,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_makalah_seminar' => SORT_DESC,
                ]
            ],
        ]);

        $dataProviderHeaderDetailDokumenBukti = new ActiveDataProvider([
            'query' => $queryHeaderDetailDokumenBukti,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('MakalahSeminarViewKprodiFrk', [
            'model' => $model,
            'dataProviderDosenMakalahSeminar' => $dataProviderDosenMakalahSeminar,
            'dataProviderHeaderDetailDokumenBukti' => $dataProviderHeaderDetailDokumenBukti,
        ]);
    }

    /**
     * Creates a new MakalahSeminar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMakalahSeminarAdd(){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new MakalahSeminar();
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $post = Yii::$app->request->post();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $tingkatan_makalah = null;
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['MakalahSeminar'];
            $model->header_dokumen_bukti_id = 20;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            if($model->tingkatan_makalah == 1){
                $tingkatan_makalah = "Tingkat Regional/ minimal fakultas";
            }else if($model->tingkatan_makalah == 2){
                $tingkatan_makalah = "Tingkat nasional";
            }else if($model->tingkatan_makalah == 3){
                $tingkatan_makalah = "Tingkat internasional";
            }
            $model->tingkatan_makalah = $tingkatan_makalah;
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->save();
            $postModelDosenMakalahSeminar = null;
            if(isset($post['dosen_id'])){
                $postModelDosenMakalahSeminar = $post['dosen_id'];
            }else{
                $postModelDosenMakalahSeminar = null;
            }
//          Penelitian Sebagai Anggota
            if (!empty($postModelDosenMakalahSeminar)) {
                if(count($postModelDosenMakalahSeminar)>1){
                    foreach ($postModelDosenMakalahSeminar as $key => $value) {
                        $newModelDosenMakalahSeminar = new DosenMakalahSeminar();
                        $newModelDosenMakalahSeminar->dosen_id = $value;
                        $newModelDosenMakalahSeminar->makalah_seminar_id= $model->makalah_seminar_id;
                        if($value == $dosen['dosen_id']){
                            $newModelDosenMakalahSeminar->jabatan_dlm_makalah_seminar= 'Ketua';
                            $newModelDosenMakalahSeminar->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_makalah_seminar;
                        }else{
                            $newModelDosenMakalahSeminar->jabatan_dlm_makalah_seminar = 'Anggota';
                            $newModelDosenMakalahSeminar->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_makalah_seminar;
                        }
                        $newModelDosenMakalahSeminar->save();
                    }  
                }else{
                    $dosenMakalahSeminar = new DosenMakalahSeminar();
                    $dosenMakalahSeminar->dosen_id = $dosen->dosen_id;
                    $dosenMakalahSeminar->makalah_seminar_id = $model->makalah_seminar_id;
                    $dosenMakalahSeminar->jabatan_dlm_makalah_seminar= 'Ketua';
                    $dosenMakalahSeminar->jlh_sks_beban_kerja_dosen = $model->jlh_sks_makalah_seminar;
                    $dosenMakalahSeminar->save();
                }
            }else{
                $dosenMakalahSeminar = new DosenMakalahSeminar();
                $dosenMakalahSeminar->dosen_id = $dosen->dosen_id;
                $dosenMakalahSeminar->makalah_seminar_id = $model->makalah_seminar_id;
                $dosenMakalahSeminar->jabatan_dlm_makalah_seminar= 'Ketua';
                $dosenMakalahSeminar->jlh_sks_beban_kerja_dosen = $model->jlh_sks_makalah_seminar;
                $dosenMakalahSeminar->save();
            }
            return $this->redirect(['makalah-seminar-view', 'id' => $model->makalah_seminar_id]);
        } else {
            return $this->render('MakalahSeminarAdd', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'semester' => $semester,
                'dosen' => $dosen,
            ]);
        }
    }

    /**
     * Updates an existing MakalahSeminar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMakalahSeminarEdit($id){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = $this->findModel($id);
        $rows = (new \yii\db\Query())
        ->select(['baak_dosen_makalah_seminar.dosen_id', 'nama_dosen'])
        ->from('baak_dosen_makalah_seminar')
        ->where(['makalah_seminar_id' => $id])
        ->join('INNER JOIN','baak_dosen','baak_dosen.dosen_id = baak_dosen_makalah_seminar.dosen_id')    
        ->all();
        $result = ArrayHelper::getColumn($rows, 'dosen_id');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $post = Yii::$app->request->post();
        $tingkatan_makalah = $model->tingkatan_makalah;
        if($tingkatan_makalah=='Tingkat Regional/ minimal fakultas'){
            $model->tingkatan_makalah = 1;
        }else if($tingkatan_makalah=='Tingkat nasional'){
            $model->tingkatan_makalah = 2;
        }else{
            $model->tingkatan_makalah = 3;
        }
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['MakalahSeminar'];
            $model->status = 'Rencana Kerja';
            //Penelitian Sebagai Ketua
            if($model->tingkatan_makalah == 1){
                $tingkatan_makalah = "Tingkat Regional/ minimal fakultas";
            }else if($model->tingkatan_makalah == 2){
                $tingkatan_makalah = "Tingkat nasional";
            }else if($model->tingkatan_makalah == 3){
                $tingkatan_makalah = "Tingkat internasional";
            }
            $model->tingkatan_makalah = $tingkatan_makalah;
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->save();
            DosenMakalahSeminar::deleteAll(['makalah_seminar_id'=>$id]);
            $postModelDosenMakalahSeminar = null;
            if(isset($post['dosen_id'])){
                $postModelDosenMakalahSeminar = $post['dosen_id'];
            }else{
                $postModelDosenMakalahSeminar = null;
            }
//          Penelitian Sebagai Anggota
            if (!empty($postModelDosenMakalahSeminar)) {
                if(count($postModelDosenMakalahSeminar)>1){
                    foreach ($postModelDosenMakalahSeminar as $key => $value) {
                        $newModelDosenMakalahSeminar = new DosenMakalahSeminar();
                        $newModelDosenMakalahSeminar->dosen_id = $value;
                        $newModelDosenMakalahSeminar->makalah_seminar_id= $model->makalah_seminar_id;
                        if($value == $dosen['dosen_id']){
                            $newModelDosenMakalahSeminar->jabatan_dlm_makalah_seminar= 'Ketua';
                            $newModelDosenMakalahSeminar->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_makalah_seminar;
                        }else{
                            $newModelDosenMakalahSeminar->jabatan_dlm_makalah_seminar = 'Anggota';
                            $newModelDosenMakalahSeminar->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_makalah_seminar;
                        }
                        $newModelDosenMakalahSeminar->save();
                    }  
                }else{
                    $dosenMakalahSeminar = new DosenMakalahSeminar();
                    $dosenMakalahSeminar->dosen_id = $dosen->dosen_id;
                    $dosenMakalahSeminar->makalah_seminar_id = $model->makalah_seminar_id;
                    $dosenMakalahSeminar->jabatan_dlm_makalah_seminar= 'Ketua';
                    $dosenMakalahSeminar->jlh_sks_beban_kerja_dosen = $model->jlh_sks_makalah_seminar;
                    $dosenMakalahSeminar->save();
                }
            }else{
                $dosenMakalahSeminar = new DosenMakalahSeminar();
                $dosenMakalahSeminar->dosen_id = $dosen->dosen_id;
                $dosenMakalahSeminar->makalah_seminar_id = $model->makalah_seminar_id;
                $dosenMakalahSeminar->jabatan_dlm_makalah_seminar= 'Ketua';
                $dosenMakalahSeminar->jlh_sks_beban_kerja_dosen = $model->jlh_sks_makalah_seminar;
                $dosenMakalahSeminar->save();
            }
            return $this->redirect(['makalah-seminar-view', 'id' => $model->makalah_seminar_id]);
        } else {
            return $this->render('MakalahSeminarEdit', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'semester'=>$semester,
                'result' =>$result,
                'dosen' => $dosen,
            ]);
        }
    }
    
    public function actionMakalahSeminarUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
//        Yii::$app->params['uploadPath'] = realpath(Yii::$app->basePath) . '/web/uploads/makalah_seminar/';
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        $modelDokumenBukti = new DokumenBuktiMakalahSeminar();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/makalah_seminar/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->makalah_seminar_id = $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiMakalahSeminar = \backend\modules\baak\models\DokumenBuktiMakalahSeminar::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['makalah_seminar_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiMakalahSeminar) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['makalah-seminar-view', 'id' => $id]);
        } else {
            return $this->render('MakalahSeminarUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionMakalahSeminarDownload($id) {
        $dokumenBuktiMakalahSeminar = \backend\modules\baak\models\DokumenBuktiMakalahSeminar::find()->where(['dokumen_bukti_makalah_seminar_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiMakalahSeminar['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }
    
    public function actionMakalahSeminarEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiMakalahSeminar::find()->where(['makalah_seminar_id' => $model->makalah_seminar_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/makalah_seminar/'.$modelDokumenBukti->makalah_seminar_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['makalah-seminar-view', 'id' => $modelDokumenBukti->makalah_seminar_id]);
        } else {
            return $this->render('MakalahSeminarUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }

    /**
     * Deletes an existing MakalahSeminar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMakalahSeminarDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the MakalahSeminar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MakalahSeminar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MakalahSeminar::find()->where(['makalah_seminar_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
