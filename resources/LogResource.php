<?php

class LogResource implements ResourceInterface
{

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return 'file:///log/error.log';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'log/error.log';
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return 'Log errors';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Errors log of Apache server';
    }

    /**
     * @inheritDoc
     */
    public function getMimeType(): string
    {
        return 'text/plain';
    }
}