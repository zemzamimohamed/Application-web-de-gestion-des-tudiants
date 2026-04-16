<?php
declare(strict_types=1);

$pageTitle = $pageTitle ?? 'GestEtu';
$currentPage = $currentPage ?? '';

function navActive(string $page, string $currentPage): string
{
    return $page === $currentPage ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | GestEtu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .card {
            border-radius: 1rem;
        }
        .card-header {
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
            font-weight: 600;
        }
        .table td,
        .table th {
            vertical-align: middle;
        }
        .page-wrapper {
            min-height: calc(100vh - 160px);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-graduation-cap me-2"></i>GestEtu
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Basculer la navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= navActive('index', $currentPage) ?>" href="index.php">Tableau de bord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('liste', $currentPage) ?>" href="liste.php">Liste</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('ajouter', $currentPage) ?>" href="ajouter.php">Ajouter</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 page-wrapper">
