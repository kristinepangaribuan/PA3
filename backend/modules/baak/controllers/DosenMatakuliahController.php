<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\DosenMatakuliah;
use backend\modules\baak\models\search\DosenMatakuliahSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\HeaderDetailDokumenBukti;
use ZipArchive;

/**
 * DosenMatakuliahController implements the CRUD actions for DosenMatakuliah model.
 */
class DosenMatakuliahController extends Controller
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
     * Lists all DosenMatakuliah models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DosenMatakuliahSearch();
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
        $this->redirect(['dosen-matakuliah-view', 'id'=>$id]);
    }

    /**
     * Displays a single DosenMatakuliah model.
     * @param integer $id
     * @return mixed
     */
    public function actionDosenMatakuliahView($id)
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
        
        $queryKelasPengajaran= \backend\modules\baak\models\KelasPengajaran::find()->where(['dosen_matakuliah_id' => $id]);
        
        $dataProviderKelasPengajaran= new ActiveDataProvider([
            'query' => $queryKelasPengajaran,
            'sort' => [
                'defaultOrder' => [
                    'kelas_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('DosenMatakuliahView', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
            'dataProviderKelasPengajaran'=>$dataProviderKelasPengajaran,
        ]);
    }
    
    public function actionDosenMatakuliahViewKprodiFrk($id)
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
        
        $queryKelasPengajaran= \backend\modules\baak\models\KelasPengajaran::find()->where(['dosen_matakuliah_id' => $id]);
        
        $dataProviderKelasPengajaran= new ActiveDataProvider([
            'query' => $queryKelasPengajaran,
            'sort' => [
                'defaultOrder' => [
                    'kelas_id' => SORT_ASC,
                //'title' => SORT_ASC, 
                ]
            ],
        ]);
        return $this->render('DosenMatakuliahViewKprodiFrk', [
            'model' => $model,
            'dataProviderHeaderDetailDokumenBukti'=>$dataProviderHeaderDetailDokumenBukti,
            'dataProviderKelasPengajaran'=>$dataProviderKelasPengajaran,
        ]);
    }
    
    public function actionDosenMatakuliahUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiDosenMatakuliah();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/matakuliah/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->dosen_matakuliah_id= $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiMatakuliah = \backend\modules\baak\models\DokumenBuktiDosenMatakuliah::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['dosen_matakuliah_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiMatakuliah) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['dosen-matakuliah-view', 'id' => $id]);
        } else {
            return $this->render('DosenMatakuliahUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionDosenMatakuliahEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiDosenMatakuliah::find()
                                            ->where(['dosen_matakuliah_id' => $model->dosen_matakuliah_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/matakuliah/'.$modelDokumenBukti->dosen_matakuliah_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['dosen-matakuliah-view', 'id' => $modelDokumenBukti->dosen_matakuliah_id]);
        } else {
            return $this->render('DosenMatakuliahUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionDosenMatakuliahDownload($id) {
        $dokumenBuktiMatakuliah = \backend\modules\baak\models\DokumenBuktiDosenMatakuliah::find()->where(['dokumen_bukti_dosen_matakuliah_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiMatakuliah['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }
    /**
     * Creates a new DosenMatakuliah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDosenMatakuliahAdd()
    {
        $user_id = Yii::$app->user->id;
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new DosenMatakuliah();
        $dataKuliah = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kuliah::find()->where(['ref_kbk_id'=>$dosen['ref_kbk_id']])->all(), 'kuliah_id', 'nama_kul_ind');
        $dataKelas = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kelas::find()->all(),'kelas_id', 'nama');
        $post = Yii::$app->request->post();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $prodi = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\InstProdi::find()->all(), 'ref_kbk_id', 'desc_ind');
        if ($model->load(Yii::$app->request->post())) {
            $model->dosen_id = $dosen->dosen_id;
            $kelas_id = $post['kelas_id'];
            $jlh_mhs = \backend\modules\baak\models\DimxDim::find()->where(['IN', 'kelas_id', $kelas_id])->all();
            $jlh_total_mhs = count($jlh_mhs);
            $model->jlh_mhs_matakuliah = $jlh_total_mhs;
            $model->semester_id = $semester->semester_id;
            $model->header_dokumen_bukti_id = 1;
            $model->status = "Rencana Kerja";
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->save();
            //Kelas yang diajari
            if (!empty($kelas_id)) {
                foreach ($kelas_id as $key => $value) {
                    $newModelKelasPengajaran = new \backend\modules\baak\models\KelasPengajaran();
                    $newModelKelasPengajaran->dosen_matakuliah_id= $model->dosen_matakuliah_id;
                    $newModelKelasPengajaran->kelas_id= $value;
                    $newModelKelasPengajaran->save();
                }
            }
            return $this->redirect(['dosen-matakuliah-view', 'id' => $model->dosen_matakuliah_id]);
        } else {
            return $this->render('DosenMatakuliahAdd', [
                'model' => $model,
                'dataKuliah' => $dataKuliah,
                'dataKelas' => $dataKelas,
                'semester' => $semester,
                'prodi' => $prodi,
            ]);
        }
    }
    
    public function actionGetMatakuliah($kuliah_id){
        $data = \backend\modules\baak\models\Kuliah::findOne($kuliah_id);
        echo \yii\helpers\Json::encode($data);
    }
    
    public function actionGetMahasiswa(array $idKelas){
        $data = \backend\modules\baak\models\DimxDim::find()->where(['IN', 'kelas_id', $idKelas])->all();
        echo \yii\helpers\Json::encode(count($data));
    }
    
    public function actionGet() {
        $request = Yii::$app->request;
        $obj = $request->post('obj');
        $value = $request->post('value');
        $select = null;
        $id = null;
        $name = null;
        $data = null;
        //echo $obj;
        switch ($obj){
            case 'ref_kbk_id':
                $data = \backend\modules\baak\models\Kuliah::find()->where([$obj=>$value])->all();
                $id = 'kuliah_id';
                $name = 'nama_kul_ind';
                $select = 'Pilih Matakuliah...';
                break;
            case 'kuliah_id':
                $krsDetail = \backend\modules\baak\models\KrsDetail::find()->where([$obj => $value])->all();
                $krsIdDetail = \yii\helpers\ArrayHelper::map($krsDetail, 'krs_mhs_id', 'krs_mhs_id');
                $krsMHS    = \backend\modules\baak\models\KrsMhs::find()->where(['IN', 'krs_mhs_id', $krsIdDetail])->all();
                $krsIdMHS = \yii\helpers\ArrayHelper::map($krsMHS, 'dim_id','dim_id');
                $dimMHS = \backend\modules\baak\models\DimxDim::find()->where(['IN', 'dim_id', $krsIdMHS])->all();
                $kelasId = \yii\helpers\ArrayHelper::map($dimMHS, 'kelas_id','kelas_id');
                $data = \backend\modules\baak\models\Kelas::find()->where(['IN','kelas_id', $kelasId])->all();
                $id = 'kelas_id';
                $name = 'nama';
                $select = 'Pilih Kelas...';
                break;
        }
        $tagOptions = ['prompt' => $select];
        return \yii\helpers\Html::renderSelectOptions([], \yii\helpers\ArrayHelper::map($data, $id, $name), $tagOptions);
    }
    /**
     * Updates an existing DosenMatakuliah model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDosenMatakuliahEdit($id)
    {
        $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id'=>$user_id])->one();
        $dataKuliah = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kuliah::find()->where(['ref_kbk_id'=>$dosen['ref_kbk_id']])->all(), 'kuliah_id', 'nama_kul_ind');
        $dataKelas = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Kelas::find()->all(),'kelas_id', 'nama');
        $rows = (new \yii\db\Query())
        ->select(['baak_kelas_pengajaran.kelas_id', 'nama'])
        ->from('baak_kelas_pengajaran')
        ->where(['dosen_matakuliah_id' => $id])
        ->join('INNER JOIN','baak_adak_kelas','baak_kelas_pengajaran.kelas_id = baak_adak_kelas.kelas_id')    
        ->all();
        $result = \yii\helpers\ArrayHelper::getColumn($rows, 'kelas_id');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $post = Yii::$app->request->post();
        $prodi = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\InstProdi::find()->all(), 'ref_kbk_id', 'desc_ind');
        if ($model->load(Yii::$app->request->post())) {
            $kelas_id = $post['kelas_id'];
            $jlh_mhs = \backend\modules\baak\models\DimxDim::find()->where(['IN', 'kelas_id', $kelas_id])->all();
            $jlh_total_mhs = count($jlh_mhs);
            $model->jlh_mhs_matakuliah = $jlh_total_mhs;
            $model->status = "Rencana Kerja";
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->save();
            \backend\modules\baak\models\KelasPengajaran::deleteAll(['dosen_matakuliah_id'=>$id]);
            //Kelas yang diajari
            if (!empty($kelas_id)) {
                foreach ($kelas_id as $key => $value) {
                    $newModelKelasPengajaran = new \backend\modules\baak\models\KelasPengajaran();
                    $newModelKelasPengajaran->dosen_matakuliah_id= $model->dosen_matakuliah_id;
                    $newModelKelasPengajaran->kelas_id= $value;
                    $newModelKelasPengajaran->save();
                }
            }
            return $this->redirect(['dosen-matakuliah-view', 'id' => $model->dosen_matakuliah_id]);
        } else {
            return $this->render('DosenMatakuliahEdit', [
                'model' => $model,
                'dataKuliah' => $dataKuliah,
                'dataKelas' => $dataKelas,
                'result' => $result,
                'semester'=>$semester,
                'prodi'=>$prodi,
            ]);
        }
    }

    /**
     * Deletes an existing DosenMatakuliah model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDosenMatakuliahDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the DosenMatakuliah model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DosenMatakuliah the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DosenMatakuliah::find()->where(['dosen_matakuliah_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
