<?php

class AjouterQuestionHandler {

    public function handleAjoutQuestion($postData) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($postData['ajouter_question'])) {
            // Récupérer les données du formulaire
            $uuid = $postData['uuid'];
            $type = $postData['type'];
            $label = $postData['label'];
            $choices = isset($postData['choices']) ? $postData['choices'] : null;
            $correct = isset($postData['correct']) ? $postData['correct'] : null;

            // Créer une nouvelle instance de Question
            $nouvelleQuestion = new Question($uuid, $type, $label, $choices, $correct);

            // Ajouter la nouvelle question au fichier JSON
            $this->ajouterQuestionAuJSON($nouvelleQuestion);
            
            return "Question ajoutée avec succès!";
        }

        return null;
    }

    private function ajouterQuestionAuJSON(Question $question) {
        // Charger le contenu existant du fichier JSON
        $jsonData = file_get_contents('data/model.json');
        $questionsData = json_decode($jsonData, true);

        // Ajouter la nouvelle question au tableau existant
        $questionsData[] = [
            'uuid' => $question->uuid,
            'type' => $question->type,
            'label' => $question->label,
            'choices' => $question->choices,
            'correct' => $question->correct,
        ];

        // Convertir le tableau mis à jour en JSON
        $updatedJsonData = json_encode($questionsData, JSON_PRETTY_PRINT);

        // Écrire le JSON mis à jour dans le fichier
        file_put_contents('data/model.json', $updatedJsonData);
    }
}
?>
