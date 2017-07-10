<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_menguji_proposal".
 *
 * @property integer $menguji_proposal_id
 * @property string $jenis_proposal
 * @property string $judul_proposal
 * @property integer $jlh_mhs_proposal
 * @property double $jlh_sks_menguji_proposal
 * @property integer $dosen_id
 * @property integer $semester_id
 * @property integer $header_dokumen_bukti_id
 * @property integer $jenis_proposal_id
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $status
 * @property integer $status_realisasi
 * @property integer $status_all_dokumen
 *
 * @property BaakDokumenBuktiMengujiProposal[] $baakDokumenBuktiMengujiProposals
 * @property BaakDosen $dosen
 * @property BaakHeaderDokumenBukti $headerDokumenBukti
 * @property BaakRJenisProposal $jenisProposal
 * @property BaakRSemester $semester
 */
class MengujiProposal extends \yii\db\ActiveRecord
{
    public $tahun_ajaran;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_menguji_proposal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jlh_mhs_proposal', 'dosen_id', 'semester_id', 'header_dokumen_bukti_id', 'jenis_proposal_id', 'deleted', 'status_all_dokumen'], 'integer'],
            [['jenis_proposal_id'], 'required', 'message' => 'Jenis Proposal Harus Dipilih'],
            [['judul_proposal'], 'required', 'message' => 'Judul Proposal Harus Diisi'],
            [['jlh_mhs_proposal'], 'required', 'message' => 'Jumlah Mahasiswa Harus Diisi'],
            [['jlh_sks_menguji_proposal'], 'number'],
            [['created_at', 'updated_at', 'deleted_at', 'status_realisasi'], 'safe'],
            [['judul_proposal'], 'string', 'max' => 200],
            [['created_by', 'updated_by', 'deleted_by', 'status'], 'string', 'max' => 32],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['dosen_id' => 'dosen_id']],
            [['header_dokumen_bukti_id'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderDokumenBukti::className(), 'targetAttribute' => ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']],
            [['jenis_proposal_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisProposal::className(), 'targetAttribute' => ['jenis_proposal_id' => 'jenis_proposal_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => Semester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menguji_proposal_id' => 'Menguji Proposal ID',
            'jenis_proposal' => 'Jenis Proposal',
            'judul_proposal' => 'Judul Proposal',
            'jlh_mhs_proposal' => 'Jlh Mhs Proposal',
            'jlh_sks_menguji_proposal' => 'Jlh Sks Menguji Proposal',
            'dosen_id' => 'Dosen',
            'semester_id' => 'Semester',
            'header_dokumen_bukti_id' => 'Header Dokumen Bukti',
            'jenis_proposal_id' => 'Jenis Proposal',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDokumenBuktiMengujiProposals()
    {
        return $this->hasMany(DokumenBuktiMengujiProposal::className(), ['menguji_proposal_id' => 'menguji_proposal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeaderDokumenBukti()
    {
        return $this->hasOne(HeaderDokumenBukti::className(), ['header_dokumen_bukti_id' => 'header_dokumen_bukti_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisProposal()
    {
        return $this->hasOne(JenisProposal::className(), ['jenis_proposal_id' => 'jenis_proposal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(Semester::className(), ['semester_id' => 'semester_id']);
    }
}
