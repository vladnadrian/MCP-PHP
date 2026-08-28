<?php

class ToolsCapability implements CapabilityInterface
{

    /**
     * @var ToolInterface[]
     */
    private array $tools = [];

    /**
     * @param ToolInterface $tool
     * @return void
     */
    public function add(ToolInterface $tool): void
    {
        $this->tools[$tool->getName()] = $tool;
    }

    /**
     * @inheritDoc
     */
    public function list(int $requestId): array
    {
        $list = [];

        foreach ($this->tools as $tool) {
            $list[] = [
                'name' => $tool->getName(),
                'description' => $tool->getDescription(),
                'inputSchema' => $tool->getInputSchema(),
            ];
        }

        return ['tools' => $list];
    }

    /**
     * @inheritDoc
     */
    public function run(int $requestId, array $params): array
    {
        $name = $params['name'] ?? '';
        $args = $params['arguments'] ?? [];

        if (!isset($this->tools[$name])) {
            return [
                'content' => [],
                'isError' => true,
            ];
        }

        try {
            $tool = $this->tools[$name];
            $output = $tool->getHandler()($args);
            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => (string)$output
                    ],
                ],
                'isError' => false,
            ];
        } catch (Throwable $e) {
            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Error: ' . $e->getMessage()
                    ],
                ],
                'isError' => true,
            ];
        }
    }
}
