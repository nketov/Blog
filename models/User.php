<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $profile
 * @property string $auth_key
 *
 * @property Post[] $posts
 */
class User extends ActiveRecord implements IdentityInterface
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required'],
            [['profile'], 'string'],
            [['username', 'password', 'email'], 'string', 'max' => 128],
            [['auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'profile' => 'Profile',
            'auth_key' => 'Auth Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['author_id' => 'id']);
    }


    public  function isAdmin()
    {
        return $this->username === 'admin';
    }



//  User Identity


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }



public static function findIdentityByAccessToken($token, $type = null)
{
//        return static::findOne(['access_token' => $token]);
}


public static function findByUsername($username)
{
    return static::findOne(['username' => $username] );
}


public function getId()
{
    return $this->id;
}


public function getAuthKey()
{
    return $this->auth_key;
}

public function validateAuthKey($authKey)
{
    return $this->auth_key === $authKey;
}



public function validatePassword($password)
{
    return Yii::$app->security->validatePassword($password, $this->password);
}


public function generateAuthKey()
{
    $this->auth_key = Yii::$app->security->generateRandomString();
}

}
