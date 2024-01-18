<?php

declare(strict_types=1);

class Question {
    public $uuid;
    public $type;
    public $label;
    public $choices;
    public $correct;
    public $score;

    public function __construct($uuid, $type, $label, $choices, $correct, $score) {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->label = $label;
        $this->choices = $choices;
        $this->correct = $correct;
        $this->score = $score;
    }
}
