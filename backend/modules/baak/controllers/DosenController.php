<?php

namespace backend\modules\baak\controllers;

use Yii;
use backend\modules\baak\models\Dosen;
use backend\modules\baak\models\search\DosenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\baak\models\User;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\DosenPenelitian;
use backend\modules\baak\models\Penelitian;
use backend\modules\baak\models\DosenModulBahanAjar;
use backend\modules\baak\models\ModulBahanAjar;
use yii\helpers\ArrayHelper;
use \kartik\mpdf\Pdf;

/**
 * DosenController implements the CRUD actions for Dosen model.
 */
class DosenController extends Controller
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
            // 'access' => [
            //     'class' => \yii\filters\AccessControl::className(),
            //     'only' => ['create', 'list-fed-dosen', 'list-frk-dosen', 'rejectfrk', 'rejectfed', 'notifikasi-frk', 'notifikasi-fed', 'frk-dosen-request', 'fed-dosen-request', 'submit-all-frk','submit-all-fed', 'process-all-frk', 'process-all-fed', 'report', 'report-dosen',
            //         'download-report-dosen', 'view-report-dosen', 'download-report', 'frk-summary', 'frk-saya-penelitian', 'frk-saya-pengajaran', 'frk-saya-pengabdian-masyarakat', 'frk-saya-pengembangan-instansi', 'fed'],
            //     'rules' => [
            //         // deny all POST requests
            //         [
            //             'allow' => false,
            //             'verbs' => ['POST']
            //         ],
            //         // allow authenticated users
            //         [
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //         // everything else is denied
            //     ],
            // ],
        ];
    }

    /**
     * Lists all Dosen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DosenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $Prodi = \backend\modules\baak\models\InstProdi::find()->all();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Prodi' => $Prodi,
            'semester' => $semester,
        ]);
    }

    public function actionRejectfrk($id, $status)
    {
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $modelStatus = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['dosen_id'=>$id])
                ->andWhere(['status' => 'Pengajuan FRK'])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
        $kProdi = Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
        if ($modelStatus->load(Yii::$app->request->post())) {
            $modelStatus->status = $status;
            $modelStatus->dosen_k_prodi_id = $kProdi['dosen_id'];
            $modelStatus->status_read = 0;
            $modelStatus->save();
            return $this->redirect(['process-all-frk', 'id'=>$id, 'status'=>'Rencana Kerja']);
        }
        return $this->renderAjax('form_request_frk', ['model' => $modelStatus]);
    }

    public function actionRejectfed($id, $status)
    {
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $modelStatus = \backend\modules\baak\models\StatusFedDosen::find()
                ->where(['dosen_id'=>$id])
                ->andWhere(['status' => 'Pengajuan FED'])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
        $kProdi = Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
        if ($modelStatus->load(Yii::$app->request->post())) {
            $modelStatus->status = $status;
            $modelStatus->dosen_k_prodi_id = $kProdi['dosen_id'];
            $modelStatus->status_read = 0;
            $modelStatus->save();
            return $this->redirect(['process-all-fed', 'id'=>$id, 'status'=>'Approve FRK']);
        }
        return $this->renderAjax('form_request_fed', ['model' => $modelStatus]);
    }

    /**
     * Displays a single Dosen model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('profil_dosen', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionNotifikasiFrk(){
        $status_approve = 'Approve FRK';
        $status_reject = 'Reject FRK';
        $dosen = Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $modelStatusDosen = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['IN','status', [$status_approve, $status_reject]])
                ->andWhere(['status_read'=>0])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->one();
        if(count($modelStatusDosen)>0){
            $modelStatusDosen->status_read = 1;
            $modelStatusDosen->save();
        }

        return $this->render('notifikasi_frk', [
            'model' => $modelStatusDosen,
            'dosen' =>$dosen,
        ]);
    }

    public function actionNotifikasiFed(){
        $status_approve = 'Approve FED';
        $status_reject = 'Reject FED';
        $dosen = Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $modelStatusDosen = \backend\modules\baak\models\StatusFedDosen::find()
                ->where(['IN','status', [$status_approve, $status_reject]])
                ->andWhere(['status_read'=>0])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->one();
        if(count($modelStatusDosen)>0){
            $modelStatusDosen->status_read = 1;
            $modelStatusDosen->save();
        }

        return $this->render('notifikasi_fed', [
            'model' => $modelStatusDosen,
            'dosen' =>$dosen,
        ]);
    }

    /**
     * Creates a new Dosen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dosen();
        if ($model->load(Yii::$app->request->post())) {
            //create user
            $user = new User();
            $user->username = $model->username;
            $user->email = $model->email;
            $user->setPassword($model->password);
            $user->generateAuthKey();
            //role default sebagai dosen
            $post = \Yii::$app->request->post();
            $postDosen = $post['Dosen'];
            $user->role_user_id = $postDosen['role_user_id'];
            if($user->role_user_id!=NULL){
                $user->role_user_id = $postDosen['role_user_id'];
            }else{
                $user->role_user_id = $model->role_user_id;
            }
            $user->save();
            $model->user_id = $user['user_id'];
            Yii::$app->db->createCommand()
            ->insert('baak_dosen', [
            'nama_dosen' => $model->nama_dosen,
            'email' => $model->email,
            'alamat' => $model->alamat,
            'nidn' => $model->nidn,
            'golongan_kepangkatan_id' =>$model->golongan_kepangkatan_id,
            'status_ikatan_kerja' =>$model->status_ikatan_kerja ,
            'tempat_tgl_lahir' => $model->tempat_tgl_lahir,
            'aktif_start' => $model->aktif_start,
            'aktif_end' => $model->aktif_end,
            'user_id' =>$model->user_id,
            'ref_kbk_id' => $model->ref_kbk_id,
            'status' => $model->status,
            's1' => $model->s1,
            's2' => $model->s2,
            's3' => $model->s3,
            'no_hp' => $model->no_hp,
            'ilmu_yg_ditekuni' => $model->ilmu_yg_ditekuni,
            ])->execute();
            $dosen = Dosen::find()->where(['user_id'=>$user['user_id']])->one();
            return $this->redirect(['view', 'id' => $dosen->dosen_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionListFrkDosen(){
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //pengajuan / request
        $queryStatusDosen = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['status'=>'Pengajuan FRK'])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->all();
        $idDosen = ArrayHelper::map($queryStatusDosen, 'dosen_id', 'dosen_id');
        $queryDosen = Dosen::find()
                ->where(['IN', 'dosen_id', $idDosen])
                ->andWhere(['ref_kbk_id'=>$dosen['ref_kbk_id']]);
        $dataProviderStatusFRK= new ActiveDataProvider([
            'query' => $queryDosen,
            'pagination' => [ 'pageSize' => 10 ],
        ]);

        //approve
        $queryStatusDosenApprove = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['status'=>'Approve FRK'])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->all();
        $idDosenApprove = ArrayHelper::map($queryStatusDosenApprove, 'dosen_id', 'dosen_id');
        $queryDosenApprove = Dosen::find()
                ->where(['IN', 'dosen_id', $idDosenApprove])
                ->andWhere(['ref_kbk_id'=>$dosen['ref_kbk_id']]);
        $dataProviderStatusFRKApprove= new ActiveDataProvider([
            'query' => $queryDosenApprove,
            'pagination' => [ 'pageSize' => 10 ],
        ]);
        return $this->render('_list_dosen_frk', [
            'dataProviderStatusFRK'=>$dataProviderStatusFRK,
            'dataProviderStatusFRKApprove'=>$dataProviderStatusFRKApprove,
            'model' => $dosen,
        ]);
    }

    public function actionListFedDosen($id){
        $dosen = Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $penugasanAsesor = \backend\modules\baak\models\PenugasanAsesor::find()->where(['dosen_id'=>$id])->andWhere(['semester_id'=>$semester['semester_id']])->one();
        $dosenAsesor = \backend\modules\baak\models\DosenAsesor::find()->where(['penugasan_asesor_id'=>$penugasanAsesor['penugasan_asesor_id']])->andWhere(['semester_id'=>$semester['semester_id']])->all();
        $idDosenAsesor = ArrayHelper::map($dosenAsesor, 'dosen_id', 'dosen_id');
        //pengajuan / request
        $queryStatusDosen = \backend\modules\baak\models\StatusFedDosen::find()
                ->where(['status'=>'Pengajuan FED'])
                ->andWhere(['IN', 'dosen_id', [$idDosenAsesor]])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->all();

        $idDosen = ArrayHelper::map($queryStatusDosen, 'dosen_id', 'dosen_id');
        $queryDosen = Dosen::find()
                ->where(['IN', 'dosen_id', $idDosen]);
        $dataProviderStatusFED= new ActiveDataProvider([
            'query' => $queryDosen,
            'pagination' => [ 'pageSize' => 10 ],
        ]);

        //approve
        $queryStatusDosenApprove = \backend\modules\baak\models\StatusFedDosen::find()
                ->where(['status'=>'Approve FED'])
                ->andWhere(['IN', 'dosen_id', $idDosenAsesor])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->all();
//        var_dump($queryStatusDosenApprove);
//        die();
        $idDosenApprove = ArrayHelper::map($queryStatusDosenApprove, 'dosen_id', 'dosen_id');
        $queryDosenApprove = Dosen::find()
                ->where(['IN', 'dosen_id', $idDosenApprove]);
        $dataProviderStatusFEDApprove= new ActiveDataProvider([
            'query' => $queryDosenApprove,
            'pagination' => [ 'pageSize' => 10 ],
        ]);
        return $this->render('_list_dosen_fed', [
            'dataProviderStatusFED'=>$dataProviderStatusFED,
            'dataProviderStatusFEDApprove'=>$dataProviderStatusFEDApprove,
            'model' => $dosen,
        ]);
    }

    public function actionFrkDosenRequest($id, $status){
        $total_sks= 0;
        $dosen = $this->findModel($id);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status' => $status]);
        $matakuliah = $DosenMatakuliah->all();
        $dataProviderDosenMatkuliah= new ActiveDataProvider([
            'query' => $DosenMatakuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
//        var_dump($matakuliah);
//        die();
        foreach ($matakuliah as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status' => $status]);

        $idAsistenPraktikum = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenPraktikum])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        // add conditions that should always apply here
        $dataProviderAsistenTugas= new ActiveDataProvider([
            'query' => $queryAsistenTugas,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($AsistenTugas as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //bimbingan kuliah
        $queryBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks+=$data['jlh_sks_bimbingan_kuliah'];
        }
        $dataProviderBimbinganKuliah= new ActiveDataProvider([
            'query' => $queryBimbinganKuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($SeminarTerjadwal as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderSeminarTerjadwal= new ActiveDataProvider([
            'query' => $querySeminarTerjadwal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $MengujiProposal = $queryMengujiProposal->all();
        foreach ($MengujiProposal as $data){
            $total_sks+=$data['jlh_sks_menguji_proposal'];
        }
        $dataProviderMengujiProposal= new ActiveDataProvider([
            'query' => $queryMengujiProposal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderPenelitian = new ActiveDataProvider([
            'query' => $queryPenelitian,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Modul as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderModul = new ActiveDataProvider([
            'query' => $queryModul,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderMakalahSeminar= new ActiveDataProvider([
            'query' => $queryMakalahSeminar,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks+=$data['jlh_sks_beban_dosen'];
        }
        $dataProviderJurnalIlmiah= new ActiveDataProvider([
            'query' => $queryJurnalIlmiah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderKegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryKegiatanPengabdianMasyarakat,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['status'=>$status]);
        $dosenJabatan = $queryDosenJabatan->all();
        foreach ($dosenJabatan as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderDosenJabatan= new ActiveDataProvider([
            'query' => $queryDosenJabatan,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        return $this->render('request_frk',
            [
                //pengajaran
                'dataProviderDosenMatkuliah' => $dataProviderDosenMatkuliah,
                'dataProviderAsistenTugas' => $dataProviderAsistenTugas,
                'dataProviderBimbinganKuliah'=>$dataProviderBimbinganKuliah,
                'dataProviderSeminarTerjadwal'=>$dataProviderSeminarTerjadwal,
                'dataProviderMengujiProposal'=>$dataProviderMengujiProposal,
                //penelitian
                'dataProviderPenelitian'=>$dataProviderPenelitian,
                'dataProviderModul'=>$dataProviderModul,
                'dataProviderMakalahSeminar'=>$dataProviderMakalahSeminar,
                'dataProviderJurnalIlmiah' => $dataProviderJurnalIlmiah,
                //pengabdian-masyarakat
                'dataProviderKegiatanPengabdian' => $dataProviderKegiatanPengabdian,
                //pengembangan-instansi
                'dataProviderDosenJabatan' => $dataProviderDosenJabatan,
                'dosen'=>$dosen,
                'total_sks'=>$total_sks,
                'status' => $status,
            ]
        );
    }

    public function actionFedDosenRequest($id, $status){
        $total_sks= 0;
        $dosen = $this->findModel($id);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);
        $matakuliah = $DosenMatakuliah->all();
        $dataProviderDosenMatkuliah= new ActiveDataProvider([
            'query' => $DosenMatakuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($matakuliah as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);

        $idAsistenPraktikum = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenPraktikum])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        // add conditions that should always apply here
        $dataProviderAsistenTugas= new ActiveDataProvider([
            'query' => $queryAsistenTugas,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($AsistenTugas as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //bimbingan kuliah
        $queryBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks+=$data['jlh_sks_bimbingan_kuliah'];
        }
        $dataProviderBimbinganKuliah= new ActiveDataProvider([
            'query' => $queryBimbinganKuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($SeminarTerjadwal as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderSeminarTerjadwal= new ActiveDataProvider([
            'query' => $querySeminarTerjadwal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $MengujiProposal = $queryMengujiProposal->all();
        foreach ($MengujiProposal as $data){
            $total_sks+=$data['jlh_sks_menguji_proposal'];
        }
        $dataProviderMengujiProposal= new ActiveDataProvider([
            'query' => $queryMengujiProposal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()
                ->where(['IN', 'penelitian_id', $idPenelitian])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderPenelitian = new ActiveDataProvider([
            'query' => $queryPenelitian,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Modul as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderModul = new ActiveDataProvider([
            'query' => $queryModul,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderMakalahSeminar= new ActiveDataProvider([
            'query' => $queryMakalahSeminar,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks+=$data['jlh_sks_beban_dosen'];
        }
        $dataProviderJurnalIlmiah= new ActiveDataProvider([
            'query' => $queryJurnalIlmiah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderKegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryKegiatanPengabdianMasyarakat,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $dosenJabatan = $queryDosenJabatan->all();
        foreach ($dosenJabatan as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderDosenJabatan= new ActiveDataProvider([
            'query' => $queryDosenJabatan,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        return $this->render('request_fed',
            [
                //pengajaran
                'dataProviderDosenMatkuliah' => $dataProviderDosenMatkuliah,
                'dataProviderAsistenTugas' => $dataProviderAsistenTugas,
                'dataProviderBimbinganKuliah'=>$dataProviderBimbinganKuliah,
                'dataProviderSeminarTerjadwal'=>$dataProviderSeminarTerjadwal,
                'dataProviderMengujiProposal'=>$dataProviderMengujiProposal,
                //penelitian
                'dataProviderPenelitian'=>$dataProviderPenelitian,
                'dataProviderModul'=>$dataProviderModul,
                'dataProviderMakalahSeminar'=>$dataProviderMakalahSeminar,
                'dataProviderJurnalIlmiah' => $dataProviderJurnalIlmiah,
                //pengabdian-masyarakat
                'dataProviderKegiatanPengabdian' => $dataProviderKegiatanPengabdian,
                //pengembangan-instansi
                'dataProviderDosenJabatan' => $dataProviderDosenJabatan,
                'dosen'=>$dosen,
                'total_sks'=>$total_sks,
                'status' => $status,
            ]
        );
    }

    public function actionSubmitAllFrk($status){
        $status_rencana = 'Rencana Kerja';
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id' => $user_id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();

        $modelStatus = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['status'=>'Pengajuan FRK'])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
        if($modelStatus!=NULL){
            Yii::$app->session->setFlash('danger', 'Anda telah mensubmit semua frk anda, silahkan tunggu approve dari dosen K-Prodi anda');
            $this->redirect('frk-summary');
        }
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all();
        foreach ($DosenMatakuliah as $data){
            $modelDosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::findOne($data['dosen_matakuliah_id']);
            $modelDosenMatakuliah->status = $status;
            $modelDosenMatakuliah->save();
        }

        //asisten
        $DosenAsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsisten = ArrayHelper::map($DosenAsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $idAsisten= ArrayHelper::map(\backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsisten])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsisteTugasPraktikum= \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()->where(['IN', 'asisten_tugas_praktikum_id', $idAsisten])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($AsisteTugasPraktikum as $data){
            $modelAsisten  = \backend\modules\baak\models\AsistenTugasPraktikum::findOne($data['asisten_tugas_praktikum_id']);
            $modelAsisten->status = $status;
            $modelAsisten->save();
        }

        //bimbingan kuliah
        $BimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all();
        foreach ($BimbinganKuliah as $data){
            $modelBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::findOne($data['bimbingan_kuliah_id']);
            $modelBimbinganKuliah->status = $status;
            $modelBimbinganKuliah->save();
        }

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminar = ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $idSeminar = ArrayHelper::map(\backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $Seminar = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()->where(['IN', 'seminar_terjadwal_id', $idSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Seminar as $data){
            $modelSeminar = \backend\modules\baak\models\SeminarTerjadwal::findOne($data['seminar_terjadwal_id']);
            $modelSeminar->status = $status;
            $modelSeminar->save();
        }

        //menguji proposal
        $MengujiProposal= \backend\modules\baak\models\MengujiProposal::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all();
        foreach ($MengujiProposal as $data){
            $modelMengujiProposal = \backend\modules\baak\models\MengujiProposal::findOne($data['menguji_proposal_id']);
            $modelMengujiProposal->status = $status;
            $modelMengujiProposal->save();
        }

        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $idPenelitian = ArrayHelper::map(Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $modelPenelitian = Penelitian::findOne($data['penelitian_id']);
            $modelPenelitian->status = $status;
            $modelPenelitian->save();
        }

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $idModul = ArrayHelper::map(ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Modul as $data){
            $modelModul = ModulBahanAjar::findOne($data['modul_bahan_ajar_id']);
            $modelModul->status = $status;
            $modelModul->save();
        }

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $idMakalahSeminar = ArrayHelper::map(\backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $modelMakalah = \backend\modules\baak\models\MakalahSeminar::findOne($data['makalah_seminar_id']);
            $modelMakalah->status = $status;
            $modelMakalah->save();
        }

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $idJurnalIlmiah= ArrayHelper::map(\backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $modelJurnal = \backend\modules\baak\models\JurnalIlmiah::findOne($data['jurnal_ilmiah_id']);
            $modelJurnal->status = $status;
            $modelJurnal->save();
        }

        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $idKegiatanPengabdian = ArrayHelper::map(\backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $modelPengabdian = \backend\modules\baak\models\KegiatanPengabdianMasyarakat::findOne($data['kegiatan_pengabdian_masyarakat_id']);
            $modelPengabdian->status = $status;
            $modelPengabdian->save();
        }

        //jabatan dosen
        $dosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_rencana])
                ->all();
        foreach ($dosenJabatan as $data){
            $modelJabatan = \backend\modules\baak\models\DosenJabatan::findOne($data['dosen_jabatan_id']);
            $modelJabatan->status = $status;
            $modelJabatan->save();
        }
        $modelStatus = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['status'=>'Reject FRK'])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
        if($modelStatus!=NULL){
            $modelStatus->status = $status;
            $modelStatus->save();
        }else{
            $statusFrk = new \backend\modules\baak\models\StatusFrkDosen();
            $statusFrk->dosen_id = $dosen['dosen_id'];
            $statusFrk->status = $status;
            $statusFrk->pesan = '';
            $statusFrk->status_read = 0;
            $statusFrk->semester_id = $semester['semester_id'];
            $statusFrk->save();
        }
        Yii::$app->session->setFlash('success', 'Anda behasil mensubmit semua frk anda');
        $this->redirect(['dosen/frk-summary']);
    }

    public function cekAllDokumen($id, $semester_id){
        $model = $this->findModel($id);
        $status = 'Approve FRK';
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status' => $status]);
        $matakuliah = $DosenMatakuliah->all();
        if(count($matakuliah)>0){
            return false;
        }

        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status' => $status]);

        $idAsistenPraktikum = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenPraktikum])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        if(count($AsistenTugas)>0){
            return false;
        }

        //bimbingan kuliah
        $queryBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        if(count($BimbinganKuliah)>0){
            return false;
        }

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        if(count($SeminarTerjadwal)>0){
            return false;
        }

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $MengujiProposal = $queryMengujiProposal->all();
        if(count($MengujiProposal)>0){
            return false;
        }
        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        if(count($Penelitian)>0){
            return false;
        }

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        if(count($Modul)>0){
            return false;
        }
        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        if(count($MakalahSeminar)>0){
            return false;
        }
        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        if(count($JurnalIlmiah)>0){
            return false;
        }
        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        if(count($KegiatanPengabdian)>0){
            return false;
        }
        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester_id])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status_realisasi'=>1])
                ->andWhere(['status_all_dokumen'=>0])
                ->andWhere(['status'=>$status]);
        $dosenJabatan = $queryDosenJabatan->all();
        if(count($dosenJabatan)>0){
            return false;
        }

        return true;
    }

    public function actionSubmitAllFed($status){
        $status_rencana = 'Approve FRK';
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id' => $user_id])->one();
        $id = $dosen['dosen_id'];
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $semester_id = $semester['semester_id'];
        if(!$this->cekAllDokumen($id, $semester_id)){
            Yii::$app->session->setFlash('error', 'anda belum meng-upload semua dokumen bukti frk yang terealisasi, silahkan upload kembali');
            $this->redirect(['dosen/fed', 'id'=>$id]);
        }else{
            //matakuliah
            $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()->asArray()
                    ->where(['dosen_id'=>$dosen['dosen_id']])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all();
            foreach ($DosenMatakuliah as $data){
                $modelDosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::findOne($data['dosen_matakuliah_id']);
                $modelDosenMatakuliah->status = $status;
                $modelDosenMatakuliah->save();
            }

            //asisten
            $DosenAsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
            $oldIDsAsisten = ArrayHelper::map($DosenAsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
            $idAsisten= ArrayHelper::map(\backend\modules\baak\models\AsistenTugasPraktikum::find()
                    ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsisten])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['status' => $status_rencana])
                    ->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
            $AsisteTugasPraktikum= \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()->where(['IN', 'asisten_tugas_praktikum_id', $idAsisten])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
            foreach ($AsisteTugasPraktikum as $data){
                $modelAsisten  = \backend\modules\baak\models\AsistenTugasPraktikum::findOne($data['asisten_tugas_praktikum_id']);
                $modelAsisten->status = $status;
                $modelAsisten->save();
            }

            //bimbingan kuliah
            $BimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()->asArray()
                    ->where(['dosen_id'=>$dosen['dosen_id']])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all();
            foreach ($BimbinganKuliah as $data){
                $modelBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::findOne($data['bimbingan_kuliah_id']);
                $modelBimbinganKuliah->status = $status;
                $modelBimbinganKuliah->save();
            }

            //seminar terjadwal
            $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
            $oldIDsSeminar = ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
            $idSeminar = ArrayHelper::map(\backend\modules\baak\models\SeminarTerjadwal::find()
                    ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminar])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
            $Seminar = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()->where(['IN', 'seminar_terjadwal_id', $idSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
            foreach ($Seminar as $data){
                $modelSeminar = \backend\modules\baak\models\SeminarTerjadwal::findOne($data['seminar_terjadwal_id']);
                $modelSeminar->status = $status;
                $modelSeminar->save();
            }

            //menguji proposal
            $MengujiProposal= \backend\modules\baak\models\MengujiProposal::find()->asArray()
                    ->where(['dosen_id'=>$dosen['dosen_id']])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all();
            foreach ($MengujiProposal as $data){
                $modelMengujiProposal = \backend\modules\baak\models\MengujiProposal::findOne($data['menguji_proposal_id']);
                $modelMengujiProposal->status = $status;
                $modelMengujiProposal->save();
            }

            //penelitian
            $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
            $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
            $idPenelitian = ArrayHelper::map(Penelitian::find()
                    ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all(), 'penelitian_id', 'penelitian_id');
            $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
            foreach ($Penelitian as $data){
                $modelPenelitian = Penelitian::findOne($data['penelitian_id']);
                $modelPenelitian->status = $status;
                $modelPenelitian->save();
            }

            //modul bahan ajar
            $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
            $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
            $idModul = ArrayHelper::map(ModulBahanAjar::find()
                    ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
            $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
            foreach ($Modul as $data){
                $modelModul = ModulBahanAjar::findOne($data['modul_bahan_ajar_id']);
                $modelModul->status = $status;
                $modelModul->save();
            }

            //makalah seminar
            $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
            $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
            $idMakalahSeminar = ArrayHelper::map(\backend\modules\baak\models\MakalahSeminar::find()
                    ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all(), 'makalah_seminar_id', 'makalah_seminar_id');
            $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
            foreach ($MakalahSeminar as $data){
                $modelMakalah = \backend\modules\baak\models\MakalahSeminar::findOne($data['makalah_seminar_id']);
                $modelMakalah->status = $status;
                $modelMakalah->save();
            }

            //jurnal seminar
            $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
            $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
            $idJurnalIlmiah= ArrayHelper::map(\backend\modules\baak\models\JurnalIlmiah::find()
                    ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
            $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
            foreach ($JurnalIlmiah as $data){
                $modelJurnal = \backend\modules\baak\models\JurnalIlmiah::findOne($data['jurnal_ilmiah_id']);
                $modelJurnal->status = $status;
                $modelJurnal->save();
            }

            //kegiatan pengabdian masyarakat
            $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
            $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
            $idKegiatanPengabdian = ArrayHelper::map(\backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                    ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
            $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
            foreach ($KegiatanPengabdian as $data){
                $modelPengabdian = \backend\modules\baak\models\KegiatanPengabdianMasyarakat::findOne($data['kegiatan_pengabdian_masyarakat_id']);
                $modelPengabdian->status = $status;
                $modelPengabdian->save();
            }

            //jabatan dosen
            $dosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                    ->where(['dosen_id'=>$dosen['dosen_id']])
                    ->andWhere(['semester_id' => $semester['semester_id']])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['status' => $status_rencana])
                    ->all();
            foreach ($dosenJabatan as $data){
                $modelJabatan = \backend\modules\baak\models\DosenJabatan::findOne($data['dosen_jabatan_id']);
                $modelJabatan->status = $status;
                $modelJabatan->save();
            }

            $modelStatus = \backend\modules\baak\models\StatusFedDosen::find()
                ->where(['dosen_id'=>$id])
                ->andWhere(['status' => 'Reject FED'])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
            if($modelStatus!=null){
                $modelStatus->status = $status;
                $modelStatus->save();
            }else{
                $statusFed = new \backend\modules\baak\models\StatusFedDosen();
                $statusFed->dosen_id = $dosen['dosen_id'];
                $statusFed->status = $status;
                $statusFed->pesan = '';
                $statusFed->status_read = 0;
                $statusFed->semester_id = $semester['semester_id'];
                $statusFed->save();
            }
            Yii::$app->session->setFlash('success', 'Anda behasil mensubmit semua fed anda');
            $this->redirect(['dosen/fed', 'id'=>$dosen['dosen_id']]);
        }
    }

    public function actionProcessAllFrk($id, $status){
        $status_sebelumnya = 'Pengajuan FRK';
        $dosen = Dosen::findOne($id);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])->all();
        foreach ($DosenMatakuliah as $data){
            $modelDosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::findOne($data['dosen_matakuliah_id']);
            $modelDosenMatakuliah->status = $status;
            $modelDosenMatakuliah->save();
        }

        //asisten
        $DosenAsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsisten = ArrayHelper::map($DosenAsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $idAsisten= ArrayHelper::map(\backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsisten])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsisteTugasPraktikum= \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()->where(['IN', 'asisten_tugas_praktikum_id', $idAsisten])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($AsisteTugasPraktikum as $data){
            $modelAsisten  = \backend\modules\baak\models\AsistenTugasPraktikum::findOne($data['asisten_tugas_praktikum_id']);
            $modelAsisten->status = $status;
            $modelAsisten->save();
        }

        //bimbingan kuliah
        $BimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])->all();
        foreach ($BimbinganKuliah as $data){
            $modelBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::findOne($data['bimbingan_kuliah_id']);
            $modelBimbinganKuliah->status = $status;
            $modelBimbinganKuliah->save();
        }

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminar = ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $idSeminar = ArrayHelper::map(\backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $Seminar = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()->where(['IN', 'seminar_terjadwal_id', $idSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Seminar as $data){
            $modelSeminar = \backend\modules\baak\models\SeminarTerjadwal::findOne($data['seminar_terjadwal_id']);
            $modelSeminar->status = $status;
            $modelSeminar->save();
        }

        //menguji proposal
        $MengujiProposal= \backend\modules\baak\models\MengujiProposal::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])->all();
        foreach ($MengujiProposal as $data){
            $modelMengujiProposal = \backend\modules\baak\models\MengujiProposal::findOne($data['menguji_proposal_id']);
            $modelMengujiProposal->status = $status;
            $modelMengujiProposal->save();
        }

        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $idPenelitian = ArrayHelper::map(Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $modelPenelitian = Penelitian::findOne($data['penelitian_id']);
            $modelPenelitian->status = $status;
            $modelPenelitian->save();
        }

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $idModul = ArrayHelper::map(ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Modul as $data){
            $modelModul = ModulBahanAjar::findOne($data['modul_bahan_ajar_id']);
            $modelModul->status = $status;
            $modelModul->save();
        }

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $idMakalahSeminar = ArrayHelper::map(\backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $modelMakalah = \backend\modules\baak\models\MakalahSeminar::findOne($data['makalah_seminar_id']);
            $modelMakalah->status = $status;
            $modelMakalah->save();
        }

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $idJurnalIlmiah= ArrayHelper::map(\backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $modelJurnal = \backend\modules\baak\models\JurnalIlmiah::findOne($data['jurnal_ilmiah_id']);
            $modelJurnal->status = $status;
            $modelJurnal->save();
        }

        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $idKegiatanPengabdian = ArrayHelper::map(\backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])
                ->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $modelPengabdian = \backend\modules\baak\models\KegiatanPengabdianMasyarakat::findOne($data['kegiatan_pengabdian_masyarakat_id']);
            $modelPengabdian->status = $status;
            $modelPengabdian->save();
        }

        //jabatan dosen
        $dosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])
                ->all();
        foreach ($dosenJabatan as $data){
            $modelJabatan = \backend\modules\baak\models\DosenJabatan::findOne($data['dosen_jabatan_id']);
            $modelJabatan->status = $status;
            $modelJabatan->save();
        }
        if($status == 'Approve FRK'){
            $modelStatus = \backend\modules\baak\models\StatusFrkDosen::find()
                ->where(['dosen_id'=>$id])
                ->andWhere(['status' => 'Pengajuan FRK'])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
            $kProdi = Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
            $modelStatus->status = $status;
            $modelStatus->dosen_k_prodi_id = $kProdi['dosen_id'];
            $modelStatus->status_read = 0;
            $modelStatus->pesan = 'Frk anda telah diapprove oleh K-Prodi anda oleh :'.$kProdi['nama_dosen'];
            $modelStatus->save();
            Yii::$app->session->setFlash('success', 'Anda behasil mengapprove semua frk '.$dosen['nama_dosen']);
        }
        else{
            Yii::$app->session->setFlash('danger', 'Anda behasil mereject semua frk '.$dosen['nama_dosen']);
        }
        $this->redirect(['list-frk-dosen']);
    }

    public function actionProcessAllFed($id, $status){
        $status_sebelumnya = 'Pengajuan FED';
        $dosen = Dosen::findOne($id);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])->all();
        foreach ($DosenMatakuliah as $data){
            $modelDosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::findOne($data['dosen_matakuliah_id']);
            $modelDosenMatakuliah->status = $status;
            $modelDosenMatakuliah->save();
        }

        //asisten
        $DosenAsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsisten = ArrayHelper::map($DosenAsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $idAsisten= ArrayHelper::map(\backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsisten])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsisteTugasPraktikum= \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()->where(['IN', 'asisten_tugas_praktikum_id', $idAsisten])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($AsisteTugasPraktikum as $data){
            $modelAsisten  = \backend\modules\baak\models\AsistenTugasPraktikum::findOne($data['asisten_tugas_praktikum_id']);
            $modelAsisten->status = $status;
            $modelAsisten->save();
        }

        //bimbingan kuliah
        $BimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])->all();
        foreach ($BimbinganKuliah as $data){
            $modelBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::findOne($data['bimbingan_kuliah_id']);
            $modelBimbinganKuliah->status = $status;
            $modelBimbinganKuliah->save();
        }

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminar = ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $idSeminar = ArrayHelper::map(\backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $Seminar = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()->where(['IN', 'seminar_terjadwal_id', $idSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Seminar as $data){
            $modelSeminar = \backend\modules\baak\models\SeminarTerjadwal::findOne($data['seminar_terjadwal_id']);
            $modelSeminar->status = $status;
            $modelSeminar->save();
        }

        //menguji proposal
        $MengujiProposal= \backend\modules\baak\models\MengujiProposal::find()->asArray()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])->all();
        foreach ($MengujiProposal as $data){
            $modelMengujiProposal = \backend\modules\baak\models\MengujiProposal::findOne($data['menguji_proposal_id']);
            $modelMengujiProposal->status = $status;
            $modelMengujiProposal->save();
        }

        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $idPenelitian = ArrayHelper::map(Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $modelPenelitian = Penelitian::findOne($data['penelitian_id']);
            $modelPenelitian->status = $status;
            $modelPenelitian->save();
        }

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $idModul = ArrayHelper::map(ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Modul as $data){
            $modelModul = ModulBahanAjar::findOne($data['modul_bahan_ajar_id']);
            $modelModul->status = $status;
            $modelModul->save();
        }

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $idMakalahSeminar = ArrayHelper::map(\backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $modelMakalah = \backend\modules\baak\models\MakalahSeminar::findOne($data['makalah_seminar_id']);
            $modelMakalah->status = $status;
            $modelMakalah->save();
        }

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $idJurnalIlmiah= ArrayHelper::map(\backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status_sebelumnya])->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $modelJurnal = \backend\modules\baak\models\JurnalIlmiah::findOne($data['jurnal_ilmiah_id']);
            $modelJurnal->status = $status;
            $modelJurnal->save();
        }

        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $idKegiatanPengabdian = ArrayHelper::map(\backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])
                ->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $modelPengabdian = \backend\modules\baak\models\KegiatanPengabdianMasyarakat::findOne($data['kegiatan_pengabdian_masyarakat_id']);
            $modelPengabdian->status = $status;
            $modelPengabdian->save();
        }

        //jabatan dosen
        $dosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status_sebelumnya])
                ->all();
        foreach ($dosenJabatan as $data){
            $modelJabatan = \backend\modules\baak\models\DosenJabatan::findOne($data['dosen_jabatan_id']);
            $modelJabatan->status = $status;
            $modelJabatan->save();
        }

        if($status == 'Approve FED'){
            $modelStatus = \backend\modules\baak\models\StatusFedDosen::find()
                ->where(['dosen_id'=>$id])
                ->andWhere(['status' => 'Pengajuan FED'])
                ->andWhere(['semester_id'=> $semester['semester_id']])->one();
            $kProdi = Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
            $modelStatus->status = $status;
            $modelStatus->dosen_k_prodi_id = $kProdi['dosen_id'];
            $modelStatus->status_read = 0;
            $modelStatus->pesan = 'Fed anda telah diapprove oleh Asesor anda anda oleh :'.$kProdi['nama_dosen'];
            $modelStatus->save();
            Yii::$app->session->setFlash('success', 'Anda behasil mengapprove semua fed '.$dosen['nama_dosen']);
        }
        else{
            Yii::$app->session->setFlash('danger', 'Anda behasil mereject semua fed '.$dosen['nama_dosen']);
        }
        $this->redirect(['list-fed-dosen']);
    }

    /**
     * Updates an existing Dosen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $user = User::find()->where(['user_id'=>$model->user_id])->one();
            $user->username = $model->username;
            $user->email = $model->email;
            $user->setPassword($model->password);
            $user->generateAuthKey();
            //role default sebagai dosen
            $post = \Yii::$app->request->post();
            $postDosen = $post['Dosen'];
            $user->role_user_id = $postDosen['role_user_id'];
            $user->save();
            $model->user_id = $user['user_id'];
            Yii::$app->db->createCommand()
            ->update('baak_dosen', [
            'nama_dosen' => $model->nama_dosen,
            'email' => $model->email,
            'alamat' => $model->alamat,
            'nidn' => $model->nidn,
            'golongan_kepangkatan_id' =>$model->golongan_kepangkatan_id,
            'status_ikatan_kerja' =>$model->status_ikatan_kerja ,
            'tempat_tgl_lahir' => $model->tempat_tgl_lahir,
            'aktif_start' => $model->aktif_start,
            'aktif_end' => $model->aktif_end,
            'user_id' =>$model->user_id,
            'ref_kbk_id' => $model->ref_kbk_id,
            'status' => $model->status,
            's1' => $model->s1,
            's2' => $model->s2,
            's3' => $model->s3,
            'no_hp' => $model->no_hp,
            'ilmu_yg_ditekuni' => $model->ilmu_yg_ditekuni,
            ], 'dosen_id='.$id)->execute();
            return $this->redirect(['view', 'id' => $id]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dosen model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionReport($id, $tahun_ajaran_id, $semester_id){
        $model = $this->findModel($id);
        $status = 'Approve FED';
        $TahunAjaran = \backend\modules\baak\models\TahunAjaran::find()->all();
        $post =  Yii::$app->request->post();
        if($model->load(Yii::$app->request->post())){
            $search = $post['Dosen'];
            $sem = $search['semester_id'];
            $tahun_ajaran_id = $search['tahun_ajaran_id'];
            $semester = \backend\modules\baak\models\Semester::find()
                    ->where(['semester'=>$sem])
                    ->andWhere(['tahun_ajaran_id'=>$tahun_ajaran_id])
                    ->one();
        }else{
            $semester = \backend\modules\baak\models\Semester::findOne($semester_id);
        }
       if($semester->semester=='Gasal'){
           $sem = 'Gasal';
       }else{
           $sem = 'Genap';
       }
        $total_sks= 0;
        $total_sks_pengajaran = $total_sks_penelitian = $total_sks_pengabdian = $total_sks_pengembangan = 0;
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);
        $matakuliah = $DosenMatakuliah->all();
        $dataProviderDosenMatkuliah= new ActiveDataProvider([
            'query' => $DosenMatakuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($matakuliah as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);

        $idAsistenPraktikum = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenPraktikum])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        // add conditions that should always apply here
        $dataProviderAsistenTugas= new ActiveDataProvider([
            'query' => $queryAsistenTugas,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($AsistenTugas as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //bimbingan kuliah
        $queryBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks_pengajaran+=$data['jlh_sks_bimbingan_kuliah'];
        }
        $dataProviderBimbinganKuliah= new ActiveDataProvider([
            'query' => $queryBimbinganKuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($SeminarTerjadwal as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderSeminarTerjadwal= new ActiveDataProvider([
            'query' => $querySeminarTerjadwal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $MengujiProposal = $queryMengujiProposal->all();
        foreach ($MengujiProposal as $data){
            $total_sks_pengajaran+=$data['jlh_sks_menguji_proposal'];
        }
        $dataProviderMengujiProposal= new ActiveDataProvider([
            'query' => $queryMengujiProposal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderPenelitian = new ActiveDataProvider([
            'query' => $queryPenelitian,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($Modul as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderModul = new ActiveDataProvider([
            'query' => $queryModul,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderMakalahSeminar= new ActiveDataProvider([
            'query' => $queryMakalahSeminar,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_dosen'];
        }
        $dataProviderJurnalIlmiah= new ActiveDataProvider([
            'query' => $queryJurnalIlmiah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks_pengabdian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderKegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryKegiatanPengabdianMasyarakat,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $dosenJabatan = $queryDosenJabatan->all();
        foreach ($dosenJabatan as $data){
            $total_sks_pengembangan+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderDosenJabatan= new ActiveDataProvider([
            'query' => $queryDosenJabatan,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        $total_sks = ($total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan);
        $semester_id = $semester['semester_id'];
        $temp = $model->getAllFrkFed($status, $id, 1, $semester_id);
        $total_sks_frk = $model->getAllFrkFed($status, $id, 0, $semester_id) + $temp;
        $total_sks_fed = $temp;
        return $this->render('report', [
                //pengajaran
                'dataProviderDosenMatkuliah' => $dataProviderDosenMatkuliah,
                'dataProviderAsistenTugas' => $dataProviderAsistenTugas,
                'dataProviderBimbinganKuliah'=>$dataProviderBimbinganKuliah,
                'dataProviderSeminarTerjadwal'=>$dataProviderSeminarTerjadwal,
                'dataProviderMengujiProposal'=>$dataProviderMengujiProposal,
                //penelitian
                'dataProviderPenelitian'=>$dataProviderPenelitian,
                'dataProviderModul'=>$dataProviderModul,
                'dataProviderMakalahSeminar'=>$dataProviderMakalahSeminar,
                'dataProviderJurnalIlmiah' => $dataProviderJurnalIlmiah,
                //pengabdian-masyarakat
                'dataProviderKegiatanPengabdian' => $dataProviderKegiatanPengabdian,
                //pengembangan-instansi
                'dataProviderDosenJabatan' => $dataProviderDosenJabatan,
                'dosen'=>$model,
                'total_sks'=>$total_sks,
                'total_sks_pengajaran' => $total_sks_pengajaran,
                'total_sks_penelitian' => $total_sks_penelitian,
                'total_sks_pengabdian' => $total_sks_pengabdian,
                'total_sks_pengembangan' => $total_sks_pengembangan,
                'model' => $model,
                'status' => $status,
                'TahunAjaran' => $TahunAjaran,
                'tahun_ajaran_id' => $tahun_ajaran_id,
                'semester_id' => $semester_id,
                'total_sks_fed' => $total_sks_fed,
                'total_sks_frk' => $total_sks_frk,
                'semester'=>$semester,
                'sem' => $sem,
            ]);

    }

    public function actionReportDosen($id, $tahun_ajaran_id, $semester_id){
        $model = $this->findModel($id);
        $status = 'Approve FED';
        $TahunAjaran = \backend\modules\baak\models\TahunAjaran::find()->all();
        $post =  Yii::$app->request->post();
        if($model->load(Yii::$app->request->post())){
            $search = $post['Dosen'];
            $sem = $search['semester_id'];
            $tahun_ajaran_id = $search['tahun_ajaran_id'];
            $semester = \backend\modules\baak\models\Semester::find()
                    ->where(['semester'=>$sem])
                    ->andWhere(['tahun_ajaran_id'=>$tahun_ajaran_id])
                    ->one();
        }else{
            $semester = \backend\modules\baak\models\Semester::findOne($semester_id);
        }

        $total_sks= 0;
        $total_sks_pengajaran = $total_sks_penelitian = $total_sks_pengabdian = $total_sks_pengembangan = 0;
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);
        $matakuliah = $DosenMatakuliah->all();
        $dataProviderDosenMatkuliah= new ActiveDataProvider([
            'query' => $DosenMatakuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($matakuliah as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);

        $idAsistenPraktikum = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenPraktikum])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        // add conditions that should always apply here
        $dataProviderAsistenTugas= new ActiveDataProvider([
            'query' => $queryAsistenTugas,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($AsistenTugas as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //bimbingan kuliah
        $queryBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks_pengajaran+=$data['jlh_sks_bimbingan_kuliah'];
        }
        $dataProviderBimbinganKuliah= new ActiveDataProvider([
            'query' => $queryBimbinganKuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($SeminarTerjadwal as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderSeminarTerjadwal= new ActiveDataProvider([
            'query' => $querySeminarTerjadwal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $MengujiProposal = $queryMengujiProposal->all();
        foreach ($MengujiProposal as $data){
            $total_sks_pengajaran+=$data['jlh_sks_menguji_proposal'];
        }
        $dataProviderMengujiProposal= new ActiveDataProvider([
            'query' => $queryMengujiProposal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderPenelitian = new ActiveDataProvider([
            'query' => $queryPenelitian,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($Modul as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderModul = new ActiveDataProvider([
            'query' => $queryModul,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderMakalahSeminar= new ActiveDataProvider([
            'query' => $queryMakalahSeminar,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_dosen'];
        }
        $dataProviderJurnalIlmiah= new ActiveDataProvider([
            'query' => $queryJurnalIlmiah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks_pengabdian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderKegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryKegiatanPengabdianMasyarakat,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $dosenJabatan = $queryDosenJabatan->all();
        foreach ($dosenJabatan as $data){
            $total_sks_pengembangan+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderDosenJabatan= new ActiveDataProvider([
            'query' => $queryDosenJabatan,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        $total_sks = $total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan;
        return $this->render('report', [
                //pengajaran
                'dataProviderDosenMatkuliah' => $dataProviderDosenMatkuliah,
                'dataProviderAsistenTugas' => $dataProviderAsistenTugas,
                'dataProviderBimbinganKuliah'=>$dataProviderBimbinganKuliah,
                'dataProviderSeminarTerjadwal'=>$dataProviderSeminarTerjadwal,
                'dataProviderMengujiProposal'=>$dataProviderMengujiProposal,
                //penelitian
                'dataProviderPenelitian'=>$dataProviderPenelitian,
                'dataProviderModul'=>$dataProviderModul,
                'dataProviderMakalahSeminar'=>$dataProviderMakalahSeminar,
                'dataProviderJurnalIlmiah' => $dataProviderJurnalIlmiah,
                //pengabdian-masyarakat
                'dataProviderKegiatanPengabdian' => $dataProviderKegiatanPengabdian,
                //pengembangan-instansi
                'dataProviderDosenJabatan' => $dataProviderDosenJabatan,
                'dosen'=>$model,
                'total_sks'=>$total_sks,
                'total_sks_pengajaran' => $total_sks_pengajaran,
                'total_sks_penelitian' => $total_sks_penelitian,
                'total_sks_pengabdian' => $total_sks_pengabdian,
                'total_sks_pengembangan' => $total_sks_pengembangan,
                'model' => $model,
                'status' => $status,
                'TahunAjaran' => $TahunAjaran,
                'semester'=>$semester,
            ]);
    }

    public function actionDownloadReportDosen($id, $tahun_ajaran_id, $semester_id){
        $model = $this->findModel($id);
        $status = 'Approve FED';
        $TahunAjaran = \backend\modules\baak\models\TahunAjaran::find()->all();
        $semester = \backend\modules\baak\models\Semester::findOne($semester_id);
        $total_sks= 0;
        $total_sks_pengajaran = $total_sks_penelitian = $total_sks_pengabdian = $total_sks_pengembangan = 0;
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()->asArray()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);
        $matakuliah = $DosenMatakuliah->all();
        foreach ($matakuliah as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas = \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status' => $status]);
        $modelAsisten = $queryAsistenTugas->all();

        $idAsistenPraktikum = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenPraktikum])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($AsistenTugas as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //bimbingan kuliah
        $queryBimbinganKuliah = \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks_pengajaran+=$data['jlh_sks_bimbingan_kuliah'];
        }
        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $modelSeminarTerjadwal = $querySeminarTerjadwal->all();
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($SeminarTerjadwal as $data){
            $total_sks_pengajaran+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $MengujiProposal = $queryMengujiProposal->all();
        foreach ($MengujiProposal as $data){
            $total_sks_pengajaran+=$data['jlh_sks_menguji_proposal'];
        }
        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $modelPenelitian = $queryPenelitian->all();
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $modelModulBahanAjar = $queryModul->all();
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($Modul as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $modelMakalahSeminar = $queryMakalahSeminar->all();
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $modelJurnalIlmiah = $queryJurnalIlmiah->all();
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks_penelitian+=$data['jlh_sks_beban_dosen'];
        }
        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$model['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $modelKegiatanPengabdian = $queryKegiatanPengabdianMasyarakat->all();
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$model['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks_pengabdian+=$data['jlh_sks_beban_kerja_dosen'];
        }
        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$model['dosen_id']])
                ->andWhere(['semester_id'=>$semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['status'=>$status]);
        $dosenJabatan = $queryDosenJabatan->all();
        foreach ($dosenJabatan as $data){
            $total_sks_pengembangan+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $total_sks = ($total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan);
        $temp = $model->getAllFrkFed($status, $id, 1, $semester_id);
        $total_sks_frk = $model->getAllFrkFed($status, $id, 0, $semester_id) + $temp;
        $total_sks_fed = $temp;
        $pdf = new Pdf([
            'filename' => 'ReportDosen.pdf',
            'mode' => PDF::MODE_UTF8,
            'content' => $this->renderPartial('report_dosen', [
                //pengajaran
                'dataProviderDosenMatkuliah' => $matakuliah,
                'dataProviderAsistenTugas' => $modelAsisten,
                'dataProviderBimbinganKuliah'=>$BimbinganKuliah,
                'dataProviderSeminarTerjadwal'=>$modelSeminarTerjadwal,
                'dataProviderMengujiProposal'=>$MengujiProposal,
                //penelitian
                'dataProviderPenelitian'=>$modelPenelitian,
                'dataProviderModul'=>$modelModulBahanAjar,
                'dataProviderMakalahSeminar'=>$modelMakalahSeminar,
                'dataProviderJurnalIlmiah' => $modelJurnalIlmiah,
                //pengabdian-masyarakat
                'dataProviderKegiatanPengabdian' => $modelKegiatanPengabdian,
                //pengembangan-instansi
                'dataProviderDosenJabatan' => $dosenJabatan,
                'dosen'=>$model,
                'total_sks'=>$total_sks,
                'total_sks_pengajaran' => $total_sks_pengajaran,
                'total_sks_penelitian' => $total_sks_penelitian,
                'total_sks_pengabdian' => $total_sks_pengabdian,
                'total_sks_pengembangan' => $total_sks_pengembangan,
                'model' => $model,
                'status' => $status,
                'TahunAjaran' => $TahunAjaran,
                'tahun_ajaran_id' => $tahun_ajaran_id,
                'semester_id' => $semester_id,
                'total_sks_fed' => $total_sks_fed,
                'total_sks_frk' => $total_sks_frk,
                'total_sks' => $total_sks,
                'model' => $model,
                ]),
            'options' => [
                'title' => 'Laporan Dosen '.$model->nama_dosen,
                'subject' => 'Laporan Dosen'
            ],
            'methods' => [
                'SetHeader' => ['Laporan FRK & FED '.$model->nama_dosen],
                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }

    // Privacy statement output demo
    public function actionDownloadReport() {
        $searchModel = new DosenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $Prodi = \backend\modules\baak\models\InstProdi::find()->all();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();

        $pdf = new Pdf([
            'filename' => 'Laporan.pdf',
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $this->renderPartial('laporan_pdf', ['searchModel'=> $searchModel, 'dataProvider'=> $dataProvider, 'Prodi'=> $Prodi, 'semester'=>$semester]),
            'options' => [
                'title' => 'Laporan FRK/FED Dosen',
                'subject' => 'Laporan'
            ],
            'methods' => [
                'SetHeader' => ['Generated By: TYP05||BAAK'],
                'SetFooter' => ['|Page {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }

    /**
     * Finds the Dosen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dosen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dosen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFrk()
    {
        return $this->render('frk');
    }

    public function actionFrkSummary(){
//        $status_rencana = 'Rencana Kerja';
//        $status_pengajuan = 'Pengajuan FRK';
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $total_sks_pengajaran = $this->countSksPengajaran($dosen->dosen_id);
        $total_sks_penelitian = $this->countSksPenelitian($dosen->dosen_id);
        $total_sks_pengabdian = $this->countSksPengabdianMasyarakat($dosen->dosen_id);
        $total_sks_pengembangan = $this->countSksPengembanganInstansi($dosen->dosen_id);
        $total_sks = $total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan;
        return $this->render('_summary_frk',
            [
                'total_sks_pengajaran' => $total_sks_pengajaran,
                'total_sks_penelitian' => $total_sks_penelitian,
                'total_sks_pengabdian' => $total_sks_pengabdian,
                'total_sks_pengembangan' => $total_sks_pengembangan,
                'total_sks' => $total_sks,
                'dosen'=>$dosen,
            ]
        );
    }

    public function countSksPengajaran($dosen_id){
        $total_sks= 0;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen_id])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all();
        foreach ($DosenMatakuliah as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //asisten
        $DosenAsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen_id])->all();
        $oldIDsAsisten = ArrayHelper::map($DosenAsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $idAsisten= ArrayHelper::map(\backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsisten])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $AsisteTugasPraktikum= \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()->where(['IN', 'asisten_tugas_praktikum_id', $idAsisten])->andWhere(['dosen_id'=>$dosen_id])->all();
        foreach ($AsisteTugasPraktikum as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //bimbingan kuliah
        $BimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()->asArray()
                ->where(['dosen_id'=>$dosen_id])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks+=$data['jlh_sks_bimbingan_kuliah'];
        }

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen_id])->all();
        $oldIDsSeminar = ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $idSeminar = ArrayHelper::map(\backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $Seminar = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()->where(['IN', 'seminar_terjadwal_id', $idSeminar])->andWhere(['dosen_id'=>$dosen_id])->all();
        foreach ($Seminar as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //menguji proposal
        $MengujiProposal= \backend\modules\baak\models\MengujiProposal::find()->asArray()
                ->where(['dosen_id'=>$dosen_id])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all();
        foreach ($MengujiProposal as $data){
            $total_sks+=$data['jlh_sks_menguji_proposal'];
        }
        return $total_sks;
    }

    public function countSksPenelitian($dosen_id){
        $total_sks= 0;
        //penelitian
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen_id])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $idPenelitian = ArrayHelper::map(Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen_id])->all();
        foreach ($Penelitian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen_id])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $idModul = ArrayHelper::map(ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()->where(['IN', 'modul_bahan_ajar_id', $idModul])->andWhere(['dosen_id'=>$dosen_id])->all();
        foreach ($Modul as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen_id])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $idMakalahSeminar = ArrayHelper::map(\backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])->andWhere(['dosen_id'=>$dosen_id])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen_id])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $idJurnalIlmiah= ArrayHelper::map(\backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()
                ->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])
                ->andWhere(['dosen_id'=>$dosen_id])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks+=$data['jlh_sks_beban_dosen'];
        }
        return $total_sks;
    }

    public function countSksPengabdianMasyarakat($dosen_id){
        $total_sks = 0;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen_id])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $idKegiatanPengabdian = ArrayHelper::map(\backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])->andWhere(['dosen_id'=>$dosen_id])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        return $total_sks;
    }

    public function countSksPengembanganInstansi($dosen_id){
        $total_sks = 0;
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $dosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen_id])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]])
                ->all();
        foreach ($dosenJabatan as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        return $total_sks;
    }

    public function actionFrkSayaPenelitian()
    {
//        $status_rencana = 'Rencana Kerja';
//        $status_pengajuan = 'Pengajuan FRK';
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        $dataProviderPenelitian = new ActiveDataProvider([
            'query' => $queryPenelitian,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        // add conditions that should always apply here
        $dataProviderModul = new ActiveDataProvider([
            'query' => $queryModul,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        // add conditions that should always apply here
        $dataProviderMakalahSeminar= new ActiveDataProvider([
            'query' => $queryMakalahSeminar,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //jurnal ilmiah
        $DosenJurnalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsJurnalIlmiah= ArrayHelper::map($DosenJurnalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsJurnalIlmiah])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        // add conditions that should always apply here
        $dataProviderJurnalIlmiah= new ActiveDataProvider([
            'query' => $queryJurnalIlmiah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        $total_sks_pengajaran = $this->countSksPengajaran($dosen->dosen_id);
        $total_sks_penelitian = $this->countSksPenelitian($dosen->dosen_id);
        $total_sks_pengabdian = $this->countSksPengabdianMasyarakat($dosen->dosen_id);
        $total_sks_pengembangan = $this->countSksPengembanganInstansi($dosen->dosen_id);
        $total_sks = $total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan;

        return $this->render('_penelitian',
            [
                'dataProviderPenelitian'=>$dataProviderPenelitian,
                'dataProviderModul'=>$dataProviderModul,
                'dataProviderMakalahSeminar'=>$dataProviderMakalahSeminar,
                'dataProviderJurnalIlmiah' => $dataProviderJurnalIlmiah,
                'dosen'=>$dosen,
                'total_sks' => $total_sks,
            ]
        );
    }

    public function actionFrkSayaPengajaran()
    {
//        $status_rencana = 'Rencana Kerja';
//        $status_pengajuan = 'Pengajuan FRK';
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //dosen matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        $dataProviderDosenMatkuliah= new ActiveDataProvider([
            'query' => $DosenMatakuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        // add conditions that should always apply here
        $dataProviderAsistenTugas= new ActiveDataProvider([
            'query' => $queryAsistenTugas,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //Bimbimbingan Kuliah
        $queryBimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        $dataProviderBimbinganKuliah= new ActiveDataProvider([
            'query' => $queryBimbinganKuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        // add conditions that should always apply here
        $dataProviderSeminarTerjadwal= new ActiveDataProvider([
            'query' => $querySeminarTerjadwal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        $dataProviderMengujiProposal= new ActiveDataProvider([
            'query' => $queryMengujiProposal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        $total_sks_pengajaran = $this->countSksPengajaran($dosen->dosen_id);
        $total_sks_penelitian = $this->countSksPenelitian($dosen->dosen_id);
        $total_sks_pengabdian = $this->countSksPengabdianMasyarakat($dosen->dosen_id);
        $total_sks_pengembangan = $this->countSksPengembanganInstansi($dosen->dosen_id);
        $total_sks = $total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan;


        return $this->render('_pengajaran',
            [
                'dataProviderDosenMatkuliah' => $dataProviderDosenMatkuliah,
                'dataProviderAsistenTugas' => $dataProviderAsistenTugas,
                'dataProviderBimbinganKuliah'=>$dataProviderBimbinganKuliah,
                'dataProviderSeminarTerjadwal'=>$dataProviderSeminarTerjadwal,
                'dataProviderMengujiProposal'=>$dataProviderMengujiProposal,
                'dosen'=>$dosen,
                'total_sks' => $total_sks,
            ]
        );
    }

    public function actionFrkSayaPengabdianMasyarakat()
    {
//        $status_rencana = 'Rencana Kerja';
//        $status_pengajuan = 'Pengajuan FRK';
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        $dataProviderKegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryKegiatanPengabdianMasyarakat,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        $total_sks_pengajaran = $this->countSksPengajaran($dosen->dosen_id);
        $total_sks_penelitian = $this->countSksPenelitian($dosen->dosen_id);
        $total_sks_pengabdian = $this->countSksPengabdianMasyarakat($dosen->dosen_id);
        $total_sks_pengembangan = $this->countSksPengembanganInstansi($dosen->dosen_id);
        $total_sks = $total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan;
        return $this->render('_p_masyarakat',[
            'dataProviderKegiatanPengabdian'=>$dataProviderKegiatanPengabdian,
            'dosen'=>$dosen,
            'total_sks'=>$total_sks,
        ]);
    }

    public function actionFrkSayaPengembanganInstansi(){
//        $status_rencana = 'Rencana Kerja';
//        $status_pengajuan = 'Pengajuan FRK';
        $user_id = Yii::$app->user->id;
        $dosen = Dosen::find()->where(['user_id'=>$user_id])->one();
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0]);
//                ->andWhere(['IN', 'status', [$status_rencana, $status_pengajuan]]);
        $dataProviderDosenJabatan= new ActiveDataProvider([
            'query' => $queryDosenJabatan,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        $total_sks_pengajaran = $this->countSksPengajaran($dosen->dosen_id);
        $total_sks_penelitian = $this->countSksPenelitian($dosen->dosen_id);
        $total_sks_pengabdian = $this->countSksPengabdianMasyarakat($dosen->dosen_id);
        $total_sks_pengembangan = $this->countSksPengembanganInstansi($dosen->dosen_id);
        $total_sks = $total_sks_pengajaran + $total_sks_penelitian + $total_sks_pengabdian + $total_sks_pengembangan;
        return $this->render('_p_instansi',[
            'dataProviderDosenJabatan'=>$dataProviderDosenJabatan,
            'dosen'=>$dosen,
            'total_sks'=>$total_sks,
        ]);
    }

    public function actionFed($id){
        $status_approve = 'Approve FRK';
        $status_pengajuan = 'Pengajuan FED';
        $status_selesai = 'Approve FED';
        $dosen = Dosen::findOne($id);
        $semester = \backend\modules\baak\models\Semester::find()->where(['semester_aktif'=>1])->one();
        $total_sks= 0;
        //BIDANG PENGAJARAN
        //matakuliah
        $DosenMatakuliah = \backend\modules\baak\models\DosenMatakuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $matakuliah = $DosenMatakuliah->all();
        $dataProviderDosenMatkuliah = new ActiveDataProvider([
            'query' => $DosenMatakuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($matakuliah as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        //asisten tugas praktikum
        $AsistenTugasPraktikum = \backend\modules\baak\models\DosenAsistenPraktikum::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsAsistenTugas= ArrayHelper::map($AsistenTugasPraktikum, 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        $queryAsistenTugas= \backend\modules\baak\models\AsistenTugasPraktikum::find()
                ->where(['IN', 'asisten_tugas_praktikum_id', $oldIDsAsistenTugas])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $idAsistenTugas = ArrayHelper::map($queryAsistenTugas->all(), 'asisten_tugas_praktikum_id', 'asisten_tugas_praktikum_id');
        // add conditions that should always apply here
        $AsistenTugas = \backend\modules\baak\models\DosenAsistenPraktikum::find()->asArray()
                ->where(['IN', 'asisten_tugas_praktikum_id', $idAsistenTugas])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        $dataProviderAsistenTugas= new ActiveDataProvider([
            'query' => $queryAsistenTugas,
            'pagination' => [ 'pageSize' => 5 ],
        ]);
        foreach ($AsistenTugas as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        //bimbingan kuliah
        $queryBimbinganKuliah= \backend\modules\baak\models\BimbinganKuliah::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $BimbinganKuliah = $queryBimbinganKuliah->all();
        foreach ($BimbinganKuliah as $data){
            $total_sks+=$data['jlh_sks_bimbingan_kuliah'];
        }
        $dataProviderBimbinganKuliah= new ActiveDataProvider([
            'query' => $queryBimbinganKuliah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //seminar terjadwal
        $DosenSeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsSeminarTerjadwal= ArrayHelper::map($DosenSeminarTerjadwal, 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $querySeminarTerjadwal= \backend\modules\baak\models\SeminarTerjadwal::find()
                ->where(['IN', 'seminar_terjadwal_id', $oldIDsSeminarTerjadwal])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $idSeminarTerjadwal= ArrayHelper::map($querySeminarTerjadwal->all(), 'seminar_terjadwal_id', 'seminar_terjadwal_id');
        $SeminarTerjadwal = \backend\modules\baak\models\DosenSeminarTerjadwal::find()->asArray()
                ->where(['IN', 'seminar_terjadwal_id', $idSeminarTerjadwal])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($SeminarTerjadwal as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }

        // add conditions that should always apply here
        $dataProviderSeminarTerjadwal= new ActiveDataProvider([
            'query' => $querySeminarTerjadwal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //menguji proposal
        $queryMengujiProposal= \backend\modules\baak\models\MengujiProposal::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $MengujiProposal = $queryMengujiProposal->all();
        foreach ($MengujiProposal as $data){
            $total_sks+=$data['jlh_sks_menguji_proposal'];
        }
        $dataProviderMengujiProposal= new ActiveDataProvider([
            'query' => $queryMengujiProposal,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENELITIAN
        //penelitian
        $DosenPenelitian = DosenPenelitian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsPenelitian = ArrayHelper::map($DosenPenelitian, 'penelitian_id', 'penelitian_id');
        $queryPenelitian = Penelitian::find()
                ->where(['IN', 'penelitian_id', $oldIDsPenelitian])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $idPenelitian = ArrayHelper::map($queryPenelitian->all(), 'penelitian_id', 'penelitian_id');
        $Penelitian = DosenPenelitian::find()->asArray()->where(['IN', 'penelitian_id', $idPenelitian])->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Penelitian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderPenelitian = new ActiveDataProvider([
            'query' => $queryPenelitian,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //modul bahan ajar
        $DosenModulAjar = DosenModulBahanAjar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsModul = ArrayHelper::map($DosenModulAjar, 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $queryModul = ModulBahanAjar::find()
                ->where(['IN', 'modul_bahan_ajar_id', $oldIDsModul])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $idModul = ArrayHelper::map($queryModul->all(), 'modul_bahan_ajar_id', 'modul_bahan_ajar_id');
        $Modul = DosenModulBahanAjar::find()->asArray()
                ->where(['IN', 'modul_bahan_ajar_id', $idModul])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($Modul as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderModul = new ActiveDataProvider([
            'query' => $queryModul,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //makalah seminar
        $DosenMakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsMakalahSeminar = ArrayHelper::map($DosenMakalahSeminar, 'makalah_seminar_id', 'makalah_seminar_id');
        $queryMakalahSeminar= \backend\modules\baak\models\MakalahSeminar::find()
                ->where(['IN', 'makalah_seminar_id', $oldIDsMakalahSeminar])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $idMakalahSeminar = ArrayHelper::map($queryMakalahSeminar->all(), 'makalah_seminar_id', 'makalah_seminar_id');
        $MakalahSeminar = \backend\modules\baak\models\DosenMakalahSeminar::find()->asArray()
                ->where(['IN', 'makalah_seminar_id', $idMakalahSeminar])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($MakalahSeminar as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        // add conditions that should always apply here
        $dataProviderMakalahSeminar= new ActiveDataProvider([
            'query' => $queryMakalahSeminar,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //jurnal seminar
        $DosenJunalIlmiah = \backend\modules\baak\models\DosenJurnalIlmiah::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsjurnalIlmiah= ArrayHelper::map($DosenJunalIlmiah, 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $queryJurnalIlmiah= \backend\modules\baak\models\JurnalIlmiah::find()
                ->where(['IN', 'jurnal_ilmiah_id', $oldIDsjurnalIlmiah])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $idJurnalIlmiah= ArrayHelper::map($queryJurnalIlmiah->all(), 'jurnal_ilmiah_id', 'jurnal_ilmiah_id');
        $JurnalIlmiah= \backend\modules\baak\models\DosenJurnalIlmiah::find()->asArray()
                ->where(['IN', 'jurnal_ilmiah_id', $idJurnalIlmiah])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($JurnalIlmiah as $data){
            $total_sks+=$data['jlh_sks_beban_dosen'];
        }
        $dataProviderJurnalIlmiah= new ActiveDataProvider([
            'query' => $queryJurnalIlmiah,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGABDIAN MASYARAKAT
        //kegiatan pengabdian masyarakat
        $DosenKegiatanMasyarakat= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->where(['dosen_id'=>$dosen['dosen_id']])->all();
        $oldIDsKegiatanMasyarakat= ArrayHelper::map($DosenKegiatanMasyarakat, 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $queryKegiatanPengabdianMasyarakat= \backend\modules\baak\models\KegiatanPengabdianMasyarakat::find()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $oldIDsKegiatanMasyarakat])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $idKegiatanPengabdian = ArrayHelper::map($queryKegiatanPengabdianMasyarakat->all(), 'kegiatan_pengabdian_masyarakat_id', 'kegiatan_pengabdian_masyarakat_id');
        $KegiatanPengabdian= \backend\modules\baak\models\DosenKegiatanPengabdian::find()->asArray()
                ->where(['IN', 'kegiatan_pengabdian_masyarakat_id', $idKegiatanPengabdian])
                ->andWhere(['dosen_id'=>$dosen['dosen_id']])->all();
        foreach ($KegiatanPengabdian as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderKegiatanPengabdian= new ActiveDataProvider([
            'query' => $queryKegiatanPengabdianMasyarakat,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //BIDANG PENGEMBANGAN INSTANSI
        //jabatan-dosen
        $queryDosenJabatan = \backend\modules\baak\models\DosenJabatan::find()
                ->where(['dosen_id'=>$dosen['dosen_id']])
                ->andWhere(['semester_id' => $semester['semester_id']])
                ->andWhere(['deleted'=>0])
                ->andWhere(['IN', 'status', [$status_approve, $status_pengajuan, $status_selesai]]);
        $dosenJabatan = $queryDosenJabatan->all();
        foreach ($dosenJabatan as $data){
            $total_sks+=$data['jlh_sks_beban_kerja_dosen'];
        }
        $dataProviderDosenJabatan= new ActiveDataProvider([
            'query' => $queryDosenJabatan,
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        //from model
        $status = $status_approve;
        $dosen_id = $dosen['dosen_id'];
        $semester_id = $semester['semester_id'];
        if($dosen->getStatusFed($dosen_id)>0){
            $total_sks_terealisasi = $dosen->getAllFrkFed('Pengajuan FED', $dosen_id, 1, $semester_id);
            $total_sks_tidak_terealisasi = $dosen->getAllFrkFed('Pengajuan FED', $dosen_id, 0, $semester_id);
        }else if($dosen->getStatusFedSelesai($dosen_id)>0){
            $total_sks_terealisasi = $dosen->getAllFrkFed('Approve FED', $dosen_id, 1, $semester_id);
            $total_sks_tidak_terealisasi = $dosen->getAllFrkFed('Approve FED', $dosen_id, 0, $semester_id);
        }else{
            $total_sks_terealisasi = $dosen->getAllFrkFed($status, $dosen_id, 1, $semester_id);
            $total_sks_tidak_terealisasi = $dosen->getAllFrkFed($status, $dosen_id, 0, $semester_id);
        }
        return $this->render('fed',
            [
                //pangajaran
                'dataProviderDosenMatkuliah' => $dataProviderDosenMatkuliah,
                'dataProviderAsistenTugas' => $dataProviderAsistenTugas,
                'dataProviderBimbinganKuliah'=>$dataProviderBimbinganKuliah,
                'dataProviderSeminarTerjadwal'=>$dataProviderSeminarTerjadwal,
                'dataProviderMengujiProposal'=>$dataProviderMengujiProposal,

                //penelitian
                'dataProviderPenelitian'=>$dataProviderPenelitian,
                'dataProviderModul'=>$dataProviderModul,
                'dataProviderMakalahSeminar'=>$dataProviderMakalahSeminar,
                'dataProviderJurnalIlmiah' => $dataProviderJurnalIlmiah,

                //pengabdian-masyarakat
                'dataProviderKegiatanPengabdian' => $dataProviderKegiatanPengabdian,

                //pengembangan-instansi
                'dataProviderDosenJabatan' => $dataProviderDosenJabatan,
                'dosen'=>$dosen,
                'total_sks_terealisasi'=>$total_sks_terealisasi,
                'total_sks_tidak_terealisasi'=>$total_sks_tidak_terealisasi,
            ]
        );
    }
}
