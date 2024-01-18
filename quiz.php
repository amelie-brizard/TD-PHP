<?php

declare(strict_types=1);

require_once 'DataLoader.php';
require_once 'Question.php';
require_once 'Quiz.php';
require_once 'QuestionHandler.php';
require_once 'TextQuestionHandler.php';
require_once 'RadioQuestionHandler.php';
require_once 'CheckboxQuestionHandler.php';

$dataLoader = new DataLoader('data/model.json');
$questionsData = $dataLoader->getData();

$quiz = new Quiz();

foreach ($questionsData as $data) {
    $uuid = $data['uuid'];
    $type = $data['type'];
    $label = $data['label'];
    $choices = $data['choices'];
    $correct = $data['correct'];
    $score = $data['score'];

    $question = new Question($uuid, $type, $label, $choices, $correct, $score);

    switch ($type) {
        case 'text':
            $handler = new TextQuestionHandler();
            break;
        case 'radio':
            $handler = new RadioQuestionHandler();
            break;
        case 'checkbox':
            $handler = new CheckboxQuestionHandler();
            break;
        default:
            $handler = new TextQuestionHandler();
            break;
    }

    $quiz->addQuestion($question, $handler);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz->evaluate($_POST);
} else {
    $quiz->displayForm();
}
