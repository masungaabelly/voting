<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Connection;
use yii\web\UploadedFile;

class CandidateRegistrationForm extends Model
{
    public $candidate_firstname;
    public $candidate_lastname;
    public $vote_count;
    public $id;
    public $candidate_description;
    public $candidate_email;
    public $image; // Add an attribute for the image upload

    /**
     * @var UploadedFile
     */
    public $imageFile; // Attribute to hold the uploaded file instance

    public static function findOne($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['candidate_firstname', 'candidate_lastname', 'candidate_description', 'candidate_email'], 'required'],
            [['vote_count'], 'integer'],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg'], // Image file validation rule
        ];
    }

    public function save()
    {
        $existingUser = Candidate::find()->where([
            'candidate_email' => $this->candidate_email,
            'candidate_firstname' => $this->candidate_firstname,
            'candidate_lastname' => $this->candidate_lastname,
        ])->one();

        if ($existingUser) {
            $this->addError('candidate_email', 'Candidate exists');
            $this->addError('candidate_firstname', 'Candidate exists');
            $this->addError('candidate_lastname', 'Candidate exists');
            return false;
        }

        // Upload the image file if it exists
        if ($this->imageFile = UploadedFile::getInstance($this, 'imageFile')) {
            $imageName = 'candidate_' . Yii::$app->security->generateRandomString(10) . '.' . $this->imageFile->extension;
            $imagePath = 'images/' . $imageName;
            $this->imageFile->saveAs(Yii::getAlias('@webroot') . '/' . $imagePath);
        } else {
            $imageName = ''; // If no image uploaded, set empty string
        }

        $db = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=voting2',
            'username' => 'root',
            'password' => '',
        ]);

        $db->createCommand()->insert('candidate', [
            'candidate_firstname' => $this->candidate_firstname,
            'candidate_lastname' => $this->candidate_lastname,
            'candidate_description' => $this->candidate_description,
            'candidate_email' => $this->candidate_email,
            'image' => $imageName, // Save the image name to the database
        ])->execute();

        return true;
    }

    public function attributeLabels()
    {
        return [
            'candidate_firstname' => 'First name',
            'candidate_lastname' => 'Last name',
            'candidate_description' => 'Description',
            'candidate_email' => 'Candidate\'s Email',
            'imageFile' => 'Candidate\'s Image', // Label for the image upload field
        ];
    }
}
