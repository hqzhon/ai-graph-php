<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; }
        button { background-color: #007cba; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        button:hover { background-color: #005a87; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Article</h1>
        <form method="POST" action="/article/create">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article->getTitle() ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($article->getContent() ?? ''); ?></textarea>
            </div>
            <button type="submit">Create Article</button>
        </form>
        <p><a href="/">Back to Home</a></p>
    </div>
</body>
</html>