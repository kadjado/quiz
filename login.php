
<?php
session_start();
require_once 'connectdb.php';
require_once 'validation.php';

if (isset($_SESSION['user_id'])) { 
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = validateInput($_POST['username']);
    $password = validateInput($_POST['password']);
    $captcha_input = $_POST['captcha'];

    if ($captcha_input !== $_SESSION['captcha']) {
        $error = "CAPTCHA incorrect. Veuillez réessayer.";
    } else {
        $stmt = $db->prepare("SELECT * FROM quiz_users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            background-image: url("../template/im4.png");
        }
        .login-form {
            background: none;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .login-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .login-form img {
            display: block;
            margin: 10px auto;
            border-radius: 5px;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-bottom: 10px;
            text-align: center;
        }
      #captcha{
        width: 30%;

      }
      #captch{
        text-align: center;
    }
    #password, #username{
        background: none;
    }
    .captcha-code{
        align-items: center;
        justify-content: center;
        display: flex;
    }
    @media (max-width: 768px) {
    .container {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        width: 100%;
        padding: 10px;
    }
    

    h1 {
        font-size: 1.2em;
    }

    .login-form {
        padding: 15px;
    }

    input, button {
        font-size: 0.9em;
    }
}
.container {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    </style>
</head>
<body>
    <div class="container">
    <form class="login-form" action="login.php" method="POST">
    <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <h2>Connexion</h2>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Mot de passe" required>

        <label for="captcha" id="captch">CAPTCHA</label>
        <img src="captcha.php" alt="CAPTCHA">
        <div class="captcha-code">
            <input type="text" id="captcha" name="captcha" placeholder="Entrez le code" required>
        </div>

        <button type="submit" name="sub">Se connecter</button>
        <a href="process_register.php">Créer un compte</a>
    </form>
    </div>
</body>
</html>
