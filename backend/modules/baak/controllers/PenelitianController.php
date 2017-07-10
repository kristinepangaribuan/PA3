<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\Penelitian;
use backend\modules\baak\models\search\PenelitianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\baak\models\DosenPenelitian;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\Dosen;
use backend\modules\baak\models\TahunAjaran;
use yii\helpers\ArrayHelper;
use backend\modules\baak\models\HeaderDetailDokumenBukti;
use backend\modules\baak\models\Semester;

/**
 * PenelitianController implements the CRUD actions for Penelitian model.
 */
class PenelitianController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all Penelitian models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PenelitianSearch();
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
        $this->redirect(['penelitian-view', 'id'=>$id]);
    }

    /**
     * Displays a single Penelitian model.
     * @param integer $id
     * @return mixed
     */
    public function actionPenelitianView($id) {
        $modelPenelitian = $this->findModel($id);
        $queryDosenPenelitian = DosenPenelitian::find()->where(['penelitian_id' => $id]);
        $queryHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $modelPenelitian->header_dokumen_bukti_id]);
        $dataProviderDosenPenelitian = new ActiveDataProvider([
            'query' => $queryDosenPenelitian,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_penelitian' => SORT_DESC,
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
        return $this->render('PenelitianView', [
                    'modelPenelitian' => $modelPenelitian,
                    'dataProviderDosenPenelitian' => $dataProviderDosenPenelitian,
                    'dataProviderHeaderDetailDokumenBukti' => $dataProviderHeaderDetailDokumenBukti,
        ]);
    }
    
    public function actionPenelitianViewKprodiFrk($id) {
        $modelPenelitian = $this->findModel($id);
        $queryDosenPenelitian = DosenPenelitian::find()->where(['penelitian_id' => $id]);
        $queryHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id' => $modelPenelitian->header_dokumen_bukti_id]);
        $dataProviderDosenPenelitian = new ActiveDataProvider([
            'query' => $queryDosenPenelitian,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'jabatan_dlm_penelitian' => SORT_DESC,
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
        return $this->render('PenelitianViewKprodiFrk', [
                    'modelPenelitian' => $modelPenelitian,
                    'dataProviderDosenPenelitian' => $dataProviderDosenPenelitian,
                    'dataProviderHeaderDetailDokumenBukti' => $dataProviderHeaderDetailDokumenBukti,
        ]);
    }

    public function actionPenelitianDownload($id) {
        $dokumenBuktiPenelitian = \backend\modules\baak\models\DokumenBuktiPenelitian::find()->where(['dokumen_bukti_penelitian_id'=>$id])->one();
        $source_path = Yii::getAlias('@webroot');
        $file = $source_path . $dokumenBuktiPenelitian['path_dokumen'];
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    /**
     * Creates a new Penelitian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */    
    public function actionPenelitianAdd(){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = new Penelitian();
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $post = Yii::$app->request->post();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $jenisPenelitian = \backend\modules\baak\models\TahapanPenelitian::find()->where(['is_parent_of' => NULL])->all();
        $tahapanPenelitian = \backend\modules\baak\models\TahapanPenelitian::find()->where(['IS NOT','is_parent_of', NULL])->all();
        if($model->countJlhPenelitian($dosen['dosen_id'])>=2){
            Yii::$app->session->setFlash('danger', 'anda tidak dapat menambahkan penelitian yang baru, anda sudah terlibat dalam 2 penelitian dalam semester ini');
            return $this->render('PenelitianAdd', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'semester' => $semester,
                'dosen' => $dosen,
                'jenisPenelitian' => $jenisPenelitian,
                'tahapanPenelitian' => $tahapanPenelitian,
            ]);
        }
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['Penelitian'];
            if(isset($post['dosen_id'])){
                $postModelDosenPenelitian = $post['dosen_id'];
            }else{
                $postModelDosenPenelitian = null;
            }
            $model->header_dokumen_bukti_id = 11;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            $model->created_at = date('Y-m-d');
            $model->created_by = Yii::$app->user->identity->username;
            $ref_jlh_sks_id = 15;
            if(count($postModelDosenPenelitian)>1){
                $ref_jlh_sks_id = 14;
            }else{
                $ref_jlh_sks_id = 15;
            }
            $refJlhSks = \backend\modules\baak\models\RefJlhSks::findOne($ref_jlh_sks_id);
            $model->jlh_sks_penelitian =($refJlhSks['jlh_sks'] * $model->jlh_target)/100; 
            $model->save();
            if (!empty($postModelDosenPenelitian)) {
                foreach ($postModelDosenPenelitian as $key => $value) {
                    $dosen_id = $value;
                    $newModelDosenPenelitian = new DosenPenelitian();
                    $newModelDosenPenelitian->dosen_id = $value;
                    $newModelDosenPenelitian->penelitian_id = $model->penelitian_id;
                    if(count($postModelDosenPenelitian)>1){
                        if($value == $dosen['dosen_id']){
                            //ketua terdapat 2 penelelitian
                            if($model->countJlhPenelitian($dosen_id)>=1){
                                
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 2 * 0.6 * $model->jlh_sks_penelitian;
                            }else{
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_penelitian;
                            }
                            $newModelDosenPenelitian->jabatan_dlm_penelitian = 'Ketua';
                            
                        }else{
                            //anggota terdapat dalam 2 penelitian
                            if($model->countJlhPenelitian($dosen_id)){
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 2 * 0.4 * $model->jlh_sks_penelitian;
                            }else{
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_penelitian;
                            }
                            $newModelDosenPenelitian->jabatan_dlm_penelitian = 'Anggota';
                        }
                    }else{
                        //ketua terdapat 2 penelelitian
                            if($model->countJlhPenelitian($dosen_id)>=1){
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 2 * $model->jlh_sks_penelitian;
                            }else{
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = $model->jlh_sks_penelitian;
                            }
                            $newModelDosenPenelitian->jabatan_dlm_penelitian = 'Ketua';
                    }
                    $newModelDosenPenelitian->save();
                }
            }
            return $this->redirect(['penelitian-view', 'id' => $model->penelitian_id]);
        } else {
            return $this->render('PenelitianAdd', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'semester' => $semester,
                'dosen' => $dosen,
                'jenisPenelitian' => $jenisPenelitian,
                'tahapanPenelitian' => $tahapanPenelitian,
            ]);
        }
    }
    
    public function actionGetTahapan(){
        $request = Yii::$app->request;
        $obj = $request->post('obj');
        $value = $request->post('value');
        $id = null;
        $name = null;
        echo $obj;
        $tahapanPenelitian = \backend\modules\baak\models\TahapanPenelitian::find()->where(['is_parent_of' => $value])->all();
        $id = 'tahapan_penelitian_id';
        $name = 'tahapan_penelitian';
        $tagOptions = ['prompt' => "Pilih Tahapan Penelitian..."];
        return \yii\helpers\Html::renderSelectOptions([], \yii\helpers\ArrayHelper::map($tahapanPenelitian, $id, $name), $tagOptions);
    }
    
    
    public function actionGetPersentasi($tahapan_penelitian_id){
        $modelTahapan = \backend\modules\baak\models\TahapanPenelitian::findOne($tahapan_penelitian_id);
        echo \yii\helpers\Json::encode($modelTahapan);
    }

    public function actionPenelitianUpload($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti = new \backend\modules\baak\models\DokumenBuktiPenelitian();
        $modelHeaderDetailDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/penelitian/'.$id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->header_detail_dokumen_bukti_id = $id_dokumen;
            $modelDokumenBukti->penelitian_id = $id;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            
            //cek all dokumen
            $allDokumenBukti = HeaderDetailDokumenBukti::find()->where(['header_dokumen_bukti_id'=>$model->header_dokumen_bukti_id])->all();
            $idDetailDokumenBukti = \yii\helpers\ArrayHelper::map($allDokumenBukti, 'header_detail_dokumen_bukti_id', 'header_detail_dokumen_bukti_id');
            $allDokumenBuktiPenelitian = \backend\modules\baak\models\DokumenBuktiPenelitian::find()
                    ->where(['IN', 'header_detail_dokumen_bukti_id', $idDetailDokumenBukti])
                    ->andWhere(['penelitian_id'=>$id])
                    ->all();
            if(count($allDokumenBuktiPenelitian) ==  count($allDokumenBukti)){
                $model->status_all_dokumen = 1;
                $model->save();
            }
            return $this->redirect(['penelitian-view', 'id' => $id]);
        } else {
            return $this->render('PenelitianUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model'=>$model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }

     /**
     * Updates an existing Penelitian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPenelitianEdit($id){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $model = $this->findModel($id);
        $model->tahapan_penelitian_id = $model->tahapanPenelitian->tahapan_penelitian_id;
        $rows = (new \yii\db\Query())
        ->select(['baak_dosen_penelitian.dosen_id', 'nama_dosen'])
        ->from('baak_dosen_penelitian')
        ->where(['penelitian_id' => $id])
        ->join('INNER JOIN','baak_dosen','baak_dosen.dosen_id = baak_dosen_penelitian.dosen_id')    
        ->all();
        $result = ArrayHelper::getColumn($rows, 'dosen_id');
        
        $dataDosen = ArrayHelper::map(Dosen::find()->asArray()->all(), 'dosen_id', 'nama_dosen');
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif' => 1])->one();
        $post = Yii::$app->request->post();
        $jenisPenelitian = \backend\modules\baak\models\TahapanPenelitian::find()->where(['is_parent_of' => NULL])->all();
        $tahapanPenelitian = \backend\modules\baak\models\TahapanPenelitian::find()->where(['IS NOT','is_parent_of', NULL])->all();
        
        if ($model->load(Yii::$app->request->post())) {
            $postModel = $post['Penelitian'];
            if(isset($post['dosen_id'])){
                $postModelDosenPenelitian = $post['dosen_id'];
            }else{
                $postModelDosenPenelitian = null;
            }
            $model->header_dokumen_bukti_id = 11;
            $model->status = 'Rencana Kerja';
            $model->semester_id = $semester['semester_id'];
            $model->updated_at = date('Y-m-d');
            $model->updated_by = Yii::$app->user->identity->username;
            DosenPenelitian::deleteAll(['penelitian_id'=>$id]);
            $ref_jlh_sks_id = 15;
            if(count($postModelDosenPenelitian)>1){
                $ref_jlh_sks_id = 14;
            }else{
                $ref_jlh_sks_id = 15;
            }
            $refJlhSks = \backend\modules\baak\models\RefJlhSks::findOne($ref_jlh_sks_id);
            $model->jlh_sks_penelitian =($refJlhSks['jlh_sks'] * $model->jlh_target)/100;
            $model->save();
            if (!empty($postModelDosenPenelitian)) {
                foreach ($postModelDosenPenelitian as $key => $value) {
                    $dosen_id = $value;
                    $newModelDosenPenelitian = new DosenPenelitian();
                    $newModelDosenPenelitian->dosen_id = $value;
                    $newModelDosenPenelitian->penelitian_id = $model->penelitian_id;
                    if(count($postModelDosenPenelitian)>1){
                        if($value == $dosen['dosen_id']){
                            //ketua terdapat 2 penelelitian
                            if($model->countJlhPenelitian($dosen_id)>=1){
//                                die($dosen_id);
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 2 * 0.6 * $model->jlh_sks_penelitian;
                            }else{
//                                die($dosen_id);
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 0.6 * $model->jlh_sks_penelitian;
                            }
                            $newModelDosenPenelitian->jabatan_dlm_penelitian = 'Ketua';
                            
                        }else{
                            //anggota terdapat dalam 2 penelitian
                            if($model->countJlhPenelitian($dosen_id)){
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 2 * 0.4 * $model->jlh_sks_penelitian;
                            }else{
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 0.4 * $model->jlh_sks_penelitian;
                            }
                            $newModelDosenPenelitian->jabatan_dlm_penelitian = 'Anggota';
                        }
                    }
                    else{
                        //ketua terdapat 2 penelelitian
                            if($model->countJlhPenelitian($dosen_id)>=1){
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = 2 * $model->jlh_sks_penelitian;
                            }else{
                                $newModelDosenPenelitian->jlh_sks_beban_kerja_dosen = $model->jlh_sks_penelitian;
                            }
                            $newModelDosenPenelitian->jabatan_dlm_penelitian = 'Ketua';
                    }
                    $newModelDosenPenelitian->save();
                }
            }
            return $this->redirect(['penelitian-view', 'id' => $model->penelitian_id]);
        } else {
            return $this->render('PenelitianEdit', [
                'model' => $model,
                'dataDosen'=>$dataDosen,
                'result' =>$result,
                'semester' => $semester,
                'jenisPenelitian' => $jenisPenelitian,
                'tahapanPenelitian' => $tahapanPenelitian,
            ]);
        }
    }
    
    public function actionPenelitianEditDokumen($id, $id_dokumen) {
        $model = $this->findModel($id);
        $modelDokumenBukti= \backend\modules\baak\models\DokumenBuktiPenelitian::find()
                                            ->where(['penelitian_id' => $model->penelitian_id])
                                            ->andWhere(['header_detail_dokumen_bukti_id' => $id_dokumen])->one();
        $modelHeaderDetailDokumenBukti = \backend\modules\baak\models\HeaderDetailDokumenBukti::find()->where(['header_detail_dokumen_bukti_id'=>$id_dokumen])->one();
        if ($modelDokumenBukti->load(Yii::$app->request->post())) {
            $modelDokumenBukti->file = \yii\web\UploadedFile::getInstance($modelDokumenBukti, 'file');
            $path = '/uploads/penelitian/'.$modelDokumenBukti->penelitian_id.'_'.$id_dokumen . $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $source_path = Yii::getAlias('@webroot');
            $file = $source_path. $modelDokumenBukti['path_dokumen'];
            if(!is_dir($file)){
                unlink($file);
            }
            $modelDokumenBukti->path_dokumen = $path;
            $modelDokumenBukti->nama_file = $modelDokumenBukti->nama_file . '.' . $modelDokumenBukti->file->extension;
            $modelDokumenBukti->save();
            $modelDokumenBukti->file->saveAs(Yii::getAlias('@webroot').$path);
            return $this->redirect(['penelitian-view', 'id' => $modelDokumenBukti->penelitian_id]);
        } else {
            return $this->render('PenelitianUpload', [
                'modelDokumenBukti' => $modelDokumenBukti,
                'model' => $model,
                'dokumen_id' => $id_dokumen,
                'modelHeaderDetailDokumenBukti'=>$modelHeaderDetailDokumenBukti,
            ]);
        }
    }
    
    /**
     * Deletes an existing Penelitian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPenelitianDel($id) {
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_at = date('Y-m-d');
        $model->deleted_by = \Yii::$app->user->identity->username;
        $model->save();
        return $this->redirect(['dosen/frk']);
    }

    /**
     * Finds the Penelitian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Penelitian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Penelitian::find()->where(['penelitian_id'=>$id])->andWhere(['deleted'=>0])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
