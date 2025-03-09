<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <style>
        body {
           
            background-image: url("../template/im4.png");
          
        }
        .container{
            font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        }
        .register-form {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .register-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .register-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .register-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .register-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .register-form button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-bottom: 10px;
            text-align: center;
        }
        .link1{
            color : black;
            text-decoration: none;

        }
    </style>
</head>
<body>
<div id="error" class="error"></div>
   <div class="container">
   <form class="register-form" id="registerForm" action="process_register.php" method="POST">
        <h2>Créer un compte</h2>
        
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" placeholder="Votre nom d'utilisateur" required>
        
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" placeholder="Votre email" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
        
        <label for="confirmPassword">Confirmer le mot de passe :</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmez le mot de passe" required>
        
        <button type="submit">Créer un compte</button>
        <a href="login.php" class="link1">Se connecter</a>
    </form>

   </div>
    <script>
        // Validation côté client
        const registerForm = document.getElementById('registerForm');
        const errorDiv = document.getElementById('error');

        registerForm.addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirmPassword').value.trim();

            errorDiv.innerText = ''; // Réinitialiser les erreurs

            if (username === '' || email === '' || password === '' || confirmPassword === '') {
                e.preventDefault();
                errorDiv.innerText = 'Tous les champs sont obligatoires.';
                return;
            }

            // Validation de l'email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                errorDiv.innerText = 'Veuillez entrer une adresse email valide.';
                return;
            }

            // Vérification de la longueur du mot de passe
            if (password.length < 8) {
                e.preventDefault();
                errorDiv.innerText = 'Le mot de passe doit contenir au moins 8 caractères.';
                return;
            }

            // Vérification des mots de passe
            if (password !== confirmPassword) {
                e.preventDefault();
                errorDiv.innerText = 'Les mots de passe ne correspondent pas.';
            }
        });
    </script>
</body>
</html>
<?php
require_once 'connectdb.php';
require_once 'validation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = validateInput($_POST['username']);
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $confirmPassword = validateInput($_POST['confirmPassword']);
    $errors = [];

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Veuillez entrer une adresse e-mail valide.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
        try {
            $stmt = $db->prepare("SELECT username FROM quiz_users WHERE email = :email");
            $stmt->execute(['email' => $email]);

            if ($stmt->fetch()) {
                $errors[] = "Cette adresse e-mail est déjà utilisée.";
            } else {
                // Générer un code de vérification
                $verificationCode = rand(100000, 999999);

                // Hacher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Stocker les informations avec un statut non vérifié
                $stmt = $db->prepare("INSERT INTO quiz_users(username, email, password, verification_code, is_verified) VALUES (:username, :email, :password, :verification_code, 0)");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'verification_code' => $verificationCode
                ]);
                /*Envoyer l'email de vérification
                $subject = "Vérification de votre compte";
                $message = "Bonjour $username,\n\nVotre code de vérification est : $verificationCode\n\nMerci de l'entrer sur le site pour activer votre compte.";
                $headers = "From: kadjadopascal81@gmail.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "Un email de vérification a été envoyé à $email.";
                } else {
                    $errors[] = "Erreur lors de l'envoi de l'email de vérification.";
                }*/
              
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la création du compte : " . $e->getMessage();
        }
    }

    // Afficher les erreurs, le cas échéant
    foreach ($errors as $error) {
        echo "<p style='color: red;'>$error</p>";
    }
}
?>
