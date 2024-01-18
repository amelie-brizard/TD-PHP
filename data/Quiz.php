<?php

declare(strict_types=1);

require_once 'Question.php';
require_once 'QuestionHandler.php';
require_once 'TextQuestionHandler.php';
require_once 'RadioQuestionHandler.php';
require_once 'CheckboxQuestionHandler.php';

class Quiz {
    private $questions = [];
    private $handlers = [];

    public function addQuestion(Question $question, QuestionHandler $handler) {
        $this->questions[] = $question;
        $this->handlers[$question->uuid] = $handler;
    }

    public function displayFormWithAddQuestion() {
        echo "<h2>Formulaire de Quiz</h2>";
        $this->displayForm();

        echo "<h2>Ajouter une nouvelle question</h2>";
        $this->displayAjoutQuestionForm();
    }

    public function displayForm() {
        echo "<form method='POST' action='quiz.php'><ol>";
        foreach ($this->questions as $question) {
            echo "<li>";
            $this->handlers[$question->uuid]->display($question);
        }
        echo "</ol><input type='submit' value='Envoyer'></form>";
    }

    public function evaluate($postData) {
        $questionTotal = 0;
        $questionCorrect = 0;
        $scoreTotal = 0;
        $scoreCorrect = 0;

        foreach ($this->questions as $question) {
            $questionTotal += 1;
            $this->handlers[$question->uuid]->evaluate($question, $postData[$question->uuid] ?? NULL, $scoreTotal, $scoreCorrect);

            if (!is_null($postData[$question->uuid]) && $question->correct == $postData[$question->uuid]) {
                $questionCorrect += 1;
            }
        }

        echo "Réponses correctes: " . $questionCorrect . "/" . $questionTotal . "<br>";
        echo "Votre score: " . $scoreCorrect . "/" . $scoreTotal . "<br>";
    }

    public function displayAjoutQuestionForm() {
        echo "<form method='POST' action='question.php'>";
        echo "<label for='uuid'>UUID:</label>";
        echo "<input type='text' name='uuid' required><br>";
        echo "<label for='type'>Type:</label>";
        echo "<select name='type' required>";
        echo "<option value='' selected disabled>Choisissez le type</option>";
        echo "<option value='radio'>Radio</option>";
        echo "<option value='text'>Text</option>";
        echo "<option value='checkbox'>Checkbox</option>";
        echo "</select><br>";
        echo "<label for='label'>Label:</label>";
        echo "<input type='text' name='label' required><br>";
        echo "<label for='score'>Score:</label>";
        echo "<input type='number' name='score' required><br>";
        echo "<input type='submit' name='ajouter_question' value='Ajouter la question'>";
        echo "</form>";
    }

    public function evaluateAjoutQuestionForm($postData) {
        $ajouterQuestionHandler = new AjouterQuestionHandler();
        $message = $ajouterQuestionHandler->handleAjoutQuestion($postData);

        if ($message) {
            echo "<p>$message</p>";
        }
    }
}
