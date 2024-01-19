<?php

declare(strict_types=1);

abstract class QuestionHandler {
    abstract public function display(Question $question);
    abstract public function evaluate(Question $question, $postData, &$scoreTotal, &$scoreCorrect);
}
