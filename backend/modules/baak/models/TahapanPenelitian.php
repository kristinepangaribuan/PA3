<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_tahapan_penelitian".
 *
 * @property integer $tahapan_penelitian_id
 * @property string $tahapan_penelitian
 * @property integer $jlh_persentasi
 * @property integer $is_parent_of
 */
class TahapanPenelitian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_tahapan_penelitian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jlh_persentasi', 'is_parent_of'], 'integer'],
            [['tahapan_penelitian'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahapan_penelitian_id' => 'Tahapan Penelitian ID',
            'tahapan_penelitian' => 'Tahapan Penelitian',
            'jlh_persentasi' => 'Jlh Persentasi',
            'is_parent_of' => 'Is Parent Of',
        ];
    }
    
    public function getPenelitians()
    {
        return $this->hasMany(Penelitian::className(), ['tahapan_penelitian_id' => 'tahapan_penelitian_id']);
    }
}
