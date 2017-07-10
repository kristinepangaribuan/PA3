<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\ModulBahanAjar;
use backend\modules\baak\models\search\ModulBahanAjarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\Dosen;
use backend\modules\baak\models\DosenModulBahanAjar;
use backend\modules\baak\models\DokumenBuktiModul;
use backend\modules\baak\models\TahunAjaran;
use backend\modules\baak\models\Semester;
use yii\helpers\ArrayHelper;
use backend\modules\baak\models\HeaderDetailDokumenBukti;

/**
 * ModulBahanAjarController implements the CRUD actions for ModulBahanAjar model.
 */
class ModulBahanAjarController extends Controller
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
     * Lists all ModulBahanAjar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModulBahanAjarSearch();
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
        $this->redirect(['modul-bahan-ajar-view', 'id'=>$id]);
    }

    /**
     * Displays a single ModulBahanAjar model.
     * @param integer $id
     * @return mixed
     */
    
    public function actionModulBahanAjarView($id) {
        $model = $this->findModel($id);
        $query = \backend\modules\baak\models\DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id' => $id]);
        $queryDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_modul_bahan_ajar' => SORT_DESC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);

        $dataProviderDokumenBukti = new ActiveDataProvider([
            'query' => $queryDokumen,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('ModulBahanAjarView', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'dataProviderDokumenBukti' => $dataProviderDokumenBukti,
        ]);
    }
    
    public function actionModulBahanAjarViewKprodiFrk($id) {
        $model = $this->findModel($id);
        $query = \backend\modules\baak\models\DosenModulBahanAjar::find()->where(['modul_bahan_ajar_id' => $id]);
        $queryDokumen = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $model->header_dokumen_bukti_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_modul_bahan_ajar' => SORT_DESC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);

        $dataProviderDokumenBukti = new ActiveDataProvider([
            'query' => $queryDokumen,
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('ModulBahanAjarViewKprodiFrk', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'dataProviderDokumenBukti' => $dataProviderDokumenBukti,
        ]);
    }
    
    
    public function actionModulBahanAjarDownload($id) {
        $dokumenBuktiPenelitian = DokumenBuktiModul::find()->where(['dokumen_bukti_modul_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiPenelitian['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }
    
    public function actionModulBahanAjarUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiModul();
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/modul/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->modul_bahan_ajar_id = $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiModul = \backend\modules\baak\models\DokumenBuktiModul::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['modul_bahan_ajar_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiModul) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['modul-bahan-ajar-view', 'id' => $id]);
        } else {
            return $this->render('ModulBahanAjarUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }


    /**
     * Creates a new ModulBahanAjar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionModulBahanAjarAdd(){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new ModulBahanAjar();
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $post = Yii::$app->request->post();
        $tahapanModul = \backend\modules\baak\models\TahapanModul::find()->all();
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['ModulBahanAjar'];
            $model->header_dokumen_bukti_id = 15;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->save();
            $postModelDosenModulBahanAjar = null;
            if(isset($post['dosen_id'])){
                $postModelDosenModulBahanAjar = $post['dosen_id'];
            }else{
                $postModelDosenModulBahanAjar = null;
            }
            //Penelitian Sebagai Anggota
            if (!empty($postModelDosenModulBahanAjar)) {
                if(count($postModelDosenModulBahanAjar)>1){
                    foreach ($postModelDosenModulBahanAjar as $key => $value) {
                        $newDosenModulBahanAjar = new DosenModulBahanAjar();
                        $newDosenModulBahanAjar->dosen_id = $value;
                        $newDosenModulBahanAjar->modul_bahan_ajar_id= $model->modul_bahan_ajar_id;
                        if($value == $dosen['dosen_id']){
                            $newDosenModulBahanAjar->jabatan_dlm_modul_bahan_ajar = 'Penulish Utama';
                            $newDosenModulBahanAjar->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_modul;
                        }else{
                            $newDosenModulBahanAjar->jabatan_dlm_modul_bahan_ajar = 'Kontributor';
                            $newDosenModulBahanAjar->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_modul;
                        }
                        $newDosenModulBahanAjar->save();
                    }
                }else{
                    $newDosenModulBahanAjar = new DosenModulBahanAjar();
                    $newDosenModulBahanAjar->dosen_id = $dosen['dosen_id'];
                    $newDosenModulBahanAjar->modul_bahan_ajar_id= $model->modul_bahan_ajar_id;
                    $newDosenModulBahanAjar->jabatan_dlm_modul_bahan_ajar = 'Penulish Utama';
                    $newDosenModulBahanAjar->jlh_sks_beban_kerja_dosen = $model->jlh_sks_modul;
                    $newDosenModulBahanAjar->save();
                }
                
            }
            return $this->redirect(['modul-bahan-ajar-view', 'id' => $model->modul_bahan_ajar_id]);
        } else {
            return $this->render('ModulBahanAjarAdd', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'semester' => $semester,
                'dosen' => $dosen,
                'tahapanModul'=> $tahapanModul,
            ]);
        }
    }
    public function actionGetPersentasi($tahapan_modul_id){
        $modelTahapan = \backend\modules\baak\models\TahapanModul::findOne($tahapan_modul_id);
        echo \yii\helpers\Json::encode($modelTahapan);
    }

    /**
     * Updates an existing ModulBahanAjar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionModulBahanAjarEdit($id){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = $this->findModel($id);
        $model->tahapan_modul_id = $model->tahapanModul->tahapan_modul_id;
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $post = Yii::$app->request->post();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $tahapanModul = \backend\modules\baak\models\TahapanModul::find()->all();
        $rows = (new \yii\db\Query())
        ->select(['baak_dosen_modul_bahan_ajar.dosen_id', 'nama_dosen'])
        ->from('baak_dosen_modul_bahan_ajar')
        ->where(['modul_bahan_ajar_id' => $id])
        ->join('INNER JOIN','baak_dosen','baak_dosen.dosen_id = baak_dosen_modul_bahan_ajar.dosen_id')    
        ->all();
        $result = ArrayHelper::getColumn($rows, 'dosen_id');
        
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['ModulBahanAjar'];
            $postModelDosenModulBahanAjar = null;
//            $model->header_dokumen_bukti_id = 15;
            $model->status = 'Rencana Kerja';
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->save();
            DosenModulBahanAjar::deleteAll(['modul_bahan_ajar_id'=>$id]);
            $postModelDosenModulBahanAjar = null;
            if(isset($post['dosen_id'])){
                $postModelDosenModulBahanAjar = $post['dosen_id'];
            }else{
                $postModelDosenModulBahanAjar = null;
            }
            //Penelitian Sebagai Anggota
            if (!empty($postModelDosenModulBahanAjar)) {
                if(count($postModelDosenModulBahanAjar)>1){
                    foreach ($postModelDosenModulBahanAjar as $key => $value) {
                        $newDosenModulBahanAjar = new DosenModulBahanAjar();
                        $newDosenModulBahanAjar->dosen_id = $value;
                        $newDosenModulBahanAjar->modul_bahan_ajar_id= $model->modul_bahan_ajar_id;
                        if($value == $dosen['dosen_id']){
                            $newDosenModulBahanAjar->jabatan_dlm_modul_bahan_ajar = 'Penulish Utama';
                            $newDosenModulBahanAjar->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_modul;
                        }else{
                            $newDosenModulBahanAjar->jabatan_dlm_modul_bahan_ajar = 'Kontributor';
                            $newDosenModulBahanAjar->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_modul;
                        }
                        $newDosenModulBahanAjar->save();
                    }
                }else{
                    $newDosenModulBahanAjar = new DosenModulBahanAjar();
                    $newDosenModulBahanAjar->dosen_id = $dosen['dosen_id'];
                    $newDosenModulBahanAjar->modul_bahan_ajar_id= $model->modul_bahan_ajar_id;
                    $newDosenModulBahanAjar->jabatan_dlm_modul_bahan_ajar = 'Penulish Utama';
                    $newDosenModulBahanAjar->jlh_sks_beban_kerja_dosen = $model->jlh_sks_modul;
                    $newDosenModulBahanAjar->save();
                }
                
            }
            return $this->redirect(['modul-bahan-ajar-view', 'id' => $model->modul_bahan_ajar_id]);
        } else {
            return $this->render('ModulBahanAjarEdit', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'result' =>$result,
                'dosen' => $dosen,
                'semester' => $semester,
                'tahapanModul' => $tahapanModul,
            ]);
        }
    }
    
    public function actionModulBahanAjarEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiModul::find()
                                            ->where(['modul_bahan_ajar_id' => $model->modul_bahan_ajar_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/modul/'.$modelDokumenBukti->modul_bahan_ajar_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['modul-bahan-ajar-view', 'id' => $modelDokumenBukti->modul_bahan_ajar_id]);
        } else {
            return $this->render('ModulBahanAjarUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }

    /**
     * Deletes an existing ModulBahanAjar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionModulBahanAjarDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the ModulBahanAjar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModulBahanAjar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModulBahanAjar::find()->where(['modul_bahan_ajar_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
