<?php
declare(strict_types=1);

require_once 'connexion.php';

$pageTitle = 'Détails de l\'étudiant';
$currentPage = 'liste';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirectTo('liste.php?msg=id_invalide');
}

$etudiant = getStudentById($pdo, $id);
if (!$etudiant) {
    redirectTo('liste.php?msg=introuvable');
}

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="mb-1"><i class="fas fa-id-card me-2 text-primary"></i>Fiche étudiant</h1>
        <p class="text-muted mb-0">Consultation détaillée des informations enregistrées.</p>
    </div>
    <a href="liste.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-user-graduate me-2"></i><?= e($etudiant['prenom']) ?> <?= e($etudiant['nom']) ?>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Identifiant</div>
                    <div class="fw-semibold"><?= (int) $etudiant['id'] ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Classe</div>
                    <div class="fw-semibold"><?= e($etudiant['classe']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Email</div>
                    <div class="fw-semibold"><?= e($etudiant['email']) ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Date de naissance</div>
                    <div class="fw-semibold">
                        <?= $etudiant['date_naissance'] ? e((new DateTime($etudiant['date_naissance']))->format('d/m/Y')) : 'Non renseignée' ?>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Date d'inscription</div>
                    <div class="fw-semibold"><?= e((new DateTime($etudiant['date_inscription']))->format('d/m/Y à H:i')) ?></div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex flex-wrap gap-2">
            <a href="modifier.php?id=<?= (int) $etudiant['id'] ?>" class="btn btn-warning">
                <i class="fas fa-pen me-2"></i>Modifier
            </a>
            <form action="supprimer.php" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cet étudiant ?');">
                <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </form>
            <a href="liste.php" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
