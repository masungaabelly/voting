<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii;


class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    // Define the password_hash property as protected
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */


    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['firstname' => $username]);
    }


    public function validatePassword($password)
{
    return $this->password === $password;
}


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        // Implement logic to return auth key if needed
        return ;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        // Implement logic to validate auth key if needed
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Gets the role of the user
     *
     * @return string|null
     */
    public function getRole()
    {
        return $this->role;
    }
}