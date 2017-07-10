<?php

namespace backend\modules\baak\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\AsistenTugasPraktikum;

/**
 * AsistenTugasPraktikumSearch represents the model behind the search form about `backend\modules\baak\models\AsistenTugasPraktikum`.
 */
class AsistenTugasPraktikumSearch extends AsistenTugasPraktikum
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asisten_tugas_praktikum_id', 'jlh_mhs_praktikum', 'kuliah_id', 'semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_realisasi', 'status_all_dokumen'], 'integer'],
            [['jlh_sks_asistensi'], 'number'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'status'], 'safe'],
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
        $query = AsistenTugasPraktikum::find();

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
            'asisten_tugas_praktikum_id' => $this->asisten_tugas_praktikum_id,
            'jlh_sks_asistensi' => $this->jlh_sks_asistensi,
            'jlh_mhs_praktikum' => $this->jlh_mhs_praktikum,
            'kuliah_id' => $this->kuliah_id,
            'semester_id' => $this->semester_id,
            'header_dokumen_bukti_id' => $this->header_dokumen_bukti_id,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'status_realisasi' => $this->status_realisasi,
            'status_all_dokumen' => $this->satus_all_dokumen,
        ]);
        $query->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
