<?php

namespace app\models;

use Yii;
use yii\base\Model;

use yii\db\Connection;



class UserRegistrationForm extends Model
{
    public $firstname;
    public $lastname;

    public $password;
    public $password2;
    public $user_email;





    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['firstname', 'lastname', 'password', 'password2', 'user_email'], 'required'],

            ['user_email', 'email'],

            ['password2', 'compare', 'compareAttribute' => 'password', 'message' => "passwords don't match"],
        ];
    }


    public function save()
    {
        // Check if username or email already exists
        $existingUser = User::find()->where(['user_email' => $this->user_email])->one();

        if ($existingUser) {
            if ($existingUser->user_email === $this->user_email) {
                $this->addError('user_email', 'Email is already taken.');
            }
            return false;
        }

        $db = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=voting2',
            'username' => 'root',
            'password' => '',
        ]);

        $db->createCommand()->insert('user', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'password' => $this->password,
            'user_email' => $this->user_email,
        ])->execute();

        return true;
    }


    public function attributeLabels()
    {
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name', // Specify the label for the username attribute
            'password' => 'Password',
            'password2' => 'Confirm Password',
            'user_email' => 'Email',
        ];
    }





}
