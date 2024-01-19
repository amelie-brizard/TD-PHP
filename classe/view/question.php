<?php

declare(strict_types=1);

require_once('../Request.php');

require_once('../AjouterQuestionHandler.php');
require_once('../data/DataLoader.php');

$dataLoader = new DataLoader('../json/model.json');
$questionsData = $dataLoader->getData();

// Utilisez la classe Request au lieu de $_POST
$request = new Request($_POST);

echo <<< EOL
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Question</title>
    <link rel='stylesheet' href='../../css/question.css'>
</head>
<body>
<header>
<nav> 
<ul>
<li><a href='../index.php'>Acceuil</a></li>
<li><a href='./question.php'>Ajouter des questions</a></li>
<li><a href='./quiz.php'>Quiz</a></li>
</ul>
</nav>
</header>
<main>
EOL;


if ($request->get('ajouter_question')) {
    $ajouterQuestionHandler = new AjouterQuestionHandler();
    $message = $ajouterQuestionHandler->handleAjoutQuestion($request);

    if ($message) {
        echo "<p>$message</p>";
    }
}

// Affichage du formulaire pour ajouter une nouvelle question
echo <<< EOL
<h2>Ajouter une nouvelle question</h2>
<form method='POST' action='question.php'>
<label for='uuid'>UUID:</label>
<input type='text' name='uuid' required><br>
<label for='type'>Type:</label>
<select name='type' id='questionType' required>
EOL;

// Options avec la vérification pour définir l'attribut 'selected'
$types = ['text', 'radio', 'checkbox'];
foreach ($types as $typeOption) {
    $selected = ($request->get('type') === $typeOption) ? 'selected' : '';
    echo "<option value='$typeOption' $selected>$typeOption</option>";
}

// Label et scripts JavaScript pour soumettre automatiquement le formulaire lors du changement de type
echo "<label for='label'>Label:</label>";
echo "<input type='text' name='label' required><br>";
echo <<<label
    <script>
        document.getElementById('questionType').addEventListener('change', function () {
            this.form.submit();
        });
    </script>
label;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type'])) {
    $type = $_POST['type'];

    if ($type == 'checkbox') {
        echo <<< EOL
    <button type='button' onclick='addCorrect()'>Ajouter une réponse correcte</button>
    <div id='correct-container'>
        <input type='text' name='correct[]' required><br>
    </div>
    <script>
    function addCorrect() {
        var container = document.getElementById('correct-container');
        
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'correct[]';
        input.required = true;
        container.appendChild(input);
    }
    </script>
    EOL;
    
        echo <<< EOL
    <label for='choices'>Choix:</label>
    <div id='choices-container'>
        <input type='text' name='choices[]' required><br>
    </div>
    <button type='button' onclick='addChoice()'>Ajouter un choix</button>
    <script>
    function addChoice() {
        var container = document.getElementById('choices-container');
        
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'choices[]';
        input.required = true;
        container.appendChild(input);
    }
    </script>
    EOL;
    }
    
    
    
    elseif ($type == 'radio') {
        echo <<< EOL
<label for='correct'>Bonne réponse:</label>
<input type='text' name='correct' required><br>
<label for='choices'>Choix:</label>
<div id='choices-container'>
<input type='text' name='choices[]' required><br>
</div>
<button type='button' onclick='addChoice()'>Ajouter un choix</button>
<script>
function addChoice() {
    var container = document.getElementById('choices-container');
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'choices[]';
    input.required = true;
    container.appendChild(input);
}
</script>
EOL;
    }
    else{
        echo "<label for='correct'>Bonne réponse:</label>";
    echo "<input type='text' name='correct' required><br>";
    }
}
else{
    echo "<label for='correct'>Bonne réponse:</label>";
    echo "<input type='text' name='correct' required><br>";
}

echo <<< EOL
<label for='score'>Score:</label>
<input type='number' name='score' required><br>
<input type='submit' name='ajouter_question' value='Ajouter la question'>
</form>

<!-- Afficher les données POST pour le débogage -->
<h3>Données POST :</h3>
<pre>
EOL;

echo <<< EQL
</main>
</body>
</html>
EQL;

print_r($_POST);
echo "</pre>";
?>
