<?php
session_start();
require 'config/connexion_bdd.php';
$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['identifiant'];
    $mdp = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM historique_connexions WHERE identifiant = ? AND connexion_reussie = 0 AND date_heure > NOW() - INTERVAL 1 DAY");
    $stmt->execute([$id]);
    
    if ($stmt->fetchColumn() >= 3) {
        $pdo->prepare("UPDATE utilisateurs SET profil_bloque = 1 WHERE identifiant = ?")->execute([$id]);
        $erreur = "Compte bloqué suite à 3 échecs en 24h.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE identifiant = ?");
        $stmt->execute([$id]);
        $u = $stmt->fetch();

        if ($u && password_verify($mdp, $u['mot_de_passe']) && !$u['profil_bloque']) {
            $pdo->prepare("INSERT INTO historique_connexions (identifiant, connexion_reussie) VALUES (?, 1)")->execute([$id]);
            $_SESSION['user'] = $u['identifiant'];
            header('Location: page_protegee.php');
            exit();
        } else {
            $pdo->prepare("INSERT INTO historique_connexions (identifiant, connexion_reussie) VALUES (?, 0)")->execute([$id]);
            $erreur = "Identifiants incorrects ou compte bloqué.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container">
        <span class="dev-badge">Beaud Valentin</span>
        <h1>Connexion</h1>
        <?php if($erreur) echo "<div class='alert alert-error'>$erreur</div>"; ?>
        <form method="POST">
            <input type="text" name="identifiant" placeholder="Identifiant" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p><a href="inscription.php">Créer un compte</a></p>
    </div>
</body>
</html>