<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\KegiatanPengabdianMasyarakat;
use backend\modules\baak\models\search\KegiatanPengabdianMasyarakatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\baak\models\Semester;
use backend\modules\baak\models\HeaderDetailDokumenBukti;
use yii\data\ActiveDataProvider;
/**
 * KegiatanPengabdianMasyarakatController implements the CRUD actions for KegiatanPengabdianMasyarakat model.
 */
class KegiatanPengabdianMasyarakatController extends Controller
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
     * Lists all KegiatanPengabdianMasyarakat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KegiatanPengabdianMasyarakatSearch();
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
        $this->redirect(['kegiatan-pengabdian-masyarakat-view', 'id'=>$id]);
    }

    /**
     * Displays a single KegiatanPengabdianMasyarakat model.
     * @param integer $id
     * @return mixed
     */
    public function actionKegiatanPengabdianMasyarakatView($id)
    {
        $model  = $this->findModel($id);
        $DokumenBuktiKegiatanPengabdian = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id]);
        $dataProviderDokumenBukti = new \yii\data\ActiveDataProvider([
            'query'=>$DokumenBuktiKegiatanPengabdian,
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
        
        $queryDosenKegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id' => $id]);
        $dataProviderkegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryDosenKegiatanPengabdian,
            'sort' => [
                'defaultOrder' => [
                    'dosen_id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('KegiatanPengabdianMasyarakatView', [
            'model' => $this->findModel($id),
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
            'dataProviderkegiatanPengabdian' => $dataProviderkegiatanPengabdian,
        ]);
    }
    
    public function actionKegiatanPengabdianMasyarakatViewKprodiFrk($id)
    {
        $model  = $this->findModel($id);
        $DokumenBuktiKegiatanPengabdian = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id]);
