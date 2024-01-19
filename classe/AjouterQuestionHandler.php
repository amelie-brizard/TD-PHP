<?php

declare(strict_types=1);

require_once('Question.php');
require_once('Request.php');

class AjouterQuestionHandler {
    public function handleAjoutQuestion(Request $request): ?string {
        $uuid = $request->get('uuid');
        $type = $request->get('type');
        $label = $request->get('label');
        $correct = $request->get('correct');
        $choices = $request->get('choices') ?? [];
        $score = (int)$request->get('score');

        // Vous pouvez ajouter une validation supplémentaire ici si nécessaire

        $newQuestion = new Question($uuid, $type, $label, $choices, $correct, $score);

        // Ajouter la nouvelle question à votre stockage de données (par exemple, fichier JSON)
        $this->ajouterQuestionDansJSON($newQuestion);

        return "Question ajoutée avec succès.";
    }

    private function ajouterQuestionDansJSON(Question $question): void {
        // Charger les questions existantes depuis le fichier JSON
        $jsonData = file_get_contents('../json/model.json');
        $questionsData = json_decode($jsonData, true);

        // Ajouter la nouvelle question
        $questionsData[] = [
            'uuid' => $question->uuid,
            'type' => $question->type,
            'label' => $question->label,
            'correct' => $question->correct,
            'choices' => $question->choices,
            'score' => $question->score,
        ];

        // Convertir le tableau en format JSON
        $newJsonData = json_encode($questionsData, JSON_PRETTY_PRINT);

        // Écrire les données dans le fichier JSON
        file_put_contents('../json/model.json', $newJsonData);
    }
}

