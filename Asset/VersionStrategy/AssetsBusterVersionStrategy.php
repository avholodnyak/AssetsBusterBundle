<?php

namespace AVHolodnyak\AssetsBusterBundle\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Assets buster implementation for asset version strategy.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
class AssetsBusterVersionStrategy implements VersionStrategyInterface
{
    /**
     * @var string Path to the basters file
     */
    private $bustersPath;

    /**
     * @var string Version format
     */
    private $versionFormat;

    /**
     * @var BustersFileLoaderInterface $bustersLoader Busters file loader
     */
    private $bustersLoader;

    /**
     * @var array Hashes of asset files
     */
    private $hashes;

    /**
     * AssetsBusterVersionStrategy constructor.
     *
     * @param string $bustersPath Path to the basters file
     * @param string $versionFormat Version format (sprintf-compatible format string)
     * @param BustersFileLoaderInterface $bustersLoader Busters file loader
     */
    public function __construct($bustersPath, $versionFormat, BustersFileLoaderInterface $bustersLoader)
    {
        $this->bustersPath = $bustersPath;
        $this->versionFormat = $versionFormat;
        $this->bustersLoader = $bustersLoader;
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception Throws an exception if specified busters file does not exist or doesn't contain valid json data
     */
    public function getVersion($path)
    {
        if (!is_array($this->hashes)) {
            $this->hashes = $this->bustersLoader->loadFile($this->bustersPath);
        }

        return isset($this->hashes[$path]) ? $this->hashes[$path] : "";
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception Throws an exception if specified busters file does not exist or doesn't contain valid json data
     */
    public function applyVersion($path)
    {
        $version = $this->getVersion($path);

        if ("" === $version) {
            return $path;
        }

        $versionized = sprintf(
            $this->versionFormat,
            ltrim($path, "/"),
            $version
        );

        if ($path && "/" === $path[0]) {
            return "/" . $versionized;
        }

        return $versionized;
    }
}
