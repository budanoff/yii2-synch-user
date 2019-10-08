<?php

namespace budanoff\synchuser\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property string $pwd
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property int $id_org
 *
 * @property Request[] $requests
 */
class User extends ActiveRecord
{
    public $pwd;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'pwd', 'email',  'name'], 'required'],
            [['status', 'created_at', 'updated_at', 'id_org'], 'integer'],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'string', 'max' => 60],
            [['password_hash', 'password_reset_token', 'email', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name' => 'Name',
            'id_org' => 'Id Org',
        ];
    }



    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert==1) {
                $this->created_at = time();
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            $this->updated_at = time();
            $this->password_hash = Yii::$app->security->generatePasswordHash(trim($this->pwd));
            $this->email = (filter_var($this->email, FILTER_VALIDATE_EMAIL))?$this->email:"empty@email.ru";
            $this->status = ($this->status==1)?10:0;
            $this->id_org = ($this->id_org!="")?$this->id_org:null;

            return true;
        }
        return false;
    }
}