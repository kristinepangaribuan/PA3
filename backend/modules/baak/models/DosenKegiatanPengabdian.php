<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dosen_kegiatan_pengabdian".
 *
 * @property integer $dosen_kegiatan_pengabdian_id
 * @property integer $dosen_id
 * @property integer $kegiatan_pengabdian_masyarakat_id
 * @property double $jlh_sks_beban_kerja_dosen
 * @property string $jabatan_dlm_kegiatan
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property integer $status
 *
 * @property BaakKegiatanPengabdianMasyarakat $kegiatanPengabdianMasyarakat
 * @property BaakDosen $dosen
 */
class DosenKegiatanPengabdian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dosen_kegiatan_pengabdian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'kegiatan_pengabdian_masyarakat_id', 'deleted', 'status'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['jabatan_dlm_kegiatan'], 'string', 'max' => 30],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['kegiatan_pengabdian_masyarakat_id'], 'exist', 'skipOnError' => true, 'targetClass' => KegiatanPengabdianMasyarakat::className(), 'targetAttribute' => ['kegiatan_pengabdian_masyarakat_id' => 'kegiatan_pengabdian_masyarakat_id']],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_kegiatan_pengabdian_id' => 'Dosen Kegiatan Pengabdian ID',
            'dosen_id' => 'Dosen ID',
            'kegiatan_pengabdian_masyarakat_id' => 'Kegiatan Pengabdian Masyarakat ID',
            'jlh_sks_beban_kerja_dosen' => 'Jlh Sks Beban Kerja Dosen',
            'jabatan_dlm_kegiatan' => 'Jabatan Dlm Kegiatan',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatanPengabdianMasyarakat()
    {
        return $this->hasOne(KegiatanPengabdianMasyarakat::className(), ['kegiatan_pengabdian_masyarakat_id' => 'kegiatan_pengabdian_masyarakat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }
}
