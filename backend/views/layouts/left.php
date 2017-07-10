<aside class="main-sidebar">
    
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php $dosen = backend\modules\baak\models\Dosen::find()->where(['user_id'=>Yii::$app->user->id])->one();
                if($dosen)
                    echo $dosen['nama_dosen'];
                else
                    echo Yii::$app->user->identity->username;
                ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <?php 
            $user = backend\modules\baak\models\User::findOne(Yii::$app->user->id);
            $roleUser = backend\modules\baak\models\RoleUser::findOne($user['role_user_id']);
            $dosen = \backend\modules\baak\models\Dosen::find()->where(['user_id'=>$user['user_id']])->one();
            $asesor = \backend\modules\baak\models\PenugasanAsesor::find()->where(['dosen_id'=>$dosen['dosen_id']])->one();
            if($asesor != null){
                $visibel_asesor = TRUE;
            }else{
                $visibel_asesor = FALSE;
            }
            $dosen_id  = $dosen['dosen_id'];
            if($roleUser['role_user']=='K-Prodi'){
                $allRequestingFrk = backend\modules\baak\models\StatusFrkDosen::requestingFrk($dosen_id);
                $allRequestingFed = backend\modules\baak\models\StatusFedDosen::requestingFed($dosen_id);
        ?> 
            <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'BAAK', 'options' => ['class' => 'header']],
                    ['label' => 'Beranda', 'icon' => 'home', 'url' => ['/site/index']],
                    [
                        'label' => 'FRK',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Tambah FRK', 'icon' => 'plus', 'url' => ['/baak/dosen/frk'],],
                            ['label' => 'Overview', 'icon' => 'file-text', 'url' => ['/baak/dosen/frk-summary'],],
                        ],
                    ],
                    ['label' => 'Notifikasi Request FRK ('.$allRequestingFrk.')', 'icon' => 'bell', 'url' => ['/baak/dosen/list-frk-dosen']],
                    ['label' => 'FED', 'icon' => 'folder', 'url' => ['/baak/dosen/fed', 'id'=>$dosen['dosen_id']]],
                    ['label' => 'Notifikasi Request FED ('.$allRequestingFed.')', 'icon' => 'bell', 'url' => ['/baak/dosen/list-fed-dosen', 'id'=>$asesor['dosen_id']], 'visible'=>$visibel_asesor],
                    ['label' => 'Laporan', 'icon' => 'book', 'url' => ['/baak/dosen/index']],
                    ['label' => 'Penugasan Asesor', 'icon' => 'book', 'url' => ['/baak/penugasan-asesor/penugasan-asesor-view-dosen', 'id'=>$asesor['penugasan_asesor_id']], 'visible'=>$visibel_asesor],
                    ['label' => 'Keluar', 'icon' => 'user', 'url' => ['/baak/default/logout']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                ],                ]
            );}
            else if($roleUser['role_user']=='Admin'){ 
                $allRequestingFrk = backend\modules\baak\models\StatusFrkDosen::requestingFrk($dosen_id);
                $allRequestingFed = backend\modules\baak\models\StatusFedDosen::requestingFed($dosen_id);
                $notifikasiFrk = backend\modules\baak\models\StatusFrkDosen::notifikasiFrk($dosen_id);
                $notifikasiFed = backend\modules\baak\models\StatusFedDosen::notifikasiFed($dosen_id);
                ?>
        
                <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Beranda', 'icon' => 'home', 'url' => ['/site/index']],
                    ['label' => 'Laporan', 'icon' => 'book', 'url' => ['/baak/dosen/index']],
                    ['label' => 'Penugasan Asesor', 'icon' => 'book', 'url' => ['/baak/penugasan-asesor/index']],
                    ['label' => 'Tambah Dosen', 'icon' => 'plus', 'url' => ['/baak/dosen/create']],
                    ['label' => 'Keluar', 'icon' => 'user', 'url' => ['/baak/default/logout']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ],
                ]
            );}else if($roleUser['role_user']=='Dosen'){
                $notifikasiFrk = backend\modules\baak\models\StatusFrkDosen::notifikasiFrk($dosen_id);
                $notifikasiFed = backend\modules\baak\models\StatusFedDosen::notifikasiFed($dosen_id);
                $allRequestingFed = backend\modules\baak\models\StatusFedDosen::requestingFed($dosen_id);
                ?>
                <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Beranda', 'icon' => 'home', 'url' => ['/site/index']],
                    [
                        'label' => 'FRK',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Tambah FRK', 'icon' => 'plus', 'url' => ['/baak/dosen/frk'],],
                            ['label' => 'Overview', 'icon' => 'sign-in', 'url' => ['/baak/dosen/frk-summary'],],
                            ['label' => 'Notifikasi ('.$notifikasiFrk.')', 'icon' => 'bell', 'url' => ['/baak/dosen/notifikasi-frk'],],
                        ],
                    ],
                    [
                        'label' => 'FED',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Overview', 'icon' => 'sign-in', 'url' => ['/baak/dosen/fed', 'id'=>$dosen['dosen_id']],],
                            ['label' => 'Notifikasi ('.$notifikasiFed.')', 'icon' => 'bell', 'url' => ['/baak/dosen/notifikasi-fed'],],
                        ],
                    ],
                    ['label' => 'Notifikasi Request FED ('.$allRequestingFed.')', 'icon' => 'bell', 'url' => ['/baak/dosen/list-fed-dosen', 'id'=>$asesor['dosen_id']], 'visible'=>$visibel_asesor],
                    ['label' => 'Laporan', 'icon' => 'book', 'url' => ['/baak/dosen/index']],
                    ['label' => 'Penugasan Asesor', 'icon' => 'book', 'url' => ['/baak/penugasan-asesor/penugasan-asesor-view-dosen', 'id'=>$asesor['penugasan_asesor_id']], 'visible'=>$visibel_asesor],
                    ['label' => 'Keluar', 'icon' => 'user', 'url' => ['/baak/default/logout']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ],
                ]
            );}else{?>
                <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Beranda', 'icon' => 'home', 'url' => ['/site/index']],
                    ['label' => 'Laporan', 'icon' => 'book', 'url' => ['/baak/dosen/index']],
                    ['label' => 'Keluar', 'icon' => 'user', 'url' => ['/baak/default/logout']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ],
                ]
            );
            }
        ?>
        
    </section>

</aside>
