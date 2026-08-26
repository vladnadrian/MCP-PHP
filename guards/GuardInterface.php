<?php

interface GuardInterface
{
    /**
     * @throws Exception
     */
    public function check(): void;
}