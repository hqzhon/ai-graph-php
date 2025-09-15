<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .error { color: red; padding: 10px; background-color: #f2dede; border: 1px solid #ebccd1; border-radius: 4px; }
        .back-link { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="error">
            <h1>Transition Error!</h1>
            <p>Unable to apply the "<?php echo htmlspecialchars($transition); ?>" transition. This transition is not available from the current state "<?php echo htmlspecialchars($article->getStatus()); ?>".</p>
        </div>
        <p class="back-link"><a href="/">Back to Home</a></p>
    </div>
</body>
</html>