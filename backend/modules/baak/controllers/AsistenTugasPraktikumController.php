<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\AsistenTugasPraktikum;
use backend\modules\baak\models\search\AsistenTugasPraktikumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\HeaderDetailDokumenBukti;

/**
 * AsistenTugasPraktikumController implements the CRUD actions for AsistenTugasPraktikum model.
 */
class AsistenTugasPraktikumController extends Controller
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
     * Lists all AsistenTugasPraktikum models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AsistenTugasPraktikumSearch();
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
        $this->redirect(['asisten-tugas-praktikum-view', 'id'=>$id]);
    }

    /**
     * Displays a single AsistenTugasPraktikum model.
     * @param integer $id
     * @return mixed
     */
    public function actionAsistenTugasPraktikumView($id)
    {
//        die($id);
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
        
        $queryKelasPraktikum= \backend\modules\baak\models\KelasPraktikum::find()->where(['asisten_tugas_praktikum_id' => $id]);
        $dataProviderKelasPraktikum= new ActiveDataProvider([
            'query' => $queryKelasPraktikum,
            'sort' => [
                'defaultOrder' => [
                    'kelas_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        
        $queryDosenAsistenPraktikum= \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['asisten_tugas_praktikum_id' => $id]);
        $dataProviderDosen= new ActiveDataProvider([
            'query' => $queryDosenAsistenPraktikum,
            'sort' => [
                'defaultOrder' => [
                    'dosen_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('AsistenTugasPraktikumView', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
            'dataProviderKelasPraktikum'=>$dataProviderKelasPraktikum,
            'dataProviderDosen' => $dataProviderDosen
        ]);
    }
    
    public function actionAsistenTugasPraktikumViewKprodiFrk($id)
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
        
        $queryKelasPraktikum= \backend\modules\baak\models\KelasPraktikum::find()->where(['asisten_tugas_praktikum_id' => $id]);
        $dataProviderKelasPraktikum= new ActiveDataProvider([
            'query' => $queryKelasPraktikum,
            'sort' => [
                'defaultOrder' => [
                    'kelas_id' => SORT_ASC,
                ]
            ],
        ]);
        
        $queryDosenAsistenPraktikum= \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['asisten_tugas_praktikum_id' => $id]);
        $dataProviderDosen= new ActiveDataProvider([
            'query' => $queryDosenAsistenPraktikum,
            'sort' => [
                'defaultOrder' => [
                    'dosen_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('AsistenTugasPraktikumViewKprodiFrk', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
            'dataProviderKelasPraktikum'=>$dataProviderKelasPraktikum,
            'dataProviderDosen' => $dataProviderDosen
        ]);
    }
    
    public function actionAsistenTugasPraktikumUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiAsisten();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/asisten_tugas_praktikum/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->asisten_tugas_praktikum_id= $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiAsisten = \backend\modules\baak\models\DokumenBuktiAsisten::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['asisten_tugas_praktikum_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiAsisten) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['asisten-tugas-praktikum-view', 'id' => $id]);
        } else {
            return $this->render('AsistenTugasPraktikumUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionAsistenTugasPraktikumEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiAsisten::find()
                                            ->where(['asisten_tugas_praktikum_id' => $model->asisten_tugas_praktikum_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/asisten_tugas_praktikum/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['asisten-tugas-praktikum-view', 'id' => $modelDokumenBukti->asisten_tugas_praktikum_id]);
        } else {
            return $this->render('AsistenTugasPraktikumUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionAsistenTugasPraktikumDownload($id) {
        $dokumenBuktiAsistenPraktikum= \backend\modules\baak\models\DokumenBuktiAsisten::find()->where(['dokumen_bukti_asisten_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiAsistenPraktikum['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Creates a new AsistenTugasPraktikum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAsistenTugasPraktikumAdd()
    {
        $user_id = Yii::$app->user->id;
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new AsistenTugasPraktikum();
        $dataKuliah = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kuliah::find()->all(), 'kuliah_id', 'nama_kul_ind');
        $dataKelas = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kelas::find()->all(),'kelas_id', 'nama');
        $dataDosen = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Dosen::find()->all(),'dosen_id', 'nama_dosen');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $prodi = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\InstProdi::find()->all(), 'ref_kbk_id', 'desc_ind');
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $kelas_id = $post['kelas_id'];
            $dosen_id = $post['dosen_id'];
            $jlh_mhs = \backend\modules\baak\models\DimxDim::find()->where(['IN', 'kelas_id', $kelas_id])->all();
            $jlh_total_mhs = count($jlh_mhs);
            $model->jlh_mhs_praktikum= $jlh_total_mhs;
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->deleted = 0;
            $model->semester_id = $semester->semester_id;
            $model->header_dokumen_bukti_id = 2;
            $model->status = 'Rencana Kerja';
            $model->save();
            //Kelas yang diajari
            if (!empty($kelas_id)) {
                foreach ($kelas_id as $key => $value) {
                    $newModelKelasPraktikum= new \backend\modules\baak\models\KelasPraktikum();
                    $newModelKelasPraktikum->asisten_tugas_praktikum_id = $model->asisten_tugas_praktikum_id;
                    $newModelKelasPraktikum->kelas_id= $value;
                    $newModelKelasPraktikum->save();
                }
            }
            
            if (!empty($dosen_id)) {
                foreach ($dosen_id as $key => $value) {
                    $newModelDosenAsisten = new \backend\modules\baak\models\DosenAsistenPraktikum();
                    $newModelDosenAsisten->dosen_id= $value;
                    $newModelDosenAsisten->asisten_tugas_praktikum_id= $model->asisten_tugas_praktikum_id;
                    $newModelDosenAsisten->jlh_sks_beban_kerja_dosen = $model->jlh_sks_asistensi/count($dosen_id);
                    $newModelDosenAsisten->save();
                }
            }
            
            return $this->redirect(['asisten-tugas-praktikum-view', 'id' => $model->asisten_tugas_praktikum_id]);
        } else {
            return $this->render('AsistenTugasPraktikumAdd', [
                'dosen'=>$dosen,
                'model' => $model,
                'dataKuliah' =>$dataKuliah,
                'dataKelas' => $dataKelas,
                'dataDosen' => $dataDosen,
                'semester'=>$semester,
                'prodi'=>$prodi,
            ]);
        }
    }

    /**
     * Updates an existing AsistenTugasPraktikum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAsistenTugasPraktikumEdit($id)
    {
        $user_id = Yii::$app->user->id;
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = $this->findModel($id);
        $dataKuliah = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kuliah::find()->all(), 'kuliah_id', 'nama_kul_ind');
        $dataKelas = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kelas::find()->all(),'kelas_id', 'nama');
        $dataDosen = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Dosen::find()->all(),'dosen_id', 'nama_dosen');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $rowsKelas = (new \yii\db\Query())
        ->select(['baak_kelas_praktikum.kelas_id', 'nama'])
        ->from('baak_kelas_praktikum')
        ->where(['asisten_tugas_praktikum_id' => $id])
        ->join('INNER JOIN','baak_adak_kelas','baak_kelas_praktikum.kelas_id = baak_adak_kelas.kelas_id')    
        ->all();
        $resultKelas = \yii\helpers\ArrayHelper::getColumn($rowsKelas, 'kelas_id');
        $rowsDosen = (new \yii\db\Query())
        ->select(['baak_dosen_asisten_praktikum.dosen_id', 'nama_dosen'])
        ->from('baak_dosen_asisten_praktikum')
        ->where(['asisten_tugas_praktikum_id' => $id])
        ->join('INNER JOIN','baak_dosen','baak_dosen.dosen_id = baak_dosen_asisten_praktikum.dosen_id')    
        ->all();
        $resultDosen = \yii\helpers\ArrayHelper::getColumn($rowsDosen, 'dosen_id');
        $prodi = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\InstProdi::find()->all(), 'ref_kbk_id', 'desc_ind');
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {
            $model->dosen_id = $dosen->dosen_id;
            $kelas_id = $post['kelas_id'];
            $dosen_id = $post['dosen_id'];
            $jlh_mhs = \backend\modules\baak\models\DimxDim::find()->where(['IN', 'kelas_id', $kelas_id])->all();
            $jlh_total_mhs = count($jlh_mhs);
            $model->jlh_mhs_praktikum= $jlh_total_mhs;
            $model->status = 'Rencana Kerja';
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->save();
            \backend\modules\baak\models\DosenAsistenPraktikum::deleteAll(['asisten_tugas_praktikum_id'=>$id]);
            \backend\modules\baak\models\KelasPraktikum::deleteAll(['asisten_tugas_praktikum_id'=>$id]);
            //Kelas yang diajari
            if (!empty($kelas_id)) {
                foreach ($kelas_id as $key => $value) {
                    $newModelKelasPraktikum= new \backend\modules\baak\models\KelasPraktikum();
                    $newModelKelasPraktikum->asisten_tugas_praktikum_id = $model->asisten_tugas_praktikum_id;
                    $newModelKelasPraktikum->kelas_id= $value;
                    $newModelKelasPraktikum->save();
                }
            }
            
            if (!empty($dosen_id)) {
                foreach ($dosen_id as $key => $value) {
                    $newModelDosenAsisten = new \backend\modules\baak\models\DosenAsistenPraktikum();
                    $newModelDosenAsisten->dosen_id= $value;
                    $newModelDosenAsisten->asisten_tugas_praktikum_id= $model->asisten_tugas_praktikum_id;
                    $newModelDosenAsisten->jlh_sks_beban_kerja_dosen = $model->jlh_sks_asistensi/count($dosen_id);
                    $newModelDosenAsisten->save();
                }
            }
            
            return $this->redirect(['asisten-tugas-praktikum-view', 'id' => $model->asisten_tugas_praktikum_id]);
        } else {
            return $this->render('AsistenTugasPraktikumEdit', [
                'dosen'=>$dosen,
                'model' => $model,
                'dataKuliah' =>$dataKuliah,
                'dataKelas' => $dataKelas,
                'dataDosen' => $dataDosen,
                'resultDosen' => $resultDosen,
                'resultKelas' => $resultKelas,
                'semester' => $semester,
                'prodi' => $prodi,
            ]);
        }
    }

    /**
     * Deletes an existing AsistenTugasPraktikum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAsistenTugasPraktikumDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
//        \backend\modules\baak\models\DosenAsistenPraktikum::deleteAll(['asisten_tugas_praktikum_id'=>$id]);
//        \backend\modules\baak\models\KelasPraktikum::deleteAll(['asisten_tugas_praktikum_id'=>$id]);
//        \backend\modules\baak\models\DokumenBuktiAsisten::deleteAll(['asisten_tugas_praktikum_id'=>$id]);
//        $this->findModel($id)->delete();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the AsistenTugasPraktikum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AsistenTugasPraktikum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AsistenTugasPraktikum::find()->where(['asisten_tugas_praktikum_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
