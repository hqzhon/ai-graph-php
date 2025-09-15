<!DOCTYPE html>
<html>
<head>
    <title>Welcome to PHP MVP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .links { margin-top: 30px; }
        .link { display: block; margin-bottom: 10px; padding: 10px; background-color: #007cba; color: white; text-decoration: none; border-radius: 3px; }
        .link:hover { background-color: #005a87; }
        .section { margin-bottom: 30px; }
        .section h2 { color: #007cba; border-bottom: 1px solid #eee; padding-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the PHP MVP!</h1>
        <p>This is a minimal viable product (MVP) implementation of a PHP application with Workflow engine integration.</p>
        
        <div class="section">
            <h2>Features</h2>
            <ul>
                <li>Basic MVC structure with routing</li>
                <li>Template engine for rendering views</li>
                <li>Integrated Symfony Workflow component</li>
                <li>Article management with state transitions</li>
                <li>Service container for dependency management</li>
            </ul>
        </div>
        
        <div class="section">
            <h2>Demo</h2>
            <p>Try out the article workflow demo:</p>
            <a class="link" href="/article/1">View Article 1 (Draft)</a>
            <a class="link" href="/article/2">View Article 2 (Review)</a>
            <a class="link" href="/article/3">View Article 3 (Published)</a>
            <a class="link" href="/article/4">View Article 4 (Rejected)</a>
        </div>
        
        <div class="section">
            <h2>Console Commands</h2>
            <p>You can also run console commands:</p>
            <pre>php bin/console hello</pre>
            <pre>php bin/test-workflow.php</pre>
        </div>
    </div>
</body>
</html>