<?php

namespace AVHolodnyak\AssetsBusterBundle\Asset\VersionStrategy;

/**
 * Interface for busters file loader.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
interface BustersFileLoaderInterface
{
    /**
     * Reads the basters file and returns an array of hashes for each asset file.
     *
     * @param string $path Path to the basters file
     *
     * @return array Array of hashes for each asset file (hash => asset_file)
     *
     * @throws \Exception Throws an exception if specified busters file does not exist or doesn't contain valid json data
     */
    public function loadFile($path);
}
