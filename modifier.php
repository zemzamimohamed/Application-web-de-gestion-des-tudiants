<?php
declare(strict_types=1);

require_once 'connexion.php';

$pageTitle = 'Modifier un étudiant';
$currentPage = 'liste';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id) {
    redirectTo('liste.php?msg=id_invalide');
}

$etudiant = getStudentById($pdo, $id);
if (!$etudiant) {
    redirectTo('liste.php?msg=introuvable');
}

$classes = getClassesDisponibles();
$erreurs = [];
$data = [
    'nom' => $etudiant['nom'],
    'prenom' => $etudiant['prenom'],
    'email' => $etudiant['email'],
    'classe' => $etudiant['classe'],
    'date_naissance' => $etudiant['date_naissance'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['nom'] = trim($_POST['nom'] ?? '');
    $data['prenom'] = trim($_POST['prenom'] ?? '');
    $data['email'] = trim($_POST['email'] ?? '');
    $data['classe'] = trim($_POST['classe'] ?? '');
    $data['date_naissance'] = trim($_POST['date_naissance'] ?? '');

    if ($data['nom'] === '') {
        $erreurs[] = 'Le nom est obligatoire.';
    }

    if ($data['prenom'] === '') {
        $erreurs[] = 'Le prénom est obligatoire.';
    }

    if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'Veuillez saisir une adresse email valide.';
    }

    if ($data['classe'] === '' || !in_array($data['classe'], $classes, true)) {
        $erreurs[] = 'Veuillez sélectionner une classe valide.';
    }

    if (!isValidDate($data['date_naissance'])) {
        $erreurs[] = 'La date de naissance doit être au format valide AAAA-MM-JJ.';
    }

    if (!$erreurs) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE etudiant
                 SET nom = :nom,
                     prenom = :prenom,
                     email = :email,
                     classe = :classe,
                     date_naissance = :date_naissance
                 WHERE id = :id'
            );

            $stmt->execute([
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':email' => $data['email'],
                ':classe' => $data['classe'],
                ':date_naissance' => $data['date_naissance'] !== '' ? $data['date_naissance'] : null,
                ':id' => $id,
            ]);

            redirectTo('liste.php?msg=modification_ok');
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erreurs[] = 'Cette adresse email existe déjà dans la base.';
            } else {
                $erreurs[] = 'Une erreur est survenue lors de la modification.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="mb-1"><i class="fas fa-user-pen me-2 text-primary"></i>Modifier un étudiant</h1>
        <p class="text-muted mb-0">Le formulaire est pré-rempli avec les données actuelles.</p>
    </div>
    <a href="details.php?id=<?= (int) $id ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour à la fiche
    </a>
</div>

<?php if ($erreurs): ?>
    <div class="alert alert-danger">
        <strong>Merci de corriger les points suivants :</strong>
        <ul class="mb-0 mt-2">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= e($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" novalidate>
            <input type="hidden" name="id" value="<?= (int) $id ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="<?= e($data['nom']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control" value="<?= e($data['prenom']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= e($data['email']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="classe" class="form-label">Classe</label>
                    <select id="classe" name="classe" class="form-select" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach ($classes as $classe): ?>
                            <option value="<?= e($classe) ?>" <?= $data['classe'] === $classe ? 'selected' : '' ?>>
                                <?= e($classe) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_naissance" class="form-label">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" class="form-control" value="<?= e($data['date_naissance']) ?>">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-floppy-disk me-2"></i>Mettre à jour
                </button>
                <a href="details.php?id=<?= (int) $id ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
