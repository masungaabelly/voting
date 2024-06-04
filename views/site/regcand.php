<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\CandidateRegistrationForm $model */


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Candidate Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to Register Candidate:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'options' => ['enctype' => 'multipart/form-data'], // Add this line for file uploads
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'candidate_firstname')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'candidate_lastname')->textInput() ?>

            <?= $form->field($model, 'candidate_description')->textarea() ?>

            <?= $form->field($model, 'candidate_email')->textInput() ?>

            <!-- Add input field for image upload -->
            <?= $form->field($model, 'imageFile')->fileInput() ?>

            <div class="form-group d-flex justify-content-between">
                <?= Html::submitButton('Register Candidate', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
                <p>Redirecting you to the login page...</p>
                <meta http-equiv="refresh" content="3;url=<?= Yii::$app->urlManager->createUrl(['site/showcandidates']) ?>">
            <?php endif; ?>

        </div>
    </div>
</div>
