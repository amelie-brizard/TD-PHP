<?php
require "AjouterQuestionHandler.php";
require "./_inc/data/Question.php";
require "./_inc/data/QuestionHandler.php";
require "./_inc/data/TextQuestionHandler.php";
require "./_inc/data/RadioQuestionHandler.php";
require "./_inc/data/CheckboxQuestionHandler.php";

/**
 * Classe Quiz
 * Gère un ensemble de questions dans un quiz et permet d'afficher le formulaire de quiz et d'évaluer les réponses.
 */
class Quiz {
    private $questions = [];
    private $handlers = [];

    /**
     * Ajoute une question au quiz avec son gestionnaire associé.
     *
     * @param Question         $question Question à ajouter.
     * @param QuestionHandler $handler  Gestionnaire de la question.
     */
    public function addQuestion(Question $question, QuestionHandler $handler) {
        $this->questions[] = $question;
        $this->handlers[$question->uuid] = $handler;  // Mise à jour de la référence à la propriété correcte
    }
    
    /**
     * Affiche le formulaire de quiz avec toutes les questions.
     */
    public function displayForm() {
        echo "<form method='POST' action='quiz.php'><ol>";
        foreach ($this->questions as $question) {
            echo "<li>";
            $this->handlers[$question->uuid]->display($question);
        }
        echo "</ol><input type='submit' value='Envoyer'></form>";
    }
    /**
     * Évalue les réponses soumises dans le formulaire et affiche le score.
     *
     * @param array $postData Données soumises dans le formulaire.
     */
    public function evaluate($postData) {
        $questionTotal = 0;
        $questionCorrect = 0;  // Ajout de cette ligne pour initialiser le compteur de réponses correctes
        $scoreTotal = 0;
        $scoreCorrect = 0;

        foreach ($this->questions as $question) {
            $questionTotal += 1;
            $this->handlers[$question->uuid]->evaluate($question, $postData[$question->uuid] ?? NULL, $scoreTotal, $scoreCorrect);
            
            // Utilisez $question->correct au lieu de $question->correctAnswer
            if (!is_null($postData[$question->uuid]) && $question->correct == $postData[$question->uuid]) {
                $questionCorrect += 1;
            }
        }

        echo "Réponses correctes: " . $questionCorrect . "/" . $questionTotal . "<br>";
        echo "Votre score: " . $scoreCorrect . "/" . $scoreTotal . "<br>";
    }

    /**
     * Affiche le formulaire pour ajouter une nouvelle question.
     */
    public function displayAjoutQuestionForm() {
        echo "<form method='POST' action='quiz.php'>";
        echo "<label for='uuid'>UUID:</label>";
        echo "<input type='text' name='uuid' required><br>";

        echo "<label for='type'>Type:</label>";
        echo "<select name='type' required>";
        echo "<option value='radio'>Radio</option>";
        echo "<option value='text'>Text</option>";
        echo "<option value='checkbox'>Checkbox</option>";
        echo "</select><br>";

        echo "<label for='label'>Label:</label>";
        echo "<input type='text' name='label' required><br>";

        // Ajouter d'autres champs en fonction du type de question

        echo "<input type='submit' name='ajouter_question' value='Ajouter la question'>";
        echo "</form>";
    }

    /**
     * Évalue les réponses soumises dans le formulaire d'ajout de question.
     */
    public function evaluateAjoutQuestionForm($postData) {
        $ajouterQuestionHandler = new AjouterQuestionHandler();
        $message = $ajouterQuestionHandler->handleAjoutQuestion($postData);

        if ($message) {
            echo "<p>$message</p>";
        }
    }
}

// Lecture des questions depuis le fichier JSON
$jsonData = file_get_contents('data/model.json');
$questionsData = json_decode($jsonData, true);

// Création d'une instance de Quiz et ajout des questions avec les gestionnaires associés.
$quiz = new Quiz();

foreach ($questionsData as $data) {
    $uuid = $data['uuid'];
    $type = $data['type'];
    $label = $data['label'];
    $choices = $data['choices'];
    $correct = $data['correct'];
    $score = $data['score'];  // Ajout de cette ligne pour récupérer la propriété $score

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

// Affichage du formulaire ou évaluation des réponses selon la méthode de requête.
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $quiz->displayForm();
} else {
    $quiz->evaluate($_POST);
}
?>