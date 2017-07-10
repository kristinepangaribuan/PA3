<?php

namespace backend\modules\baak\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baak\models\Dosen;

/**
 * DosenSearch represents the model behind the search form about `backend\modules\baak\models\Dosen`.
 */
class DosenSearch extends Dosen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'golongan_kepangkatan_id', 'deleted', 'user_id', 'ref_kbk_id'], 'integer'],
            [['nama_dosen', 'email', 'alamat', 'nidn', 'status_ikatan_kerja', 'aktif_start', 'aktif_end', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'status', 's1', 's2', 's3', 'ilmu_yg_ditekuni'], 'safe'],
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
        $query = Dosen::find();

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
            'dosen_id' => $this->dosen_id,
            'golongan_kepangkatan_id' => $this->golongan_kepangkatan_id,
            'aktif_start' => $this->aktif_start,
            'aktif_end' => $this->aktif_end,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'user_id' => $this->user_id,
            'ref_kbk_id' => $this->ref_kbk_id,
        ]);

        $query->andFilterWhere(['like', 'nama_dosen', $this->nama_dosen])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'nidn', $this->nidn])
            ->andFilterWhere(['like', 'status_ikatan_kerja', $this->status_ikatan_kerja])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 's1', $this->s1])
            ->andFilterWhere(['like', 's2', $this->s2])
            ->andFilterWhere(['like', 's3', $this->s3])
            ->andFilterWhere(['like', 'ilmu_yg_ditekuni', $this->ilmu_yg_ditekuni]);

        return $dataProvider;
    }
}
