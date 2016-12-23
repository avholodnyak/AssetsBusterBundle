<?php

namespace AVHolodnyak\AssetsBusterBundle\Tests\Unit\Asset\VersionStrategy;

use AVHolodnyak\AssetsBusterBundle\Asset\VersionStrategy\BustersFileLoader;

/**
 * Test case for the BustersFileLoader class.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
class BustersFileLoaderTests extends \PHPUnit_Framework_TestCase
{
    public function test_loadFile_WithCorrectBustersFile_ReturnsCorrectArray()
    {
        $loader = $this->createFileLoader();

        $loadedHashes = $loader->loadFile(__DIR__."/busters.json");

        $expectedHashes = [
            "js/test-js-asset.js" => "version-for-js-asset",
            "css/test-styles-asset.css" => "version-for-styles-asset-2",
            "/img/folder/test-image-asset.jpg" => "version-for-image-asset"
        ];
        $this->assertEquals($expectedHashes, $loadedHashes);
    }

    public function test_loadFile_WithIncorrectBustersFile_ThrowsException()
    {
        $loader = $this->createFileLoader();

        $this->expectException(\Exception::class);
        $loader->loadFile(__DIR__."/busters-incorrect.txt");
    }

    public function test_loadFile_WithUnexistingBustersFile_ThrowsException()
    {
        $loader = $this->createFileLoader();

        $this->expectException(\Exception::class);
        $loader->loadFile(__DIR__."/unexisting-busters-file.txt");
    }

    /**
     * @return BustersFileLoader
     */
    private function createFileLoader()
    {
        return new BustersFileLoader();
    }
}
