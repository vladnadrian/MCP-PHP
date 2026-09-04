<?php

/**
 * Class to manage requests and response for an MCP server
 */
class McpServer {

    /**
     * @var array SecurityInterface[]
     */
    private array $security;

    /**
     * @var ToolsCapability
     */
    private ToolsCapability $tools;

    /**
     * @var ResourcesCapability
     */
    private ResourcesCapability $resources;

    /**
     * @var PromptsCapability
     */
    private PromptsCapability $prompts;

    /**
     * @param ...$security
     */
    public function __construct(...$security)
    {
        $this->security = $security;
        $this->tools = new ToolsCapability();
        $this->resources = new ResourcesCapability();
        $this->prompts = new PromptsCapability();
    }

    /**
     * @param ToolInterface $tool
     * @return void
     */
    public function addTool(ToolInterface $tool): void
    {
        $this->tools->add($tool);
    }

    /**
     * @param ResourceInterface $resource
     * @return void
     */
    public function addResource(ResourceInterface $resource): void
    {
        $this->resources->add($resource);
    }

    /**
     * @param PromptInterface $prompt
     * @return void
     */
    public function addPrompt(PromptInterface $prompt): void
    {
        $this->prompts->add($prompt);
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
     * @return array
     */
    public function handleRequest(array $request): array
    {
        $id = $request['id'] ?? null;
        $method = $request['method'] ?? '';
        $params = $request['params'] ?? [];

        $response = match ($method) {
            'initialize' => $this->handleInitialise(),
            'tools/list' => $this->tools->list(),
            'tools/call' => $this->tools->call($params),
            'resources/list' => $this->resources->list(),
            'resources/read' => $this->resources->read($params),
            'prompts/list' => $this->prompts->list(),
            'prompts/get' => $this->prompts->get($params),
            'notifications/initialized' => $this->handleInitialiseNotification(),
            default => ["Method not found: {$method}"]
        };

        return $this->responseResult($id, $response);
    }

    /**
     * @return array
     */
    private function handleInitialise(): array
    {
        return [
            'protocolVersion' => '2026-07-28',
            'capabilities' => [
                'tools' => new stdClass(),
                'resources' => [
                    'listChanged' => true,
                    'subscribe' => true,
                ],
                'prompts' => [
                    'listChanged' => true,
                ],
            ],
            'serverInfo' => [
                'name' => 'mcp-http-server',
                'version' => '1.0.0',
            ],
        ];
    }

    /**
     * @return array
     */
    private function handleInitialiseNotification(): array
    {
        return $this->responseResult(null, []);
    }

    /**
     * @param int|null $id
     * @param array $result
     * @param int $httpCode
     * @return array
     */
    private function responseResult(?int $id, array $result, int $httpCode = 200): array
    {
        return [
            'status' => $httpCode,
            'content' => [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => $result,
            ]
        ];
    }
}
