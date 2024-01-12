<?php
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
?>