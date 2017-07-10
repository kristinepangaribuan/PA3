<?php

namespace backend\modules\baak\models;

use Yii;

/**
 * This is the model class for table "baak_role_user".
 *
 * @property integer $role_user_id
 * @property string $role_user
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property BaakSysxUser[] $baakSysxUsers
 */
class RoleUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'baak_role_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['role_user', 'deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_user_id' => 'Role User ID',
            'role_user' => 'Role User',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaakSysxUsers()
    {
        return $this->hasMany(User::className(), ['role_user_id' => 'role_user_id']);
    }
}
