<?php

class ResourcesCapability implements CapabilityInterface
{
    /**
     * @var ResourceInterface[]
     */
    private array $resources = [];

    /**
     * @param ResourceInterface $resource
     * @return void
     */
    public function add(ResourceInterface $resource): void
    {
        $this->resources[$resource->getUri()] = $resource;
    }

    /**
     * @inheritDoc
     */
    public function list(int $requestId): array
    {
        $list = [];

        foreach ($this->resources as $resource) {
            $list[] = [
                'url' => $resource->getUri(),
                'name' => $resource->getName(),
                'title' => $resource->getTitle(),
                'description' => $resource->getDescription(),
            ];
        }

        return ['resources' => $list];
    }

    /**
     * @inheritDoc
     */
    public function run(int $requestId, array $params): array
    {
        $uri = $params['uri'] ?? '';

        if (!isset($this->resources[$uri])) {
            return [
                'content' => [],
                'isError' => true,
            ];
        }

        try {
            $resource = $this->resources[$uri];
            return [
                'contents' => [
                    [
                        'uri' => $resource->getUri(),
                        'mimeType' => 'text/plain',
                        'text' => file_get_contents($resource->getName()),
                    ],
                ],
                'isError' => false,
            ];
        } catch (Throwable $e) {
            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Error: ' . $e->getMessage()
                    ],
                ],
                'isError' => true,
            ];
        }
    }
}
