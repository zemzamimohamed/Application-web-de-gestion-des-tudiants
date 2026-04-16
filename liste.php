<?php
declare(strict_types=1);

require_once 'connexion.php';

$pageTitle = 'Liste des étudiants';
$currentPage = 'liste';

$recherche = trim($_GET['recherche'] ?? '');
$classe = trim($_GET['classe'] ?? '');
$flash = getFlashMessage($_GET['msg'] ?? null);

// Construction dynamique et sécurisée de la requête de recherche.
$sql = 'SELECT * FROM etudiant WHERE 1=1';
$params = [];

if ($recherche !== '') {
    $sql .= ' AND (nom LIKE :recherche OR prenom LIKE :recherche OR email LIKE :recherche)';
    $params[':recherche'] = '%' . $recherche . '%';
}

if ($classe !== '') {
    $sql .= ' AND classe = :classe';
    $params[':classe'] = $classe;
}

$sql .= ' ORDER BY nom ASC, prenom ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$etudiants = $stmt->fetchAll();

$classes = $pdo->query('SELECT DISTINCT classe FROM etudiant ORDER BY classe ASC')->fetchAll(PDO::FETCH_COLUMN);

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="mb-1"><i class="fas fa-list me-2 text-primary"></i>Liste des étudiants</h1>
        <p class="text-muted mb-0">Recherche multicritère par nom, prénom, email et classe.</p>
    </div>
    <a href="ajouter.php" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i>Nouvel étudiant
    </a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
        <i class="fas fa-circle-info me-2"></i><?= e($flash['texte']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="recherche" class="form-label">Recherche globale</label>
                <input
                    type="text"
                    id="recherche"
                    name="recherche"
                    class="form-control"
                    placeholder="Nom, prénom ou email"
                    value="<?= e($recherche) ?>"
                >
            </div>
            <div class="col-md-4">
                <label for="classe" class="form-label">Classe</label>
                <select id="classe" name="classe" class="form-select">
                    <option value="">Toutes les classes</option>
                    <?php foreach ($classes as $classeOption): ?>
                        <option value="<?= e($classeOption) ?>" <?= $classe === $classeOption ? 'selected' : '' ?>>
                            <?= e($classeOption) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Rechercher
                </button>
                <a href="liste.php" class="btn btn-outline-secondary">Réinitialiser</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>Date de naissance</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($etudiants): ?>
                        <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td><?= (int) $etudiant['id'] ?></td>
                                <td><?= e($etudiant['nom']) ?></td>
                                <td><?= e($etudiant['prenom']) ?></td>
                                <td><?= e($etudiant['email']) ?></td>
                                <td><span class="badge bg-primary-subtle text-primary-emphasis"><?= e($etudiant['classe']) ?></span></td>
                                <td><?= $etudiant['date_naissance'] ? e((new DateTime($etudiant['date_naissance']))->format('d/m/Y')) : '<span class="text-muted">Non renseignée</span>' ?></td>
                                <td class="text-center text-nowrap">
                                    <a href="details.php?id=<?= (int) $etudiant['id'] ?>" class="btn btn-sm btn-info text-white" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="modifier.php?id=<?= (int) $etudiant['id'] ?>" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="supprimer.php" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cet étudiant ?');">
                                        <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Aucun étudiant ne correspond aux critères saisis.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
