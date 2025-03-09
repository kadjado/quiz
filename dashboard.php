
<?php    
    session_start();
    include_once('connectdb.php');
    include_once('config_session.php');
 // Vérifier si l'utilisateur est connecté 
 if (!isset($_SESSION['user_id'])) { 
    // Rediriger vers la page de connexion si non connecté
     header("Location: login.php");
      exit();
     } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
    background-image: url("template/im4.png");
}
@media (max-width: 768px) {
    .container {
        width: 100%;
        padding: 10px;
    }

    h1 {
        font-size: 1.2em;
    }

    .form {
        padding: 15px;
    }

    input, button {
        font-size: 0.9em;
    }
}
.tabs {
    display: flex;
    margin-bottom: 10px;
    border-bottom: 2px solid #ccc;
}
.tab {
    padding: 10px 20px;
    cursor: pointer;
    border: 1px solid #ccc;
    border-bottom: none;
    background: none;
    margin-right: 5px;
    transition: background 0.3s ease;
    color : white;
}
.tab:hover {
    background: #eaeaea;
}
.tab.active {
    background: none;
    border-bottom: 2px solid white;
    font-weight: bold;
}

/* Contenu des onglets */
.tab-content {
    display: none;
    padding: 15px;
    border: 1px solid #ccc;
    border-top: none;
    background: #fff;
}
.tab-content.active {
    display: block;
    padding: 50px 0px 80px 0px;
    background: none;
    border: none;
}
/* Barre de navigation */

.navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
        .question-container {
            width: 6cm;
            height: 3cm;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            line-height: 3cm;
            background-color: #fff;
            position: absolute;
            top: 75%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #333;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .texte {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
            color: inherit;
            text-align: center;
            font-size: inherit;
            cursor: not-allowed;
        }
        .question-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }

       
        .options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 100px 300px 0px 300px;
            
        }
        
        .question{
            margin: 0px 600px 0px 600px;
            height: 100px;
            justify-content: center;
            align-items: center;
            display: flex;
            background-color: orange;
            border: solid black 3px;
            border-radius: 8px;
            font-size: 20px;
            color: white;
        }
        .option-btn {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .option-btn.selected{
            background-color: blue;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="navbar">
        <h1>Tableau de bord</h1>
    
        <form method="POST" action="logout.php" style="margin: 0;">
            <button type="submit" class="logout-btn">Déconnexion</button>
        </form>
    </div>
    <h1>Bienvenue, Utilisateur</h1>
        <div class="tabs">
            <div class="tab active" onclick="showTab('question')">Questions</div>
            <div class="tab" onclick="showTab('challenge')">Challenge</div>
            <div class="tab" onclick="showTab('historique')">Historique</div>
            <div class="tab" onclick="showTab('profil')">Profil</div>
        </div>
        
        <div id="question" class="tab-content active">
            <div class="question" id="question-text">Chargement...</div>
            <div class="options" id="options-container"></div>
            <audio src="template/aud1.mp3" id="click-sound"></audio>
        </div>
        <div id="historique" class="tab-content">
            <h2>Historique</h2>
            <p>Vos réponses précédentes aux sondages.</p>
            <!-- Ajoutez ici l'historique des réponses -->
        </div>
        <div id="challenge" class="tab-content">
            <h2>Challenges</h2>
            <p>commencer un challenge</p>
            <!-- Ajoutez ici les données dynamiques du profil -->
        </div>
        <div id="profil" class="tab-content">
            <h2>Mon Profil</h2>
            <h1><?php echo $_SESSION['username']?></h1>
            <h3><?php echo $_SESSION['email']?></h3>
            <!-- Ajoutez ici les données dynamiques du profil -->
        </div>
    </div>

    <script>
        // Fonction pour afficher un onglet
        function showTab(tabId) {
            
            // Masquer tous les contenus 
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));

            // Désactiver tous les onglets
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));

            // Activer le contenu et l'onglet correspondant
            document.getElementById(tabId).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
    <script>
        let questions = [];
        let currentQuestionIndex = 0; // Index de la question actuelle
        let reponse = "";

        // Charger les questions depuis le serveur
        async function loadQuestions() {
            const response = await fetch('question.php');
            questions = await response.json();
            displayQuestion(); // Afficher la première question
            
        }

        // Afficher une question et ses options
        function displayQuestion() {
            
            if (currentQuestionIndex >= questions.length) {
                document.getElementById('question-text').innerText = "Merci d'avoir répondu à toutes les questions !";
                document.getElementById('options-container').innerHTML = ""; // Vider les options
                return;
            }

            const question = questions[currentQuestionIndex];
            document.getElementById('question-text').innerText = question.question;

            const optionsContainer = document.getElementById('options-container');
            optionsContainer.innerHTML = ""; // Vider les options précédentes
            // Afficher les options de réponse
            for (let i = 1; i <= 4; i++) {
                
                const optionText = question[`option${i}`];
                
                if (optionText) {
                    
                    const button = document.createElement('button');
                    button.className = 'option-btn';
                    button.name = 'opt';
                    button.innerText = optionText;
                    button.onclick = handleOptionClick;
                    if(question[`option${i}`] == question[`reponse`]){
                     button.id = 'response';
                     reponse = button;
                }
                    optionsContainer.appendChild(button);
                    
                }
            }
            
        }

        // Gérer le clic sur une option
        function handleOptionClick(event) {
    // Ajouter la classe "selected" au bouton cliqué
    const selectedButton = event.target;
    selectedButton.classList.add('selected');
    // Désactiver tous les boutons pour éviter de cliquer à nouveau
    const allButtons = document.querySelectorAll('.option-btn');
    allButtons.forEach(button => {
        button.disabled = true;
    });
    setTimeout(() => {
        reponse.style.background = "green";
        const sound = document.getElementById('click-sound');
        sound.currentTime = 0;
        sound.pause();
    }, 3000);
    // Passer à la question suivante après 3 secondes
    setTimeout(() => {
        currentQuestionIndex++; // Passer à la question suivante
        displayQuestion(); // Afficher la prochaine question
    }, 5000);
    
}

        // Charger les questions au chargement de la page
        window.onload = loadQuestions;
    </script>
</body>
</html> 