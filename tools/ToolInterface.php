<?php

interface ToolInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return array
     */
    public function getInputSchema(): array;

    /**
     * @return closure
     */
    public function getHandler(): closure;
}