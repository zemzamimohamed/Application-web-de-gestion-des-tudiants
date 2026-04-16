<?php
declare(strict_types=1);

require_once 'connexion.php';

$pageTitle = 'Tableau de bord';
$currentPage = 'index';


$totalEtudiants = (int) $pdo->query('SELECT COUNT(*) FROM etudiant')->fetchColumn();

$repartitionStmt = $pdo->query(
    'SELECT classe, COUNT(*) AS nb
     FROM etudiant
     GROUP BY classe
     ORDER BY classe ASC'
);
$repartition = $repartitionStmt->fetchAll();
$totalClasses = count($repartition);


$dernierInscritStmt = $pdo->query(
    'SELECT *
     FROM etudiant
     ORDER BY date_inscription DESC, id DESC
     LIMIT 1'
);
$dernierInscrit = $dernierInscritStmt->fetch();

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="mb-1"><i class="fas fa-chart-line me-2 text-primary"></i>Tableau de bord</h1>
        <p class="text-muted mb-0">Vue d'ensemble rapide de la base des étudiants.</p>
    </div>
    <a href="ajouter.php" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i>Ajouter un étudiant
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-users me-2"></i>Total étudiants
            </div>
            <div class="card-body text-center">
                <div class="display-6 fw-bold text-primary"><?= $totalEtudiants ?></div>
                <p class="text-muted mb-0">Étudiants enregistrés dans la base.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-success text-white">
                <i class="fas fa-layer-group me-2"></i>Classes actives
            </div>
            <div class="card-body text-center">
                <div class="display-6 fw-bold text-success"><?= $totalClasses ?></div>
                <p class="text-muted mb-0">Classes contenant au moins un étudiant.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-info text-white">
                <i class="fas fa-user-clock me-2"></i>Dernier inscrit
            </div>
            <div class="card-body">
                <?php if ($dernierInscrit): ?>
                    <div class="fw-bold fs-5"><?= e($dernierInscrit['prenom']) ?> <?= e($dernierInscrit['nom']) ?></div>
                    <p class="mb-1 text-muted"><?= e($dernierInscrit['classe']) ?></p>
                    <small class="text-muted">Inscrit le <?= e((new DateTime($dernierInscrit['date_inscription']))->format('d/m/Y à H:i')) ?></small>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucun étudiant enregistré pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white">
        <i class="fas fa-chart-pie me-2"></i>Répartition par classe
    </div>
    <div class="card-body">
        <?php if ($repartition): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Classe</th>
                            <th>Effectif</th>
                            <th style="width: 45%;">Visualisation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($repartition as $ligne): ?>
                            <?php $pourcentage = $totalEtudiants > 0 ? (int) round(((int) $ligne['nb'] / $totalEtudiants) * 100) : 0; ?>
                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary-emphasis"><?= e($ligne['classe']) ?></span></td>
                                <td class="fw-semibold"><?= (int) $ligne['nb'] ?></td>
                                <td>
                                    <div class="progress" role="progressbar" aria-label="Répartition par classe" aria-valuenow="<?= $pourcentage ?>" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: <?= $pourcentage ?>%;">
                                            <?= $pourcentage ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary mb-0">Aucune donnée disponible pour afficher la répartition.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
