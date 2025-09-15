<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { color: green; padding: 10px; background-color: #dff0d8; border: 1px solid #d6e9c6; border-radius: 4px; }
        .back-link { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success">
            <h1>Transition Success!</h1>
            <p>The article status has been successfully updated to "<?php echo htmlspecialchars($article->getStatus()); ?>" using the "<?php echo htmlspecialchars($transition); ?>" transition.</p>
        </div>
        <p class="back-link"><a href="/">Back to Home</a></p>
    </div>
</body>
</html>