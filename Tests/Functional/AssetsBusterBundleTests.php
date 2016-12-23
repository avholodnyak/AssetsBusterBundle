<?php

namespace AVHolodnyak\AssetsBusterBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use AVHolodnyak\AssetsBusterBundle\Tests\Functional\App\AppKernel;

/**
 * Functional tests for the bundle.
 *
 * @author Andrey Holodnyak <andrey.holodnyak@gmail.com>
 */
class AssetsBusterBundleTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AppKernel|null
     */
    private $kernel = null;

    /**
     * @param string $asset
     * @param string $expectedVersionalizedAsset
     *
     * @dataProvider DefaultVersionFormat_EngineAppliesVersionCorrectly_dataProvider
     */
    public function test_DefaultVersionFormat_TwigEngineAppliesVersionCorrectly($asset, $expectedVersionalizedAsset)
    {
        $templating = $this->createTemplatingWithDefaultVersionFormat();

        $versionalizedAsset = $this->getAssetVersionalizedByTwigEngine($asset, $templating);

        $this->assertEquals($expectedVersionalizedAsset, $versionalizedAsset);
    }

    /**
     * @param string $asset
     * @param string $expectedVersionalizedAsset
     *
     * @dataProvider CustomVersionFormat_EngineAppliesVersionCorrectly_dataProvider
     */
    public function test_CustomVersionFormat_TwigEngineAppliesVersionCorrectly($asset, $expectedVersionalizedAsset)
    {
        $templating = $this->createTemplatingWithCustomVersionFormat();

        $versionalizedAsset = $this->getAssetVersionalizedByTwigEngine($asset, $templating);

        $this->assertEquals($expectedVersionalizedAsset, $versionalizedAsset);
    }

    /**
     * @param string $asset
     * @param string $expectedVersionalizedAsset
     *
     * @dataProvider DefaultVersionFormat_EngineAppliesVersionCorrectly_dataProvider
     */
    public function test_DefaultVersionFormat_PHPEngineAppliesVersionCorrectly($asset, $expectedVersionalizedAsset)
    {
        $templating = $this->createTemplatingWithDefaultVersionFormat();

        $versionalizedAsset = $this->getVersionalizedAssetByPHPEngine($asset, $templating);

        $this->assertEquals($expectedVersionalizedAsset, $versionalizedAsset);
    }

    /**
     * @param string $asset
     * @param string $expectedVersionalizedAsset
     *
     * @dataProvider CustomVersionFormat_EngineAppliesVersionCorrectly_dataProvider
     */
    public function test_CustomVersionFormat_PHPEngineAppliesVersionCorrectly($asset, $expectedVersionalizedAsset)
    {
        $templating = $this->createTemplatingWithCustomVersionFormat();

        $versionalizedAsset = $this->getVersionalizedAssetByPHPEngine($asset, $templating);

        $this->assertEquals($expectedVersionalizedAsset, $versionalizedAsset);
    }

    public function DefaultVersionFormat_EngineAppliesVersionCorrectly_dataProvider()
    {
        return array(
            array(
                "js/test-js-asset.js",
                "/assets/js/test-js-asset.js?version-for-js-asset"
            ),
            array(
                "css/test-styles-asset.css",
                "/assets/css/test-styles-asset.css?version-for-styles-asset-2"
            ),
            array(
                "/img/folder/test-image-asset.jpg",
                "/assets/img/folder/test-image-asset.jpg?version-for-image-asset"
            ),
            array(
                "js/not-specified-asset.js",
                "/assets/js/not-specified-asset.js"
            ),
        );
    }

    public function CustomVersionFormat_EngineAppliesVersionCorrectly_dataProvider()
    {
        return array(
            array(
                "js/test-js-asset.js",
                "/assets/js/test-js-asset.js?custom-version-format=version-for-js-asset"
            ),
            array(
                "css/test-styles-asset.css",
                "/assets/css/test-styles-asset.css?custom-version-format=version-for-styles-asset-2"
            ),
            array(
                "/img/folder/test-image-asset.jpg",
                "/assets/img/folder/test-image-asset.jpg?custom-version-format=version-for-image-asset"
            ),
            array(
                "js/not-specified-asset.js",
                "/assets/js/not-specified-asset.js"
            ),
        );
    }

    /**
     * @param string $asset
     * @param EngineInterface $templating
     *
     * @return string Versionalized asset that was rendered in twig template using asset() function
     */
    private function getAssetVersionalizedByTwigEngine($asset, EngineInterface $templating)
    {
        return trim($templating->render("twig-asset.html.twig", array(
            "assetPath" => $asset
        )));
    }

    /**
     * @param string $asset
     * @param EngineInterface $templating
     *
     * @return string Versionalized asset that was rendered in php template using $view['assets']->getUrl() function
     */
    private function getVersionalizedAssetByPHPEngine($asset, EngineInterface $templating)
    {
        return trim($templating->render("php-asset.html.php", array(
            "assetPath" => $asset
        )));
    }

    /**
     * @return EngineInterface
     */
    private function createTemplatingWithDefaultVersionFormat()
    {
        /**
         * IMPORTANT! Use different kernel environments to boot different configurations.
         * If you use the same environments then symfony will not update configuration parameters.
         */
        $kernel = $this->kernel = new AppKernel("test_1", true);
        $kernel->boot();
        return $kernel->getContainer()->get("templating");
    }

    /**
     * @return EngineInterface
     */
    private function createTemplatingWithCustomVersionFormat()
    {
        /**
         * IMPORTANT! Use different kernel environments to boot different configurations.
         * If you use the same environments then symfony will not update configuration parameters.
         */
        $kernel = $this->kernel = new AppKernel("test_2", true);
        $kernel->useCustomVersionFormat();
        $kernel->boot();
        return $kernel->getContainer()->get("templating");
    }

    protected function tearDown()
    {
        $this->recursiveRemoveDirectory(__DIR__ . "/App/cache");
        $this->recursiveRemoveDirectory(__DIR__ . "/App/logs");
        if (null !== $this->kernel) {
            $this->kernel->shutdown();
        }
    }

    private function recursiveRemoveDirectory($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if(is_dir($file)) {
                $this->recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}
