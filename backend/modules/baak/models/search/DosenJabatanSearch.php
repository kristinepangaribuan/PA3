<?php

namespace backend\modules\baak\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\DosenJabatan;

/**
 * DosenJabatanSearch represents the model behind the search form about `backend\modules\baak\models\DosenJabatan`.
 */
class DosenJabatanSearch extends DosenJabatan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_jabatan_id', 'dosen_id', 'struktur_jabatan_id', 'semester_id', 'header_dokumen_bukti_id', 'deleted', 'status_realisasi', 'status_all_dokumen'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by', 'status'], 'safe'],
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
        $query = DosenJabatan::find();

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
            'dosen_jabatan_id' => $this->dosen_jabatan_id,
            'dosen_id' => $this->dosen_id,
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'semester_id' => $this->semester_id,
            'header_dokumen_bukti_id' => $this->header_dokumen_bukti_id,
            'jlh_sks_beban_kerja_dosen' => $this->jlh_sks_beban_kerja_dosen,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'status_realisasi' => $this->status_realisasi,
            'status_all_dokumen' => $this->satus_all_dokumen,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
