<?php
require "AjouterQuestionHandler.php";
/**
 * Classe Question
 * Représente une question dans un quiz avec son texte, type, choix possibles, réponse attendue et score.
 */
class Question {
    public $uuid;
    public $type;
    public $label;
    public $choices;
    public $correct;
    public $score;

    /**
     * Constructeur de la classe Question.
     *
     * @param string $uuid    Identifiant unique de la question.
     * @param string $type    Type de la question (text, radio, checkbox).
     * @param string $label   Texte de la question.
     * @param array  $choices Choix possibles (pour les questions de type radio ou checkbox).
     * @param mixed  $correct Réponse attendue.
     * @param int    $score   Score de la question.
     */
    public function __construct($uuid, $type, $label, $choices, $correct, $score) {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->label = $label;
        $this->choices = $choices;
        $this->correct = $correct;
        $this->score = $score;
    }
}


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

/**
 * Classe abstraite QuestionHandler
 * Définit le comportement attendu d'un gestionnaire de question.
 */
abstract class QuestionHandler {
    /**
     * Affiche la question dans le formulaire.
     *
     * @param Question $question Question à afficher.
     */
    abstract public function display(Question $question);

    /**
     * Évalue la réponse soumise et met à jour le score.
     *
     * @param Question $question    Question à évaluer.
     * @param mixed    $postData    Réponse soumise dans le formulaire.
     * @param int      $scoreTotal   Score total.
     * @param int      $scoreCorrect Score correct.
     */
    abstract public function evaluate(Question $question, $postData, &$scoreTotal, &$scoreCorrect);
}

/**
 * Classe TextQuestionHandler
 * Gestionnaire de question pour les questions de type texte.
 */
class TextQuestionHandler extends QuestionHandler {
    public function display(Question $question) {
        echo $question->label . "<br><input type='text' name='" . $question->uuid . "'><br>";
    }

    public function evaluate(Question $question, $postData, &$scoreTotal, &$scoreCorrect) {
        $scoreTotal += $question->score;
        if (!is_null($postData) && $question->correct == $postData) {
            $scoreCorrect += $question->score;
        }
    }
}

/**
 * Classe RadioQuestionHandler
 * Gestionnaire de question pour les questions de type radio.
 */
class RadioQuestionHandler extends QuestionHandler {
    public function display(Question $question) {
        echo $question->label . "<br>";
        $i = 0;
        foreach ($question->choices as $choice) {
            $i += 1;
            echo "<input type='radio' name='" . $question->uuid . "' value='" . $choice . "' id='" . $question->uuid . "-$i'>";
            echo "<label for='" . $question->uuid . "-$i'>" . $choice . "</label>";
        }
    }

    public function evaluate(Question $question, $postData, &$scoreTotal, &$scoreCorrect) {
        $scoreTotal += $question->score;
        if (!is_null($postData) && $question->correct === $postData) {
            $scoreCorrect += $question->score;
        }
    }
}

/**
 * Classe CheckboxQuestionHandler
 * Gestionnaire de question pour les questions de type checkbox.
 */
class CheckboxQuestionHandler extends QuestionHandler {
    public function display(Question $question) {
        echo $question->label . "<br>";
        $i = 0;
        foreach ($question->choices as $choice) {
            $i += 1;
            echo "<input type='checkbox' name='" . $question->uuid . "[]' value='" . $choice["value"] . "' id='" . $question->uuid . "-$i'>";
            echo "<label for='" . $question->uuid . "-$i'>" . $choice["text"] . "</label>";
        }
    }

    public function evaluate(Question $question, $postData, &$scoreTotal, &$scoreCorrect) {
        $scoreTotal += $question->score;

        if (!is_null($postData)) {
            sort($postData); // Assurez-vous que les réponses sélectionnées sont triées

            $diff1 = array_diff($question->correct, $postData);
            $diff2 = array_diff($postData, $question->correct);

            if (count($diff1) == 0 && count($diff2) == 0) {
                $scoreCorrect += $question->score;
            }
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