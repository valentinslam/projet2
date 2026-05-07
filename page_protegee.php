<?php
session_start();
require 'config/connexion_bdd.php';
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit(); }

$id_user = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT date_heure, connexion_reussie FROM historique_connexions WHERE identifiant = ? ORDER BY date_heure DESC");
$stmt->execute([$id_user]);
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Membre</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Bonjour, <?= htmlspecialchars($id_user) ?></h1>
        <a href="deconnexion.php" style="color:red; font-weight:bold;">Se déconnecter</a>
        <h3>Historique des connexions</h3>
        <table>
            <thead>
                <tr><th>Date et Heure</th><th>État</th></tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $l): ?>
                <tr>
                    <td><?= $l['date_heure'] ?></td>
                    <td style="color: <?= $l['connexion_reussie'] ? 'green' : 'red' ?>;">
                        <?= $l['connexion_reussie'] ? 'Succès' : 'Échec' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>