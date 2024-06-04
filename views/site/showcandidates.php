<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="container">
    <h1>CANDIDATES</h1>
    <table>
        <thead>
            <tr>
                <th>Candidate id</th>
                <th>Candidate Image</th>
                <th>Candidate Names</th>
                <th>Candidate email</th>
                <th>Vote count</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($candidates as $candidate): ?>
                <tr>
                    <?php $imageUrl = Url::to('@web/images/' . $candidate['image']) ?>
                    <td>
                        <?= $candidate['id'] ?>
                    </td>
                    <td><img class="card-img-top border-bottom" src="<?= $imageUrl ?>" alt="Candidate Image"
                            style="width: auto; height: auto;" /></td>
                    <td><?= $candidate['candidate_firstname'] . ' ' . $candidate['candidate_lastname'] ?></td>
                    <td>
                        <?= $candidate['candidate_email'] ?>
                    </td>
                    <td>
                        <?= $candidate['vote_count'] ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>