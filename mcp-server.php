<?php

$autoload = ['', 'mcpserver', 'tools', 'resources', 'prompts', 'guards'];
spl_autoload_register(function($class) use ($autoload) {
    foreach ($autoload as $dir) {
        if (file_exists("{$dir}/{$class}.php")) {
            require_once "{$dir}/{$class}.php";
        }
    }
});

/**
 * @param array $message
 * @param int $code
 * @return void
 */
function response(array $message, int $code): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($message);
    exit;
}

/**
 * Start using MCP server
 */
$body = file_get_contents('php://input');
$request = json_decode($body, true);

$mcp = new McpServer(new MethodGuard(), new TokenGuard());

try {
    $mcp->checkSecurity();
} catch (GuardException|Exception $e) {
    response(['error' => $e->getMessage()], $e->getCode());
}


$mcp->addTool(new AdditionTool());
$mcp->addTool(new StringTool());
$mcp->addResource(new LogResource());
$mcp->addPrompt(new TestPrompt());

$response = $mcp->handleRequest($request);

response($response['content'], $response['status']);
