<?php

class PromptsCapability
{

    /**
     * @var PromptInterface[]
     */
    private array $prompts = [];

    /**
     * @param PromptInterface $prompt
     * @return void
     */
    public function add(PromptInterface $prompt): void
    {
        $this->prompts[$prompt->getName()] = $prompt;
    }

    /**
     * @return array[]
     */
    public function list(): array
    {
        $list = [];

        foreach ($this->prompts as $prompt) {
            $list[] = [
                'name' => $prompt->getName(),
                'title' => $prompt->getTitle(),
                'description' => $prompt->getDescription(),
                'arguments' => $prompt->getArguments(),
            ];
        }

        return ['prompts' => $list];
    }

    /**
     * @param array $params
     * @return array
     */
    public function get(array $params): array
    {
        $name = $params['name'] ?? '';
        $args = $params['arguments'] ?? [];

        if (!isset($this->prompts[$name])) {
            return [
                'content' => [],
                'isError' => true,
            ];
        }

        try {
            $prompt = $this->prompts[$name];
            $code = $args['code'] ?? '';

            return [
                'description' => $prompt->getDescription(),
                'messages' => [
                    [
                        'role' => $prompt->getRole(),
                        'content' => [
                            'type' => 'text',
                            'text' => sprintf($prompt->getMessage(), $code),
                        ],
                    ],
                ],
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
