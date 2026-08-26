<?php

class PromptTool implements ToolInterface
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'prompt';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Echo back the uppercase provided text.';
    }

    /**
     * @inheritDoc
     */
    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'text' => ['type' => 'string'],
            ],
            'required' => ['text'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getHandler(): closure
    {
        return function (array $args): string {
            return strtoupper($args['text']);
        };
    }
}