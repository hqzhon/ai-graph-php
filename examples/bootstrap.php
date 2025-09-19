<?php

use App\Examples\Container\Container;
use App\Examples\Routing\Router;
use App\Examples\Controller\DefaultController;
use App\Examples\Controller\ArticleController;
use App\Examples\Controller\ModelTestController;
use App\Examples\Controller\MultiAgentController;
use App\Examples\Controller\Streaming\StreamingModelTestController;
use App\Config\Config;
use App\Model\Factory\ModelFactory;
use App\Model\Config\ModelConfig;
use App\Agent\AgentFactory;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize the container
$container = new Container();

// --- CONFIGURATION ---
$container->singleton(Config::class, function() {
    $config = new Config();
    // Note: Core app config is still in the root config directory
    $config::load(__DIR__ . '/../config/app.php');
    return $config;
});

$container->singleton(ModelConfig::class, function() {
    // Note: Core model config is still in the root config directory
    $modelConfigFile = __DIR__ . '/../config/model.php';
    $configData = file_exists($modelConfigFile) ? require $modelConfigFile : [];
    return new ModelConfig($configData);
});

// --- FACTORIES ---
$container->singleton(ModelFactory::class);
$container->singleton(AgentFactory::class);

// --- CORE LIBRARY SERVICES ---
$container->singleton(App\Service\WorkflowService::class);
$container->singleton(App\Service\ArticleWorkflow::class);

// Note: View is a core service, but its templates are in the examples directory
$container->singleton(App\View\Template::class, function() {
    return new App\View\Template(__DIR__ . '/templates');
});

// --- EXAMPLE APP CONSOLE ---
$container->bind(App\Examples\Console\Command\HelloWorldCommand::class);
$container->bind(App\Examples\Console\Command\RunWorkflowCommand::class);
$container->bind(App\Examples\Console\Command\TestModelCommand::class);

// --- EXAMPLE APP CONTROLLERS ---
$container->bind(DefaultController::class);
$container->bind(ArticleController::class);
$container->bind(ModelTestController::class);
$container->bind(MultiAgentController::class);
$container->bind(StreamingModelTestController::class);


// --- EXAMPLE APP ROUTING ---
$container->singleton(Router::class, function(ContainerInterface $c) {
    $router = new Router($c);

    // Add routes
    $router->addRoute('GET', '/', [DefaultController::class, 'index']);
    $router->addRoute('GET', '/article-demo', [ArticleController::class, 'demo']);
    $router->addRoute('GET', '/article-demo/{id}', [ArticleController::class, 'demo']);
    $router->addRoute('GET', '/article-demo/transition/{id}/{transition}', [ArticleController::class, 'transition']);
    $router->addRoute('GET', '/model-test', [ModelTestController::class, 'showForm']);
    $router->addRoute('POST', '/model-test/process', [ModelTestController::class, 'processForm']);
    $router->addRoute('POST', '/streaming/model-test/stream', [StreamingModelTestController::class, 'streamModelResponse']);
    $router->addRoute('GET', '/workflow-lab', [MultiAgentController::class, 'lab']);
    $router->addRoute('POST', '/multi-agent/stream', [MultiAgentController::class, 'streamWorkflow']);

    return $router;
});

return $container;