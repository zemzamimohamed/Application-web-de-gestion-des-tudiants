<?php
declare(strict_types=1);

require_once 'connexion.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('liste.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirectTo('liste.php?msg=id_invalide');
}

$etudiant = getStudentById($pdo, $id);
if (!$etudiant) {
    redirectTo('liste.php?msg=introuvable');
}

$stmt = $pdo->prepare('DELETE FROM etudiant WHERE id = :id');
$stmt->execute([':id' => $id]);

redirectTo('liste.php?msg=suppression_ok');
