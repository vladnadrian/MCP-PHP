<?php

class ResourcesCapability
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
     * @return array[]
     */
    public function list(): array
    {
        $list = [];

        foreach ($this->resources as $resource) {
            $list[] = [
                'uri' => $resource->getUri(),
                'name' => $resource->getName(),
                'title' => $resource->getTitle(),
                'description' => $resource->getDescription(),
                'mimeType' => $resource->getMimeType(),
            ];
        }

        return ['resources' => $list];
    }

    /**
     * @param array $params
     * @return array
     */
    public function read(array $params): array
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
