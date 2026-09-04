<?php

class TestPrompt implements PromptInterface
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'code_review';
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return 'Request Code Review';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Review the provided PHP code for correctness, bugs, security issues, and maintainability improvements.';
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array
    {
        return [
            [
                'name' => 'code',
                'description' => 'The PHP code to review',
                'required' => true,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRole(): string
    {
        return 'user';
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Review the following PHP code and point out any bugs, security risks, correctness issues, and opportunities for improvement.\n %s.';
    }
}
