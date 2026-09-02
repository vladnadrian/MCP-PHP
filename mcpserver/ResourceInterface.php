<?php

interface ResourceInterface
{
    /**
     * @return string
     */
    public function getUri(): string;

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
     * @return string
     */
    public function getMimeType(): string;
}