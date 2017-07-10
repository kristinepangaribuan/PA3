<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_r_jenis_proposal".
 *
 * @property integer $jenis_proposal_id
 * @property string $jenis_proposal
 *
 * @property BaakMengujiProposal[] $baakMengujiProposals
 */
class JenisProposal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_r_jenis_proposal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenis_proposal'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jenis_proposal_id' => 'Jenis Proposal ID',
            'jenis_proposal' => 'Jenis Proposal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakMengujiProposals()
    {
        return $this->hasMany(MengujiProposal::className(), ['jenis_proposal_id' => 'jenis_proposal_id']);
    }
}
