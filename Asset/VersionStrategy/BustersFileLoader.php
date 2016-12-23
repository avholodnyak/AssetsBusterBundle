<?php

namespace AVHolodnyak\AssetsBusterBundle\Asset\VersionStrategy;

/**
 * Local filesystem implementation of BustersFileLoaderInterface.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
class BustersFileLoader implements BustersFileLoaderInterface
{
    /**
     * @inheritdoc
     */
    public function loadFile($path)
    {
        if (!is_file($path)) {
            throw new \Exception("File {$path} does not exist.");
        }

        $data = json_decode(
            file_get_contents($path),
            true
        );
        if (null === $data) {
            throw new \Exception("Can't read json data from file {$path}.");
        }

        return $data;
    }
}
