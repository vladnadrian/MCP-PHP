<?php

class TokenGuard implements GuardInterface
{

    /**
     * @inheritDoc
     */
    public function check(): void
    {
        $token = $this->getBearerToken();
        $expectedToken = getenv('MCP_BEARER_TOKEN');

        if (!$token || !hash_equals($expectedToken, $token)) {
            throw new GuardException('Bad token');
        }
    }

    /**
     * @return string|null
     */
    private function getBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if ($header === '' && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $header = $headers['Authorization'] ?? '';
        }

        if (preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
