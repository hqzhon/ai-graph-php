<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .status { padding: 5px 10px; border-radius: 3px; background-color: #f0f0f0; }
        .transitions { margin: 20px 0; }
        .transition-button { 
            display: inline-block; 
            margin-right: 10px; 
            margin-bottom: 10px;
            background-color: #007cba; 
            color: white; 
            padding: 8px 12px; 
            text-decoration: none; 
            border-radius: 3px;
        }
        .transition-button:hover { background-color: #005a87; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($article->getTitle()); ?></h1>
        <p><strong>Status:</strong> <span class="status"><?php echo htmlspecialchars($article->getStatus()); ?></span></p>
        <div>
            <h2>Content:</h2>
            <p><?php echo nl2br(htmlspecialchars($article->getContent())); ?></p>
        </div>
        
        <?php if (!empty($availableTransitions)): ?>
        <div class="transitions">
            <h3>Available Transitions:</h3>
            <?php foreach ($availableTransitions as $transition): ?>
                <a class="transition-button" href="/article/<?php echo htmlspecialchars($id ?? '1'); ?>/transition/<?php echo htmlspecialchars($transition); ?>">
                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $transition))); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p>No transitions available from this state.</p>
        <?php endif; ?>
        
        <p><a href="/">Back to Home</a></p>
    </div>
</body>
</html>