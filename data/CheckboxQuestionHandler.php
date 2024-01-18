<?php

declare(strict_types=1);

require_once 'QuestionHandler.php';

class CheckboxQuestionHandler extends QuestionHandler {
    public function display(Question $question) {
        echo $question->label . "<br>";
        $i = 0;
        foreach ($question->choices as $choice) {
            $i += 1;
            echo "<input type='checkbox' name='" . $question->uuid . "[]' value='" . $choice . "' id='" . $question->uuid . "-$i'>";
            echo "<label for='" . $question->uuid . "-$i'>" . $choice . "</label>";
        }
    }

    public function evaluate(Question $question, $postData, &$scoreTotal, &$scoreCorrect) {
        $scoreTotal += $question->score;

        if (!is_null($postData)) {
            $correctAnswers = is_array($question->correct) ? $question->correct : [$question->correct];
            $userAnswers = $postData;

            // Vérifiez si les réponses de l'utilisateur correspondent exactement aux réponses correctes
            if (count(array_diff($correctAnswers, $userAnswers)) === 0 &&
                count(array_diff($userAnswers, $correctAnswers)) === 0) {
                $scoreCorrect += $question->score;
            }
        }
    }
}