//        var_dump($DokumenBuktiMediaMassa);
//        die();
        $dataProviderDokumenBukti = new \yii\data\ActiveDataProvider([
            'query'=>$DokumenBuktiKegiatanPengabdian,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'header_detail_dokumen_bukti_id' => SORT_ASC,
                ]
            ],
        ]);
        
        $queryDosenKegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id' => $id]);
        $dataProviderkegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryDosenKegiatanPengabdian,
            'sort' => [
                'defaultOrder' => [
                    'dosen_id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('KegiatanPengabdianMasyarakatViewKprodiFrk', [
            'model' => $this->findModel($id),
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
            'dataProviderkegiatanPengabdian' => $dataProviderkegiatanPengabdian,
        ]);
    }
    
    public function actionKegiatanPengabdianMasyarakatEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiKegiatanPengabdian::find()->where(['kegiatan_pengabdian_masyarakat_id' => $model->kegiatan_pengabdian_masyarakat_id])
                                                ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();;
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/kegiatan_pengabdian_masyarakat/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['kegiatan-pengabdian-masyarakat-view', 'id' => $modelDokumenBukti->kegiatan_pengabdian_masyarakat_id]);
        } else {
            return $this->render('KegiatanPengabdianMasyarakatUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model'=> $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionKegiatanPengabdianMasyarakatDownload($id) {
        $dokumenBuktiKegiatanPengabdian= \backend\modules\baak\models\DokumenBuktiKegiatanPengabdian::find()->where(['dokumen_bukti_kegiatan_pengabdian_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path. $dokumenBuktiKegiatanPengabdian['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Creates a new KegiatanPengabdianMasyarakat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionKegiatanPengabdianMasyarakatAdd()
    {
      $user_id = Yii::$app->user->id;
      $dosen =  \backend\modules\baak\models\Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new KegiatanPengabdianMasyarakat();
        $dataDosen = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Dosen::find()->all(),'dosen_id', 'nama_dosen');
        $post = Yii::$app->request->post();
        $semester = Semester::find()->where(['semester_aktif'=>1])->one();
        if ($model->load(Yii::$app->request->post())) {
          $model->status = "Rencana Kerja";
          $model->semester_id = $semester->semester_id;
          $kategori = null;
          if($model->kategori_kegiatan == 1){
              $model->header_dokumen_bukti_id = 21;
              $kategori = 'Kegiatan Setara 50 Jam Kerja per Semester';
          }else if($model->kategori_kegiatan == 2){
              $model->header_dokumen_bukti_id = 29;
              $kategori = 'Penyuluhan/Penataran kepada Masyarakat';
          }else{
              $model->header_dokumen_bukti_id = 30;
              $kategori = 'Memberikan Jasa Konsultan sesuai dengan Kepakaran';
          }
          $model->kategori_kegiatan = $kategori;
          $model->created_at = date('Y-m-d');
          $model->created_by = Yii::$app->user->identity->username;
          $model->save();
          $postModelDosenPengabdian = null;
          if(isset($post['dosen_id']))
              $postModelDosenPengabdian = $post['dosen_id'];
          else{
              $postModelDosenPengabdian = null;
          }
          if (!empty($postModelDosenPengabdian)) {
                if(count($postModelDosenPengabdian)>1){
                    foreach ($postModelDosenPengabdian as $key => $value) {
                        $ModelDosenPengabdian = new \backend\modules\baak\models\DosenKegiatanPengabdian();
                        $ModelDosenPengabdian->dosen_id= $value;
                        $ModelDosenPengabdian->kegiatan_pengabdian_masyarakat_id= $model->kegiatan_pengabdian_masyarakat_id;
                        if($value == $dosen['dosen_id']){
                            $ModelDosenPengabdian->jabatan_dlm_kegiatan = "Ketua";
                            $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_pengabdian;
                        }else{
                            $ModelDosenPengabdian->jabatan_dlm_kegiatan = 'Anggota';
                            $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_pengabdian;
                        }
                        $ModelDosenPengabdian->save();
                    }
                }else{
                    $ModelDosenPengabdian = new \backend\modules\baak\models\DosenKegiatanPengabdian();
                    $ModelDosenPengabdian->dosen_id= $dosen['dosen_id'];
                    $ModelDosenPengabdian->jabatan_dlm_kegiatan = "Ketua";
                    $ModelDosenPengabdian->kegiatan_pengabdian_masyarakat_id= $model->kegiatan_pengabdian_masyarakat_id;
                    $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = ($model->jlh_sks_pengabdian);
                    $ModelDosenPengabdian->save();
                }
                
            }else{
                $ModelDosenPengabdian = new \backend\modules\baak\models\DosenKegiatanPengabdian();
                $ModelDosenPengabdian->dosen_id= $dosen['dosen_id'];
                $ModelDosenPengabdian->jabatan_dlm_kegiatan = "Ketua";
                $ModelDosenPengabdian->kegiatan_pengabdian_masyarakat_id= $model->kegiatan_pengabdian_masyarakat_id;
                $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = ($model->jlh_sks_pengabdian);
                $ModelDosenPengabdian->save();
            }
            return $this->redirect(['kegiatan-pengabdian-masyarakat-view', 'id' => $model->kegiatan_pengabdian_masyarakat_id]);
        } else {
            return $this->render('KegiatanPengabdianMasyarakatAdd', [
                'model' => $model,
                'dataDosen' => $dataDosen,
                'semester' => $semester,
                'dosen'=>$dosen,
            ]);
        }
    }

    public function actionKegiatanPengabdianMasyarakatUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiKegiatanPengabdian();
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/kegiatan_pengabdian_masyarakat/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->kegiatan_pengabdian_masyarakat_id= $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiKegiatan = \backend\modules\baak\models\DokumenBuktiKegiatanPengabdian::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['kegiatan_pengabdian_masyarakat_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiKegiatan) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['kegiatan-pengabdian-masyarakat-view', 'id' => $id]);
        } else {
            return $this->render('KegiatanPengabdianMasyarakatUpload', [
                        'modelDokumenBukti' => $modelDokumenBukti,
                        'model'=>$model,
                        'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }

    /**
     * Updates an existing KegiatanPengabdianMasyarakat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionKegiatanPengabdianMasyarakatEdit($id)
    {
        $user_id = Yii::$app->user->id;
        $dosen =  \backend\modules\baak\models\Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = $this->findModel($id);
        $dataDosen = \yii\helpers\ArrayHelper::map(\backend\modules\baak\models\Dosen::find()->all(),'dosen_id', 'nama_dosen');
        $post = Yii::$app->request->post();
        $semester = Semester::find()->where(['semester_aktif'=>1])->one();
        $rowsDosen = (new \yii\db\Query())
        ->select(['baak_dosen_kegiatan_pengabdian.dosen_id', 'nama_dosen'])
        ->from('baak_dosen_kegiatan_pengabdian')
        ->where(['kegiatan_pengabdian_masyarakat_id' => $id])
        ->join('INNER JOIN','baak_dosen','baak_dosen.dosen_id = baak_dosen_kegiatan_pengabdian.dosen_id')    
        ->all();
        $resultDosen = \yii\helpers\ArrayHelper::getColumn($rowsDosen, 'dosen_id');
        if ($model->load(Yii::$app->request->post())) {
          $model->status = "Rencana Kerja";
          $kategori = null;
          if($model->kategori_kegiatan == 1){
              $model->header_dokumen_bukti_id = 21;
              $kategori = 'Kegiatan Setara 50 Jam Kerja per Semester';
          }else if($model->kategori_kegiatan == 2){
              $model->header_dokumen_bukti_id = 29;
              $kategori = 'Penyuluhan/Penataran kepada Masyarakat';
          }else{
              $model->header_dokumen_bukti_id = 30;
              $kategori = 'Memberikan Jasa Konsultan sesuai dengan Kepakaran';
          }
          $model->kategori_kegiatan = $kategori;
          $model->updated_at = date('Y-m-d');
          $model->updated_by = Yii::$app->user->identity->username;
          $model->save();
          \backend\modules\baak\models\DosenKegiatanPengabdian::deleteAll(['kegiatan_pengabdian_masyarakat_id'=>$model->kegiatan_pengabdian_masyarakat_id]);
          $postModelDosenPengabdian = null;
          if(isset($post['dosen_id']))
              $postModelDosenPengabdian = $post['dosen_id'];
          else{
              $postModelDosenPengabdian = null;
          }
          if (!empty($postModelDosenPengabdian)) {
                if(count($postModelDosenPengabdian)>1){
                    foreach ($postModelDosenPengabdian as $key => $value) {
                        $ModelDosenPengabdian = new \backend\modules\baak\models\DosenKegiatanPengabdian();
                        $ModelDosenPengabdian->dosen_id= $value;
                        $ModelDosenPengabdian->kegiatan_pengabdian_masyarakat_id= $model->kegiatan_pengabdian_masyarakat_id;
                        if($value == $dosen['dosen_id']){
                            $ModelDosenPengabdian->jabatan_dlm_kegiatan = "Ketua";
                            $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_pengabdian;
                        }else{
                            $ModelDosenPengabdian->jabatan_dlm_kegiatan = 'Anggota';
                            $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_pengabdian;
                        }
                        $ModelDosenPengabdian->save();
                    }
                }else{
                    $ModelDosenPengabdian = new \backend\modules\baak\models\DosenKegiatanPengabdian();
                    $ModelDosenPengabdian->dosen_id= $dosen['dosen_id'];
                    $ModelDosenPengabdian->jabatan_dlm_kegiatan = "Ketua";
                    $ModelDosenPengabdian->kegiatan_pengabdian_masyarakat_id= $model->kegiatan_pengabdian_masyarakat_id;
                    $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = ($model->jlh_sks_pengabdian);
                    $ModelDosenPengabdian->save();
                }  
            }
            else{
                $ModelDosenPengabdian = new \backend\modules\baak\models\DosenKegiatanPengabdian();
                $ModelDosenPengabdian->dosen_id= $dosen['dosen_id'];
                $ModelDosenPengabdian->jabatan_dlm_kegiatan = "Ketua";
                $ModelDosenPengabdian->kegiatan_pengabdian_masyarakat_id= $model->kegiatan_pengabdian_masyarakat_id;
                $ModelDosenPengabdian->jlh_sks_beban_kerja_dosen = ($model->jlh_sks_pengabdian);
                $ModelDosenPengabdian->save();
            }
            return $this->redirect(['kegiatan-pengabdian-masyarakat-view', 'id' => $model->kegiatan_pengabdian_masyarakat_id]);
        } else {
            return $this->render('KegiatanPengabdianMasyarakatEdit', [
                'model' => $model,
                'dataDosen' => $dataDosen,
                'semester' => $semester,
                'resultDosen' => $resultDosen,
                'dosen'=>$dosen,
            ]);
        }
    }

    /**
     * Deletes an existing KegiatanPengabdianMasyarakat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionKegiatanPengabdianMasyarakatDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the KegiatanPengabdianMasyarakat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KegiatanPengabdianMasyarakat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KegiatanPengabdianMasyarakat::find()->where(['kegiatan_pengabdian_masyarakat_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
