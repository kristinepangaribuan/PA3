<?php

namespace backend\modules\baak\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\ModulBahanAjar;

/**
 * ModulBahanAjarSearch represents the model behind the search form about `backend\modules\baak\models\ModulBahanAjar`.
 */
class ModulBahanAjarSearch extends ModulBahanAjar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modul_bahan_ajar_id', 'semester_id', 'header_dokumen_bukti_id', 'jlh_targer', 'jlh_sks_modul', 'deleted', 'status_realisasi', 'status_all_dokumen'], 'integer'],
            [['nama_modul', 'tahapan_modul', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'status',], 'safe'],
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
        $query = ModulBahanAjar::find();

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
            'modul_bahan_ajar_id' => $this->modul_bahan_ajar_id,
            'semester_id' => $this->semester_id,
            'header_dokumen_bukti_id' => $this->header_dokumen_bukti_id,
            'jlh_targer' => $this->jlh_targer,
            'jlh_sks_modul' => $this->jlh_sks_modul,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'status_realisasi' => $this->status_realisasi,
            'status_all_dokumen' => $this->satus_all_dokumen,
        ]);

        $query->andFilterWhere(['like', 'nama_modul', $this->nama_modul])
            ->andFilterWhere(['like', 'tahapan_modul', $this->tahapan_modul])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
