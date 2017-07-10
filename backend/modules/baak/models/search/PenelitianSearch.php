<?php

namespace backend\modules\baak\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\Penelitian;

/**
 * PenelitianSearch represents the model behind the search form about `backend\modules\baak\models\Penelitian`.
 */
class PenelitianSearch extends Penelitian
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['penelitian_id', 'semester_id', 'header_dokumen_bukti_id', 'jlh_target', 'deleted', 'tahapan_penelitian_id', 'status_realisasi', 'status_all_dokumen'], 'integer'],
            [['jenis_penelitian', 'judul_penelitian', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'status',], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Penelitian::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'penelitian_id' => $this->penelitian_id,
            'semester_id' => $this->semester_id,
            'header_dokumen_bukti_id' => $this->header_dokumen_bukti_id,
            'jlh_target' => $this->jlh_target,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'tahapan_penelitian_id' => $this->tahapan_penelitian_id,
            'status_realisasi' => $this->status_realisasi,
            'status_all_dokumen' => $this->satus_all_dokumen,
        ]);

        $query->andFilterWhere(['like', 'jenis_penelitian', $this->jenis_penelitian])
            ->andFilterWhere(['like', 'judul_penelitian', $this->judul_penelitian])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
