<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'AuthBoard' ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>StatusBoard</h1>
        <?php if (!empty($_SESSION['user'])): ?>
            <nav>
                <a href="/dashboard">Dashboard</a>
                <span class="separator">|</span>
                <a href="/posts">Posts</a>
                <span class="separator">|</span>
                <?php 
                $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                if ($current_path !== '/posts' && $current_path !== '/posts/create'):
                ?>
                    <a href="/posts/create">Create Post</a>
                    <span class="separator">|</span>
                <?php endif; ?>
                <a class="logout-Nav" href="/logout">Logout</a>
            </nav>
        <?php endif; ?>
    </header>

    <main>
        <?php echo $content; ?>
    </main>

    <?php 
        $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($current_path == '/dashboard'):
        ?>
        <footer>
            <small>StatusBoard</small>
        </footer>
            
        <?php endif; ?>
</div>
</body>
</html>
