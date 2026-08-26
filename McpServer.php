<?php

/**
 * Class to manage requests and response for an MCP server
 */
class McpServer {

    /**
     * @var array ToolInterface[]
     */
    private array $tools = [];

    /**
     * @var array SecurityInterface[]
     */
    private array $security = [];

    public function __construct(...$security)
    {
        $this->security = $security;
    }

    /**
     * @param ToolInterface $tool
     * @return void
     */
    public function addTool(ToolInterface $tool): void
    {
        $this->tools[$tool->getName()] = $tool;
    }

    /**
     * @throws Exception
     */
    public function checkSecurity(): void
    {
        /** @var GuardInterface $security */
        foreach ($this->security as $security) {
            $security->check();
        }
    }

    /**
     * @param array $request
     * @return void
     */
    public function handleRequest(array $request): void
    {
        $id = $request['id'] ?? null;
        $method = $request['method'] ?? '';
        $params = $request['params'] ?? [];

        match ($method) {
            'initialize' => $this->handleInitialise($id),
            'tools/list' => $this->handleList($id),
            'tools/call' => $this->handleCall($id, $params),
            'notifications/initialized' => $this->handleInitialiseNotification(),
            default => $this->responseError($id, "Method not found: {$method}", 404)
        };
    }

    /**
     * @param int $id
     * @return void
     */
    private function handleInitialise(int $id): void
    {
        $this->responseResult($id, [
            'protocolVersion' => '2026-07-28',
            'capabilities' => ['tools' => new stdClass()],
            'serverInfo' => [
                'name' => 'mcp-http-server',
                'version' => '1.0.0',
            ],
        ]);
    }

    /**
     * @return void
     */
    private function handleInitialiseNotification(): void
    {
        $this->responseResult(null, []);
    }

    /**
     * @param int $id
     * @return void
     */
    private function handleList(int $id): void
    {
        $list = [];

        /**
         * @var string $name
         * @var ToolInterface $tool
         */
        foreach ($this->tools as $name => $tool) {
            $list[] = [
                'name' => $name,
                'description' => $tool->getDescription(),
                'inputSchema' => $tool->getInputSchema(),
            ];
        }
        $this->responseResult($id, ['tools' => $list]);
    }

    /**
     * @param int $id
     * @param array $params
     * @return void
     */
    private function handleCall(int $id, array $params): void
    {
        $name = $params['name'] ?? '';
        $args = $params['arguments'] ?? [];

        if (!isset($this->tools[$name])) {
            $this->responseError($id, "Unknown tool: {$name}", 404);
            return;
        }

        try {
            /** @var ToolInterface $tool */
            $tool = $this->tools[$name];
            $output = $tool->getHandler()($args);
            $this->responseResult($id, [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => (string)$output
                    ],
                ],
                'isError' => false,
            ]);
        } catch (Throwable $e) {
            $this->responseResult($id, [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Error: ' . $e->getMessage()
                    ],
                ],
                'isError' => true,
            ]);
        }
    }

    /**
     * @param int $httpCode
     * @param array $payload
     * @return void
     */
    private function jsonResponse(int $httpCode, array $payload): void
    {
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode($payload);
    }

    /**
     * @param int|null $id
     * @param $result
     * @return void
     */
    private function responseResult(?int $id, $result): void
    {
        $this->jsonResponse(200, [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => $result,
        ]);
    }

    /**
     * @param $id
     * @param string $message
     * @param int $httpCode
     * @return void
     */
    private function responseError($id, string $message, int $httpCode = 200): void
    {
        $this->jsonResponse($httpCode, [
            'jsonrpc' => '2.0',
            'id' => $id,
            'error' => [
                'message' => $message
            ],
        ]);
    }
}