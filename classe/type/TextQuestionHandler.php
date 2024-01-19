<?php

declare(strict_types=1);

namespace classe\type;

use classe\type\QuestionHandler;

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
