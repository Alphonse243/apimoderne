<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Mon Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">Mon Blog</a>
            <div class="navbar-nav">
                <a class="nav-link" href="/posts">Articles</a>
                <a class="nav-link" href="/about">À propos</a>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer class="container mt-5">
        <hr>
        <p class="text-center">&copy; {{ date('Y') }} Mon Blog</p>
    </footer>
</body>
</html>
