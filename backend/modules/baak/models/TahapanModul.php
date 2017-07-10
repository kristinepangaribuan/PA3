<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_tahapan_modul".
 *
 * @property integer $tahapan_modul_id
 * @property string $tahapan_modul
 * @property integer $jlh_presentasi
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property BaakModulBahanAjar[] $baakModulBahanAjars
 */
class TahapanModul extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_tahapan_modul';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jlh_presentasi', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['tahapan_modul'], 'string', 'max' => 120],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahapan_modul_id' => 'Tahapan Modul ID',
            'tahapan_modul' => 'Tahapan Modul',
            'jlh_presentasi' => 'Jlh Presentasi',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModulBahanAjars()
    {
        return $this->hasMany(ModulBahanAjar::className(), ['tahapan_modul_id' => 'tahapan_modul_id']);
    }
}
