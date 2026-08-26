<?php

class MethodGuard implements GuardInterface
{

    /**
     * @inheritDoc
     */
    public function check(): void
    {
         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             throw new GuardException('Method not allowed');
         }
    }
}