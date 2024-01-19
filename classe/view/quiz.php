<?php

declare(strict_types=1);

namespace classe;

use classe\data\DataLoader;
use classe\Question;
use classe\Quiz;
use classe\type\QuestionHandler;
use classe\type\TextQuestionHandler;
use classe\type\RadioQuestionHandler;
use classe\type\CheckboxQuestionHandler;

echo <<< EOL
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Quiz</title>
    <link rel='stylesheet' href='../../css/quiz.css'>
</head>
<body>
<header>
<nav> 
<ul>
<li><a href='../../index.php'>Acceuil</a></li>
<li><a href='./question.php'>Ajouter des questions</a></li>
<li><a href='./quiz.php'>Quiz</a></li>
</ul>
</nav>
</header>
<main>
EOL;

$dataLoader = new DataLoader('../json/model.json');
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

echo <<< EOL
</main>
</body>
</html>
EOL;
