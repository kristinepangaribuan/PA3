<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_r_jenis_bimbingan".
 *
 * @property integer $jenis_bimbingan_id
 * @property string $jenis_bimbingan
 * @property integer $header_dokumen_bukti_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property BaakBimbinganKuliah[] $baakBimbinganKuliahs
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 */
class JenisBimbingan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_r_jenis_bimbingan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header_dokumen_bukti_id', 'deleted'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['jenis_bimbingan'], 'string', 'max' => 30],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jenis_bimbingan_id' => 'Jenis Bimbingan ID',
            'jenis_bimbingan' => 'Jenis Bimbingan',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti ID',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakBimbinganKuliahs()
    {
        return $this->hasMany(BimbinganKuliah::className(), ['jenis_bimbingan_id' => 'jenis_bimbingan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeaderDokumenBukti()
    {
        return $this->hasOne(HeaderDokumenBukti::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }
}
