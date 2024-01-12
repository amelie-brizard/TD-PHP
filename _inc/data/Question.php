<?php
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
?>