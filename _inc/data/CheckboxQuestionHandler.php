<?php
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
?>