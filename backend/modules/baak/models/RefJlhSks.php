<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_ref_jlh_sks".
 *
 * @property integer $ref_jlh_sks_id
 * @property string $name
 * @property double $jlh_sks
 */
class RefJlhSks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_ref_jlh_sks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jlh_sks'], 'number'],
            [['name'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ref_jlh_sks_id' => 'Ref Jlh Sks ID',
            'name' => 'Name',
            'jlh_sks' => 'Jlh Sks',
        ];
    }
}
