<?php

namespace backend\modules\baak\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\DosenMatakuliah;

/**
 * DosenMatakuliahSearch represents the model behind the search form about `backend\modules\baak\models\DosenMatakuliah`.
 */
class DosenMatakuliahSearch extends DosenMatakuliah
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_matakuliah_id', 'dosen_id', 'kuliah_id', 'jlh_tatap_muka_dosen', 'semester_id', 'header_dokumen_bukti_id', 'jlh_mhs_matakuliah', 'deleted', 'status', 'status_realisasi', 'status_all_dokumen'], 'integer'],
            [['jlh_sks_beban_kerja_dosen'], 'number'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by'], 'safe'],
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
        $query = DosenMatakuliah::find();

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
            'dosen_matakuliah_id' => $this->dosen_matakuliah_id,
            'dosen_id' => $this->dosen_id,
            'kuliah_id' => $this->kuliah_id,
            'jlh_tatap_muka_dosen' => $this->jlh_tatap_muka_dosen,
            'jlh_sks_beban_kerja_dosen' => $this->jlh_sks_beban_kerja_dosen,
            'semester_id' => $this->semester_id,
            'header_dokumen_bukti_id' => $this->header_dokumen_bukti_id,
            'jlh_mhs_matakuliah' => $this->jlh_mhs_matakuliah,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'status' => $this->status,
            'status_realisasi' => $this->status_realisasi,
            'status_all_dokumen' => $this->satus_all_dokumen,
        ]);

        $query->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
