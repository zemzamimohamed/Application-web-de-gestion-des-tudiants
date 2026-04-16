<?php
declare(strict_types=1);

$host = 'localhost';
$dbname = 'ecole';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $dbname),
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    exit('Erreur de connexion à la base de données : ' . $e->getMessage());
}

/**
 * Échappe une valeur pour l'affichage HTML.
 */
function e(null|string|int|float $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}


function redirectTo(string $url): never
{
    header('Location: ' . $url);
    exit;
}


function getClassesDisponibles(): array
{
    return ['ILCS-1A', 'ILCS-1B', 'ILCS-2A', 'ILCS-2B', 'ILCS-3A'];
}

/**
 * Récupère un étudiant par son identifiant.
 */
function getStudentById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM etudiant WHERE id = :id');
    $stmt->execute([':id' => $id]);

    $etudiant = $stmt->fetch();

    return $etudiant ?: null;
}


function isValidDate(?string $date): bool
{
    if ($date === null || $date === '') {
        return true;
    }

    $objetDate = DateTime::createFromFormat('Y-m-d', $date);

    return $objetDate instanceof DateTime && $objetDate->format('Y-m-d') === $date;
}

function getFlashMessage(?string $code): ?array
{
    return match ($code) {
        'ajout_ok' => ['type' => 'success', 'texte' => 'L\'étudiant a été ajouté avec succès.'],
        'modification_ok' => ['type' => 'success', 'texte' => 'L\'étudiant a été modifié avec succès.'],
        'suppression_ok' => ['type' => 'success', 'texte' => 'L\'étudiant a été supprimé avec succès.'],
        'introuvable' => ['type' => 'warning', 'texte' => 'L\'étudiant demandé est introuvable.'],
        'id_invalide' => ['type' => 'warning', 'texte' => 'Identifiant invalide.'],
        default => null,
    };
}
