<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */


$this->title = 'Voting';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container">
    <h1>Voting</h1>
    <table>
        <thead>
            <tr>
                <th>Candidate Image</th>
                <th>Candidate Names</th>
                <th>Vote</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($candidates as $candidate): ?>
                <tr>
                    <?php $imageUrl = Url::to('@web/images/' . $candidate['image']) ?>
                    <td><img class="card-img-top border-bottom" src="<?= $imageUrl ?>" alt="Candidate Image"
                            style="width: auto; height: auto;" /></td>
                    <td><?= $candidate['candidate_firstname'] . ' ' . $candidate['candidate_lastname'] ?></td>
                    <td>
                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->vote_status === 0): ?>
                            <?= Html::a('Vote', ['site/vote-action', 'candidateId' => $candidate['id']], ['class' => 'btn btn-primary']) ?>
                        <?php elseif (Yii::$app->user->isGuest): ?>
                            <?= Html::a('Login to vote', ['site/login'], ['class' => 'btn btn-primary']) ?>
                        <?php else: ?>
                            <?= Html::a('Vote', ['site/index'], ['class' => 'btn btn-primary disabled']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php
$script = <<<JS
    $(document).on('click', '.vote-btn', function() {
        var candidateId = $(this).data('candidate-id');
        $.ajax({
            url: '/site/vote',
            type: 'post',
            data: {candidateId: candidateId},
            success: function(response) {
                // Redirect to the home page after successful voting
                window.location.href = '/site/index';
            
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while voting.');
            }
        });
    });
JS;
$this->registerJs($script);
?>