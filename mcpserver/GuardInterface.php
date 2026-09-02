<?php

interface GuardInterface
{
    /**
     * @throws GuardException
     */
    public function check(): void;
}