<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\BimbinganKuliah;
use backend\modules\baak\models\search\BimbinganKuliahSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\modules\baak\models\HeaderDetailDokumenBukti;
use yii\filters\VerbFilter;

/**
 * BimbinganKuliahController implements the CRUD actions for BimbinganKuliah model.
 */
class BimbinganKuliahController extends Controller
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
     * Lists all BimbinganKuliah models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BimbinganKuliahSearch();
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
        $this->redirect(['bimbingan-kuliah-view', 'id'=>$id]);
    }

    /**
     * Displays a single BimbinganKuliah model.
     * @param integer $id
     * @return mixed
     */
    public function actionBimbinganKuliahView($id)
    {
        $model  = $this->findModel($id);
        $DokumenBuktiBimbingaKuliah = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->jenisBimbingan->header_dokumen_bukti_id]);
        $dataProviderDokumenBukti = new \yii\data\ActiveDataProvider([
            'query'=>$DokumenBuktiBimbingaKuliah,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('BimbinganKuliahView', [
            'model' => $this->findModel($id),
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
        ]);
    }
    
    public function actionBimbinganKuliahViewKprodiFrk($id)
    {
        $model  = $this->findModel($id);
        $DokumenBuktiBimbingaKuliah = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->jenisBimbingan->header_dokumen_bukti_id]);
//        var_dump($DokumenBuktiMediaMassa);
//        die();
        $dataProviderDokumenBukti = new \yii\data\ActiveDataProvider([
            'query'=>$DokumenBuktiBimbingaKuliah,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('BimbinganKuliahViewKprodiFrk', [
            'model' => $this->findModel($id),
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
        ]);
    }


    /**
     * Creates a new BimbinganKuliah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBimbinganKuliahAdd()
    {
        $tahunAjaran = \backend\modules\baak\models\TahunAjaran::find()->all();
        $jenisBimbingan = \backend\modules\baak\models\JenisBimbingan::find()->all();
        $model = new BimbinganKuliah();
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id' => Yii::$app->user->id])->one();
        $dosen_id = $dosen['dosen_id'];
        $jlh_sks_default =1; 
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->semester_id = $semester['semester_id'];
            $model->status= "Rencana Kerja";
            $model->dosen_id = $dosen_id;
            if($model->jenis_bimbingan_id==4 || $model->jenis_bimbingan_id==5 || $model->jenis_bimbingan_id==6){
                //bimbingan TA/skripsi/karya tulis ilmiah
                if($model->jlh_mhs_bimbingan_kuliah >6){
                    $jlh_sks_default = 1;
                }else{
                    $jlh_sks_default = ($model->jlh_mhs_bimbingan_kuliah/6);
                }
            }else if($model->jenis_bimbingan_id==7){
                //bimbingan tesis
                if($model->jlh_mhs_bimbingan_kuliah > 3){
                    $jlh_sks_default = 1;
                }else{
                    $jlh_sks_default = ($model->jlh_mhs_bimbingan_kuliah/3);
                }
            }else if($model->jenis_bimbingan_id==8){
                //bimbingan disertasi
                if($model->jlh_mhs_bimbingan_kuliah > 2){
                    $jlh_sks_default = 1;
                }else{
                    $jlh_sks_default = ($model->jlh_mhs_bimbingan_kuliah/2);
                }
            }
            $model->jlh_sks_bimbingan_kuliah = $jlh_sks_default;
            $model->save();
            return $this->redirect(['bimbingan-kuliah-view', 'id' => $model->bimbingan_kuliah_id]);
        } else {
            return $this->render('BimbinganKuliahAdd', [
                'model' => $model,
                'tahunAjaran'=>$tahunAjaran,
                'jenisBimbingan'=>$jenisBimbingan,
                'semester' => $semester,
            ]);
        }
    }
    
    public function actionBimbinganKuliahDownload($id) {
        $dokumenBuktiBimbinganKuliah = \backend\modules\baak\models\DokumenBuktiBimbinganKuliah::find()->where(['dokumen_bukti_bimbingan_kuliah_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiBimbinganKuliah['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Updates an existing BimbinganKuliah model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBimbinganKuliahEdit($id)
    {
        $model = $this->findModel($id);
        $tahunAjaran = \backend\modules\baak\models\TahunAjaran::find()->all();
        $jenisBimbingan = \backend\modules\baak\models\JenisBimbingan::find()->all();
        $jlh_sks_default =1; 
        $model->tahun_ajaran = $model->semester->tahun_ajaran_id;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            if($model->jenis_bimbingan_id==4 || $model->jenis_bimbingan_id==5 || $model->jenis_bimbingan_id==6){
                //bimbingan TA/skripsi/karya tulis ilmiah
                if($model->jlh_mhs_bimbingan_kuliah >6){
                    $jlh_sks_default = 1;
                }else{
                    $jlh_sks_default = ($model->jlh_mhs_bimbingan_kuliah/6);
                }
            }else if($model->jenis_bimbingan_id==7){
                //bimbingan tesis
                if($model->jlh_mhs_bimbingan_kuliah > 3){
                    $jlh_sks_default = 1;
                }else{
                    $jlh_sks_default = ($model->jlh_mhs_bimbingan_kuliah/3);
                }
            }else if($model->jenis_bimbingan_id==8){
                //bimbingan disertasi
                if($model->jlh_mhs_bimbingan_kuliah > 2){
                    $jlh_sks_default = 1;
                }else{
                    $jlh_sks_default = ($model->jlh_mhs_bimbingan_kuliah/2);
                }
            }
            $model->jlh_sks_bimbingan_kuliah = $jlh_sks_default;
            $model->save();
            Yii::$app->session->setFlash('success', 'Anda behasil mengedit data bimbingan');
            return $this->redirect(['bimbingan-kuliah-view', 'id' => $model->bimbingan_kuliah_id]);
        } else {
            return $this->render('BimbinganKuliahEdit', [
                'model' => $model,
                'tahunAjaran'=>$tahunAjaran,
                'jenisBimbingan'=>$jenisBimbingan,
                'semester' => $semester,
            ]);
        }
    }
    
    public function actionBimbinganKuliahUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        Yii::$app->params['uploadPath'] = realpath(Yii::$app->basePath) . '/web/uploads/bimbingan_kuliah/';
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiBimbinganKuliah();
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/bimbingan_kuliah/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->bimbingan_kuliah_id= $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->jenisBimbingan->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiBimbingan = \backend\modules\baak\models\DokumenBuktiBimbinganKuliah::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['bimbingan_kuliah_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiBimbingan) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['bimbingan-kuliah-view', 'id' => $id]);
        } else {
            return $this->render('BimbinganKuliahUpload', [
                        'modelDokumenBukti' => $modelDokumenBukti,
                        'model' => $model,
                        'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionBimbinganKuliahEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiBimbinganKuliah::find()
                                            ->where(['bimbingan_kuliah_id' => $model->bimbingan_kuliah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/bimbingan_kuliah/'.$modelDokumenBukti->bimbingan_kuliah_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['bimbingan-kuliah-view', 'id' => $modelDokumenBukti->bimbingan_kuliah_id]);
        } else {
            return $this->render('BimbinganKuliahUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }

    /**
     * Deletes an existing BimbinganKuliah model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBimbinganKuliahDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
//        Yii::$app->session->setFlash('success', 'Anda behasil menghapus data bimbingan');
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the BimbinganKuliah model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BimbinganKuliah the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BimbinganKuliah::find()->where(['bimbingan_kuliah_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
