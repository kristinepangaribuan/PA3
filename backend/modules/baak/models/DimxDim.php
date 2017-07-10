<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_dimx_dim".
 *
 * @property integer $dim_id
 * @property string $nim
 * @property string $no_usm
 * @property string $jalur
 * @property string $user_name
 * @property string $kbk_id
 * @property integer $ref_kbk_id
 * @property string $kpt_prodi
 * @property integer $id_kur
 * @property integer $tahun_kurikulum_id
 * @property string $nama
 * @property string $tgl_lahir
 * @property string $tempat_lahir
 * @property string $gol_darah
 * @property integer $golongan_darah_id
 * @property string $jenis_kelamin
 * @property integer $jenis_kelamin_id
 * @property string $agama
 * @property integer $agama_id
 * @property string $alamat
 * @property string $kabupaten
 * @property string $kode_pos
 * @property string $email
 * @property string $telepon
 * @property string $hp
 * @property string $no_ijazah_sma
 * @property string $nama_sma
 * @property integer $asal_sekolah_id
 * @property string $alamat_sma
 * @property string $kabupaten_sma
 * @property string $telepon_sma
 * @property string $kodepos_sma
 * @property integer $thn_masuk
 * @property string $status_akhir
 * @property string $nama_ayah
 * @property string $nama_ibu
 * @property string $no_hp_ayah
 * @property string $no_hp_ibu
 * @property string $alamat_orangtua
 * @property string $pekerjaan_ayah
 * @property integer $pekerjaan_ayah_id
 * @property string $keterangan_pekerjaan_ayah
 * @property string $penghasilan_ayah
 * @property integer $penghasilan_ayah_id
 * @property string $pekerjaan_ibu
 * @property integer $pekerjaan_ibu_id
 * @property string $keterangan_pekerjaan_ibu
 * @property string $penghasilan_ibu
 * @property integer $penghasilan_ibu_id
 * @property string $nama_wali
 * @property string $pekerjaan_wali
 * @property integer $pekerjaan_wali_id
 * @property string $keterangan_pekerjaan_wali
 * @property string $penghasilan_wali
 * @property integer $penghasilan_wali_id
 * @property string $alamat_wali
 * @property string $telepon_wali
 * @property string $no_hp_wali
 * @property string $pendapatan
 * @property double $ipk
 * @property integer $anak_ke
 * @property integer $dari_jlh_anak
 * @property integer $jumlah_tanggungan
 * @property double $nilai_usm
 * @property integer $score_iq
 * @property string $rekomendasi_psikotest
 * @property string $foto
 * @property string $kode_foto
 * @property integer $user_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $course_group_id
 *
 * @property BaakPrklKrsMhs[] $baakPrklKrsMhs
 */
