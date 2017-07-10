<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_inst_struktur_jabatan".
 *
 * @property integer $struktur_jabatan_id
 * @property integer $instansi_id
 * @property string $jabatan
 * @property integer $parent
 * @property string $inisial
 * @property integer $id_multi_tenant
 * @property integer $unit_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $update_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property BaakDosenJabatan[] $baakDosenJabatans
 */
class InstStrukturJabatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_inst_struktur_jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instansi_id', 'parent', 'id_multi_tenant', 'unit_id', 'deleted','jlh_sks'], 'integer'],
            [['deleted_at', 'update_at', 'created_at'], 'safe'],
            [['jabatan', 'inisial'], 'string', 'max' => 45],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'struktur_jabatan_id' => 'Struktur Jabatan ID',
            'instansi_id' => 'Instansi ID',
            'jabatan' => 'Jabatan',
            'parent' => 'Parent',
            'inisial' => 'Inisial',
            'id_multi_tenant' => 'Id Multi Tenant',
            'unit_id' => 'Unit ID',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'update_at' => 'Update At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenJabatans()
    {
        return $this->hasMany(DosenJabatan::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }
    
    public function getJabatan($struktur_jabatan_id){
        return InstStrukturJabatan::findOne($struktur_jabatan_id);
    }
}
