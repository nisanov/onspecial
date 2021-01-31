<?php

declare(strict_types=1);

namespace App\Service\Services;

use Exception;

class Url extends \Phalcon\Url
{
    /**
     * The manifest information for static assert versioning.
     *
     * @var array
     */
    protected array $manifest;

    /**
     * Set the manifest file path.
     *
     * @param string $path
     * @return void
     * @throws Exception
     */
    public function setManifest(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception("The manifest file path was not found: [$path].");
        }

        $this->manifest = json_decode(file_get_contents($path), true);
    }

    /**
     * Generate a URL for a static resource.
     *
     * @param string $uri
     * @return string
     */
    public function getStatic($uri = null): string
    {
        $asset = basename($uri);

        return parent::getStatic(str_replace($asset, $this->manifest[$asset], $uri));
    }
}
