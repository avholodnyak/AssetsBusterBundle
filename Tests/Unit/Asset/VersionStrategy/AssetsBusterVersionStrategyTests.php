<?php

namespace AVHolodnyak\AssetsBusterBundle\Tests\Unit\Asset\VersionStrategy;

use AVHolodnyak\AssetsBusterBundle\Asset\VersionStrategy\AssetsBusterVersionStrategy;
use AVHolodnyak\AssetsBusterBundle\Asset\VersionStrategy\BustersFileLoaderInterface;

/**
 * Test case for the AssetsBusterVersionStrategy class.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
class AssetsBusterVersionStrategyTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $assetPath
     * @param string $expectedVersion
     *
     * @dataProvider getVersion_WithCorrectBustersFile_ReturnsCorrectVersion_dataProvider
     */
    public function test_getVersion_WithCorrectBustersFile_ReturnsCorrectVersion($assetPath, $expectedVersion)
    {
        $strategy = $this->createStrategyWithCorrectBustersFile();

        $assetVersion = $strategy->getVersion($assetPath);

        $this->assertEquals($expectedVersion, $assetVersion);
    }

    public function test_getVersion_WithCorrectBustersFile_ReturnsEmptyVersionForNonSpecifiedAsset()
    {
        $strategy = $this->createStrategyWithCorrectBustersFile();

        $assetVersion = $strategy->getVersion("dir/non-specified-asset.js");

        $this->assertEmpty($assetVersion);
    }

    public function test_getVersion_WithIncorrectBustersFile_ThrowsException()
    {
        $strategy = $this->createStrategyWithIncorrectBustersFile();

        $this->expectException(\Exception::class);
        $strategy->getVersion("js/test-script.js");
    }

    /**
     * @param string $format
     * @param string $asset
     * @param string $expectedVersionalizedAsset
     *
     * @dataProvider applyVersion_ForSpecifiedVersionFormat_ReturnsCorrectVersionalizedAsset_dataProvider
     */
    public function test_applyVersion_ForSpecifiedVersionFormat_ReturnsCorrectVersionalizedAsset($format, $asset, $expectedVersionalizedAsset)
    {
        $strategy = $this->createStrategyWithCorrectBustersFile($format);

        $versionalizedAsset = $strategy->applyVersion($asset);

        $this->assertEquals($expectedVersionalizedAsset, $versionalizedAsset);
    }

    public function test_applyVersion_WithCorrectBustersFile_DoesNotApplyVersionForNonSpecifiedAsset()
    {
        $strategy = $this->createStrategyWithCorrectBustersFile();

        $versionalizedAsset = $strategy->applyVersion("dir/non-specified-asset.js");

        $this->assertEquals("dir/non-specified-asset.js", $versionalizedAsset);
    }

    public function test_applyVersion_WithIncorrectBustersFile_ThrowsException()
    {
        $strategy = $this->createStrategyWithIncorrectBustersFile();

        $this->expectException(\Exception::class);
        $strategy->applyVersion("js/test-script.js");
    }

    /**
     * @param string $versionFormat Version format
     *
     * @return AssetsBusterVersionStrategy
     */
    private function createStrategyWithCorrectBustersFile($versionFormat = "%s?%s")
    {
        $loaderStub = $this->createMock(BustersFileLoaderInterface::class);
        $loaderStub->method("loadFile")->willReturn(array(
            "js/test-script.js" => "version-number-for-js-script",
            "css/test-styles.css" => "version-number-for-css-styles",
            "img/test-image.jpg" => "version-number-for-image",
            "/img/test-abs-asset.jpg" => "version-number-for-abs-asset",
        ));

        /** @var BustersFileLoaderInterface $loaderStub */
        $strategy = new AssetsBusterVersionStrategy("/random-path", $versionFormat, $loaderStub);
        return $strategy;
    }

    /**
     * @return AssetsBusterVersionStrategy
     */
    private function createStrategyWithIncorrectBustersFile()
    {
        $loaderStub = $this->createMock(BustersFileLoaderInterface::class);
        $loaderStub->method("loadFile")->willThrowException(new \Exception("Invalid busters file specified"));

        /** @var BustersFileLoaderInterface $loaderStub */
        $strategy = new AssetsBusterVersionStrategy("/random-path", "%s?%s", $loaderStub);
        return $strategy;
    }

    public function getVersion_WithCorrectBustersFile_ReturnsCorrectVersion_dataProvider()
    {
        return array(
            array("css/test-styles.css", "version-number-for-css-styles"),
            array("js/test-script.js", "version-number-for-js-script"),
            array("img/test-image.jpg", "version-number-for-image"),
        );
    }

    public function applyVersion_ForSpecifiedVersionFormat_ReturnsCorrectVersionalizedAsset_dataProvider()
    {
        return array(
            array(
                "%s?v=%s",
                "css/test-styles.css",
                "css/test-styles.css?v=version-number-for-css-styles",
            ),
            array(
                "%s?%s",
                "js/test-script.js",
                "js/test-script.js?version-number-for-js-script",
            ),
            array(
                "%s?%s",
                "/img/test-abs-asset.jpg",
                "/img/test-abs-asset.jpg?version-number-for-abs-asset",
            ),
        );
    }
}
