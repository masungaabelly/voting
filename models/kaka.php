<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;
    private $admin;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$this->validatePasswordHash($this->password, $user->password_hash)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    private function validatePasswordHash($password, $hash)
    {
        // Validate password hash
        if (!is_string($password) || $password === '') {
            throw new \InvalidArgumentException('Password must be a string and cannot be empty.');
        }

        // Perform password verification
        if (function_exists('password_verify')) {
            return password_verify($password, $hash);
        }

        // Legacy password hashing
        $test = crypt($password, $hash);
        $n = strlen($test);
        if ($n !== 60 || !hash_equals($test, $hash)) {
            throw new \InvalidArgumentException('Hash is invalid.');
        }

        return true;
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
