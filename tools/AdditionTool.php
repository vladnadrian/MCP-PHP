<?php

class AdditionTool implements ToolInterface
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'addition';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Add two numbers together.';
    }

    /**
     * @inheritDoc
     */
    public function getInputSchema(): array
    {
        return  [
            'type' => 'object',
            'properties' => [
                'a' => ['type' => 'number'],
                'b' => ['type' => 'number'],
            ],
            'required' => ['a', 'b'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getHandler(): closure
    {
        return function (array $args): string {
            return (string) ($args['a'] + $args['b']);
        };
    }
}
