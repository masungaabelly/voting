<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Voting Chatbot';
?>
<div class="site-chatbot">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="chat-box">
        <div id="chat-log"></div>
        <input type="text" id="chat-input" placeholder="Ask me about voting...">
        <button id="send-btn">Send</button>
    </div>
</div>

<?php
$script = <<<JS
$(document).ready(function() {
    $('#send-btn').click(function() {
        var question = $('#chat-input').val();
        if (question) {
            $('#chat-log').append('<div><b>You:</b> ' + question + '</div>');
            $('#chat-input').val('');

            $.post('chatbot', {question: question}, function(data) {
                $('#chat-log').append('<div><b>Bot:</b> ' + data.response + '</div>');
            });
        }
    });
});
JS;
$this->registerJs($script);
?>