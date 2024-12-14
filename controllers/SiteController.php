<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UserRegistrationForm;
use app\models\CandidateRegistrationForm;
use yii\db\Connection;
use yii\db\Query;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    // In your SiteController

    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $db = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=voting2',
            'username' => 'root',
            'password' => '',
        ]);

        $candidates = $db->createCommand('SELECT * FROM candidate')->queryAll();

        $users = $db->createCommand('SELECT *  FROM user ')->queryOne();


        return $this->render('index', [
            'candidates' => $candidates,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Displays Registering users.
     *
     * 
     */

    public function actionRegister()
    {

        $model = new UserRegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Registration successful. please login with your new account');
            return $this->redirect(['site/login']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }


    /**
     * Displays registering candidates.
     *
     * 
     */

    public function actionRegcand()
    {
        $model = new CandidateRegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Candidate Registration successful.');
            return $this->redirect(['site/showcandidates']);
        }

        return $this->render('regcand', [
            'model' => $model,
        ]);
    }


    /**
     * Displays showing candidates
     *
     */

    public function actionShowcandidates()
    {
        $db = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=voting2',
            'username' => 'root',
            'password' => '',
        ]);

        $candidates = $db->createCommand('SELECT * FROM candidate')->queryAll();
        return $this->render('showcandidates', ['candidates' => $candidates]);
    }


    /**
     * Displays voting here.
     *
     */


    public function actionVote()
    {

        $db = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=voting2',
            'username' => 'root',
            'password' => '',
        ]);

        $candidates = $db->createCommand('SELECT * FROM candidate')->queryAll();

        // You can redirect the user to the home page
        return $this->render('vote', ['candidates' => $candidates]);

    }

    public function actionVoteAction($candidateId)
    {
        // Get the database connection component
        $db = Yii::$app->db;

        // Update vote status for the user
        $userId = Yii::$app->user->identity->id;
        $db->createCommand()
            ->update('user', ['vote_status' => 1], ['id' => $userId])
            ->execute();

        // Increment vote count for the candidate
        $db->createCommand()
            ->update('candidate', ['vote_count' => new \yii\db\Expression('vote_count + 1')], ['id' => $candidateId])
            ->execute();

        // Redirect to the homepage
        return $this->redirect(['site/index']);
    }


    public function actionVotingResults()
    {
        $db = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=voting2',
            'username' => 'root',
            'password' => '',
        ]);

        $candidates = $db->createCommand('SELECT * FROM candidate')->queryAll();
        return $this->render('voting-results', ['candidates' => $candidates]);
    }
    public function actionChatbot()
    {
        if (Yii::$app->request->isPost) {
            $question = Yii::$app->request->post('question');
            $response = $this->generateResponse($question);
            return $this->asJson(['response' => $response]);
        }

        return $this->render('chatbot');
    }

    private function generateResponse($question)
    {
        // Basic response logic
        $question = strtolower($question);
        if (strpos($question, 'how to vote') !== false) {
            return 'To vote, please visit the voting page and follow the instructions.';
        } elseif (strpos($question, 'voting time') !== false) {
            return 'Voting is open from 9 AM to 5 PM on the voting day.';
        } elseif (strpos($question, 'requirements to vote') !== false) {
            return 'You need a valid ID and voter registration to vote.';
        } else {
            return 'Sorry, I did not understand your question. Please try asking something else.';
        }
    }





}
