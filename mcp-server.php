<?php

spl_autoload_register(function($class) {
    if (file_exists("{$class}.php")) {
        require_once "{$class}.php";
    }
    if (file_exists("tools/{$class}.php")) {
        require_once "tools/{$class}.php";
    }
    if (file_exists("guards/{$class}.php")) {
        require_once "guards/{$class}.php";
    }
});

/**
 * @param string $message
 * @param int $code
 * @return void
 */
function response(string $message, int $code): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['error' => $message]);
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
    response($e->getMessage(), $e->getCode());
}


$mcp->addTool(new AdditionTool());
$mcp->addTool(new PromptTool());

$mcp->handleRequest($request);

