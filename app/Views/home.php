<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($_ENV['APP_NAME']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-house-door"></i> 
                <?= htmlspecialchars($_ENV['APP_NAME']) ?>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/articles">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categories">Catégories</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-light py-5 mb-5">
        <div class="container text-center">
            <h1 class="display-4">Bienvenue sur notre Blog</h1>
            <p class="lead">Découvrez nos derniers articles et actualités</p>
        </div>
    </div>

    <!-- Articles récents -->
    <div class="container mb-5">
        <h2 class="mb-4">Articles récents</h2>
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($post['image_path']): ?>
                            <img src="<?= htmlspecialchars($post['image_path']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($post['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                            <a href="/posts/<?= $post['id'] ?>" class="btn btn-primary">Lire la suite</a>
                        </div>
                        <div class="card-footer text-muted">
                            Publié le <?= (new DateTime($post['created_at']))->format('d/m/Y') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>À propos</h5>
                    <p>Un blog moderne pour partager des idées et des expériences.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Suivez-nous</h5>
                    <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($_ENV['APP_NAME']) ?>. Tous droits réservés.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
