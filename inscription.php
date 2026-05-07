<?php
require 'config/connexion_bdd.php';
$msg = ""; $type = ""; $erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['identifiant'];
    $mail = $_POST['email'];
    $mdp = $_POST['mot_de_passe'];

    if (strlen($mdp) < 12) $erreurs[] = "Au moins 12 caractères.";
    if (!preg_match('/[A-Z]/', $mdp)) $erreurs[] = "Une majuscule.";
    if (!preg_match('/[a-z]/', $mdp)) $erreurs[] = "Une minuscule.";
    if (!preg_match('/\d/', $mdp)) $erreurs[] = "Un chiffre.";
    if (!preg_match('/[\W_]/', $mdp)) $erreurs[] = "Un caractère spécial.";
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide.";

    if (empty($erreurs)) {
        try {
            $hash = password_hash($mdp, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (identifiant, email, mot_de_passe) VALUES (?, ?, ?)");
            $stmt->execute([$id, $mail, $hash]);
            $msg = "Inscription réussie !"; $type = "success";
        } catch (Exception $e) {
            $msg = "Identifiant ou email déjà pris."; $type = "error";
        }
    } else {
        $msg = "Le mot de passe doit contenir : " . implode(", ", $erreurs);
        $type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Simple</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <span class="dev-badge">Beaud Valentin</span>
        <h1>S'inscrire</h1>

        <?php if($msg): ?>
            <div class="alert alert-<?php echo $type; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="identifiant" placeholder="Identifiant" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit">Créer le compte</button>
        </form>
        
        <p><small>Sécurité : 12 car., Maj, Min, Chiffre, Symbole.</small></p>
        <p><a href="index.php">Retour</a></p>
    </div>
</body>
</html>