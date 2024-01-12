<?php
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

?>