<?php

interface CapabilityInterface
{
    /**
     * @param int $requestId
     * @return array
     */
    public function list(int $requestId): array;

    /**
     * @param int $requestId
     * @param array $params
     * @return array
     */
    public function run(int $requestId, array $params): array;
}
