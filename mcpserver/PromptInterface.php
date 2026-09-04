<?php

interface PromptInterface
{

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @return string
     */
    public function getRole(): string;

    /**
     * @return string
     */
    public function getMessage(): string;
}