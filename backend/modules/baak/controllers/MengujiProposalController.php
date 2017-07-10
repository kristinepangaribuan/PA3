<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\MengujiProposal;
use backend\modules\baak\models\search\MengujiProposalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\baak\models\HeaderDetailDokumenBukti;

/**
 * MengujiProposalController implements the CRUD actions for MengujiProposal model.
 */
class MengujiProposalController extends Controller
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
     * Lists all MengujiProposal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MengujiProposalSearch();
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
        $this->redirect(['menguji-proposal-view', 'id'=>$id]);
    }

    /**
     * Displays a single MengujiProposal model.
     * @param integer $id
     * @return mixed
     */
    public function actionMengujiProposalView($id)
    {
        $model = $this->findModel($id);
        $DokumenBuktiMengujiProposal = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id]);
//        var_dump($DokumenBuktiMediaMassa);
//        die();
        $dataProviderDokumenBukti = new \yii\data\ActiveDataProvider([
            'query'=>$DokumenBuktiMengujiProposal,
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
        return $this->render('MengujiProposalView', [
            'model' => $model,
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
        ]);
    }
    
    public function actionMengujiProposalViewKprodiFrk($id)
    {
        $model = $this->findModel($id);
        $DokumenBuktiMengujiProposal = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id]);
//        var_dump($DokumenBuktiMediaMassa);
//        die();
        $dataProviderDokumenBukti = new \yii\data\ActiveDataProvider([
            'query'=>$DokumenBuktiMengujiProposal,
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
        return $this->render('MengujiProposalViewKprodiFrk', [
            'model' => $model,
            'dataProviderDokumenBukti'=>$dataProviderDokumenBukti,
        ]);
    }
    
    
    public function actionMengujiProposalUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiMengujiProposal();
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/menguji_proposal/'.$id .'_'.$id_dokumen. $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->menguji_proposal_id = $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiProposal = \backend\modules\baak\models\DokumenBuktiMengujiProposal::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['menguji_proposal_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiProposal) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['menguji-proposal-view', 'id' => $id]);
        } else {
            return $this->render('MengujiProposalUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model'=>$model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    public function actionMengujiProposalDownload($id) {
        $dokumenBuktiMengujiProposal= \backend\modules\baak\models\DokumenBuktiMengujiProposal::find()->where(['dokumen_bukti_menguji_proposal_id' => $id])->one();
        $source_path= Yii::getAlias('@webroot');
        $file = $source_path.$dokumenBuktiMengujiProposal['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Creates a new MengujiProposal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMengujiProposalAdd()
    {
        $jenisProposal = \backend\modules\baak\models\JenisProposal::find()->all();
        $tahunAjaran = \backend\modules\baak\models\TahunAjaran::find()->all();
        $model = new MengujiProposal();
        $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id' => Yii::$app->user->id])->one();
        $dosen_id = $dosen['dosen_id'];
        $jlh_sks_menguji_proposal=1;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post()))   {
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            $model->dosen_id = $dosen_id;
            $model->header_dokumen_bukti_id = 8;
            if($model->jenis_proposal_id==1){
                if($model->jlh_mhs_proposal <13)
                    $jlh_sks_menguji_proposal =  ($model->jlh_mhs_proposal/12);   
            }else if($model->jenis_proposal_id==1){
                if($model->jlh_mhs_proposal <7)
                    $jlh_sks_menguji_proposal =  ($model->jlh_mhs_proposal/6);   
            }else{
                if($model->jlh_mhs_proposal <5)
                    $jlh_sks_menguji_proposal =  ($model->jlh_mhs_proposal/4); 
            }
            $model->jlh_sks_menguji_proposal = $jlh_sks_menguji_proposal;
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['menguji-proposal-view', 'id' => $model->menguji_proposal_id]);
        } else {
            return $this->render('MengujiProposalAdd', [
                'model' => $model,
                'tahunAjaran'=>$tahunAjaran,
                'jenisProposal' =>$jenisProposal,
                'semester' => $semester,
            ]);
        }
    }

    /**
     * Updates an existing MengujiProposal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMengujiProposalEdit($id)
    {
        $jenisProposal = \backend\modules\baak\models\JenisProposal::find()->all();
        $tahunAjaran = \backend\modules\baak\models\TahunAjaran::find()->all();
        $model = $this->findModel($id);
        $model->tahun_ajaran = $model->semester->tahun_ajaran_id;
        $jlh_sks_menguji_proposal=1;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        if ($model->load(Yii::$app->request->post()))   {
            $model->semester_id = $semester['semester_id'];
            if($model->jenis_proposal_id==1){
                if($model->jlh_mhs_proposal <13)
                    $jlh_sks_menguji_proposal =  ($model->jlh_mhs_proposal/12);   
            }else if($model->jenis_proposal_id==1){
                if($model->jlh_mhs_proposal <7)
                    $jlh_sks_menguji_proposal =  ($model->jlh_mhs_proposal/6);   
            }else{
                if($model->jlh_mhs_proposal <5)
                    $jlh_sks_menguji_proposal =  ($model->jlh_mhs_proposal/4); 
            }
            $model->jlh_sks_menguji_proposal = $jlh_sks_menguji_proposal;
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            $model->save();
            return $this->redirect(['menguji-proposal-view', 'id' => $model->menguji_proposal_id]);
        } else {
            return $this->render('MengujiProposalEdit', [
                'model' => $model,
                'tahunAjaran'=>$tahunAjaran,
                'jenisProposal' =>$jenisProposal,
                'semester' => $semester,
            ]);
        }
    }

    public function actionMengujiProposalEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiMengujiProposal::find()
                                            ->where(['menguji_proposal_id' => $model->menguji_proposal_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/menguji_proposal/'.$modelDokumenBukti->menguji_proposal_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['menguji-proposal-view', 'id' => $modelDokumenBukti->menguji_proposal_id]);
        } else {
            return $this->render('MengujiProposalUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    /**
     * Deletes an existing MengujiProposal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMengujiProposalDel($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the MengujiProposal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MengujiProposal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MengujiProposal::find()->where(['menguji_proposal_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