class DimxDim extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_dimx_dim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ref_kbk_id', 'id_kur', 'tahun_kurikulum_id', 'golongan_darah_id', 'jenis_kelamin_id', 'agama_id', 'asal_sekolah_id', 'thn_masuk', 'pekerjaan_ayah_id', 'penghasilan_ayah_id', 'pekerjaan_ibu_id', 'penghasilan_ibu_id', 'pekerjaan_wali_id', 'penghasilan_wali_id', 'anak_ke', 'dari_jlh_anak', 'jumlah_tanggungan', 'score_iq', 'user_id', 'deleted', 'course_group_id'], 'integer'],
            [['nama'], 'required'],
            [['tgl_lahir', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['alamat', 'alamat_sma', 'alamat_orangtua', 'keterangan_pekerjaan_ayah', 'keterangan_pekerjaan_ibu', 'keterangan_pekerjaan_wali', 'alamat_wali'], 'string'],
            [['ipk', 'nilai_usm'], 'number'],
            [['nim', 'kodepos_sma'], 'string', 'max' => 8],
            [['no_usm'], 'string', 'max' => 15],
            [['jalur', 'kbk_id', 'telepon_wali'], 'string', 'max' => 20],
            [['user_name', 'kpt_prodi'], 'string', 'max' => 10],
            [['nama', 'tempat_lahir', 'kabupaten', 'email', 'telepon', 'hp', 'nama_sma', 'telepon_sma', 'status_akhir', 'nama_ayah', 'nama_ibu', 'no_hp_ayah', 'no_hp_ibu', 'penghasilan_ayah', 'penghasilan_ibu', 'nama_wali', 'pekerjaan_wali', 'penghasilan_wali', 'no_hp_wali', 'pendapatan', 'foto'], 'string', 'max' => 50],
            [['gol_darah'], 'string', 'max' => 2],
            [['jenis_kelamin'], 'string', 'max' => 1],
            [['agama'], 'string', 'max' => 30],
            [['kode_pos'], 'string', 'max' => 5],
            [['no_ijazah_sma', 'kabupaten_sma', 'pekerjaan_ayah', 'pekerjaan_ibu', 'kode_foto'], 'string', 'max' => 100],
            [['rekomendasi_psikotest'], 'string', 'max' => 4],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dim_id' => 'Dim ID',
            'nim' => 'Nim',
            'no_usm' => 'No Usm',
            'jalur' => 'Jalur',
            'user_name' => 'User Name',
            'kbk_id' => 'Kbk ID',
            'ref_kbk_id' => 'Ref Kbk ID',
            'kpt_prodi' => 'Kpt Prodi',
            'id_kur' => 'Id Kur',
            'tahun_kurikulum_id' => 'Tahun Kurikulum ID',
            'nama' => 'Nama',
            'tgl_lahir' => 'Tgl Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'gol_darah' => 'Gol Darah',
            'golongan_darah_id' => 'Golongan Darah ID',
            'jenis_kelamin' => 'Jenis Kelamin',
            'jenis_kelamin_id' => 'Jenis Kelamin ID',
            'agama' => 'Agama',
            'agama_id' => 'Agama ID',
            'alamat' => 'Alamat',
            'kabupaten' => 'Kabupaten',
            'kode_pos' => 'Kode Pos',
            'email' => 'Email',
            'telepon' => 'Telepon',
            'hp' => 'Hp',
            'no_ijazah_sma' => 'No Ijazah Sma',
            'nama_sma' => 'Nama Sma',
            'asal_sekolah_id' => 'Asal Sekolah ID',
            'alamat_sma' => 'Alamat Sma',
            'kabupaten_sma' => 'Kabupaten Sma',
            'telepon_sma' => 'Telepon Sma',
            'kodepos_sma' => 'Kodepos Sma',
            'thn_masuk' => 'Thn Masuk',
            'status_akhir' => 'Status Akhir',
            'nama_ayah' => 'Nama Ayah',
            'nama_ibu' => 'Nama Ibu',
            'no_hp_ayah' => 'No Hp Ayah',
            'no_hp_ibu' => 'No Hp Ibu',
            'alamat_orangtua' => 'Alamat Orangtua',
            'pekerjaan_ayah' => 'Pekerjaan Ayah',
            'pekerjaan_ayah_id' => 'Pekerjaan Ayah ID',
            'keterangan_pekerjaan_ayah' => 'Keterangan Pekerjaan Ayah',
            'penghasilan_ayah' => 'Penghasilan Ayah',
            'penghasilan_ayah_id' => 'Penghasilan Ayah ID',
            'pekerjaan_ibu' => 'Pekerjaan Ibu',
            'pekerjaan_ibu_id' => 'Pekerjaan Ibu ID',
            'keterangan_pekerjaan_ibu' => 'Keterangan Pekerjaan Ibu',
            'penghasilan_ibu' => 'Penghasilan Ibu',
            'penghasilan_ibu_id' => 'Penghasilan Ibu ID',
            'nama_wali' => 'Nama Wali',
            'pekerjaan_wali' => 'Pekerjaan Wali',
            'pekerjaan_wali_id' => 'Pekerjaan Wali ID',
            'keterangan_pekerjaan_wali' => 'Keterangan Pekerjaan Wali',
            'penghasilan_wali' => 'Penghasilan Wali',
            'penghasilan_wali_id' => 'Penghasilan Wali ID',
            'alamat_wali' => 'Alamat Wali',
            'telepon_wali' => 'Telepon Wali',
            'no_hp_wali' => 'No Hp Wali',
            'pendapatan' => 'Pendapatan',
            'ipk' => 'Ipk',
            'anak_ke' => 'Anak Ke',
            'dari_jlh_anak' => 'Dari Jlh Anak',
            'jumlah_tanggungan' => 'Jumlah Tanggungan',
            'nilai_usm' => 'Nilai Usm',
            'score_iq' => 'Score Iq',
            'rekomendasi_psikotest' => 'Rekomendasi Psikotest',
            'foto' => 'Foto',
            'kode_foto' => 'Kode Foto',
            'user_id' => 'User ID',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'course_group_id' => 'Course Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakPrklKrsMhs()
    {
        return $this->hasMany(BaakPrklKrsMhs::className(), ['dim_id' => 'dim_id']);
    }
}
