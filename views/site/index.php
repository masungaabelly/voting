<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'ONLINE VOTING SYSTEM';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Welcome!</h1>

        <p class="lead">Welcome to the official mbogo voting platform</p>
        <p class="lead">Jump right into action and vote for the leader you please</p>


        <p><a class="btn btn-success" href="<?= Url::to(['/site/vote']) ?>">Vote &raquo;</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class=" mb-3">
                <h2>Cast Your Vote: Shape the Future</h2>
                <h2>Your Voice, Your Vote: Make it Count!</h2>


            </div>


        </div>

    </div>
</div>