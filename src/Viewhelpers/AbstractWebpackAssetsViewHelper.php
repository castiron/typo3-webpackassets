<?php namespace Castiron\WebpackAssets\Viewhelpers;

use TYPO3\CMS\Core\Error\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class WebpackAssetsViewHelper
 * @package Castiron\WebpackAssets\Viewhelpers
 */
class AbstractWebpackAssetsViewHelper extends AbstractViewHelper {

    /**
     * Children must not be escaped, to be able to pass {bodytext} directly to it
     *
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * Plain HTML should be returned, no output escaping allowed
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Internal, used for subclassing
     * @var string
     */
    protected $fileType = '';

    public function initializeArguments() {
        $this->registerArgument('assetsFolder', 'string',
            'Example: "assets". The assets folder name where your resources are written by webpack', false, 'assets');
    }

    /**
     * @param string $manifestFilename
     * @param string $manifestClass
     * @return string
     * @throws Exception
     */
    public function render($manifestFilename = 'assets-manifest', $manifestClass = 'WebpackBuiltFiles') {
        if (!$this->fileType) {
            throw new Exception('File type could not be determined. You cannot use this abstract view helper directly.');
        }
        $files = $this->getFilesByType($this->fileType, $manifestFilename, $manifestClass);
        return $this->renderOutput($files);
    }

    /**
     * NOOP: Extend this
     *
     * @param $files
     * @return string
     */
    protected function renderOutput($files) {
        return '';
    }

    /**
     * @return string
     */
    protected function assetsFolder() {
        return static::trimEndSeparator($this->arguments['assetsFolder']);
    }

    /**
     * @return string
     */
    protected function publicFolder() {
        return PATH_site;
    }

    /**
     * @param $path
     * @return string
     */
    protected static function trimEndSeparator($path) {
        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $type can be 'css' or 'js' currently
     * @param string $manifestFilename
     * @param string $manifestClass
     * @return array
     * @throws Exception
     */
    protected function getFilesByType($type = '', $manifestFilename = 'assets-manifest', $manifestClass = 'WebpackBuiltFiles') {
        if (!$type) {
            return [];
        }

        $this->loadAssetsManifest($manifestFilename);

        /**
         * Bail if we couldn't load the class
         */
        if (!class_exists($manifestClass)) {
            throw new Exception('Could not load class ' . $manifestClass . ' from asset manifest file ' .
                $this->assetManifestPath($manifestFilename)
            );
        }

        $assetListVar = "${type}Files";
        return static::absolutizeUrls($manifestClass::$$assetListVar);
    }

    /**
     * @param array $urls
     * @return array
     */
    protected static function absolutizeUrls($urls = []) {
        return array_map(function ($url) {
            return DIRECTORY_SEPARATOR . ltrim($url, DIRECTORY_SEPARATOR);
        }, $urls);
    }

    /**
     * @param $manifestFilename
     * @return string
     */
    protected function assetManifestPath($manifestFilename) {
        $path = [
            $this->publicFolder(),
            $this->assetsFolder(),
            $manifestFilename . '.php',
        ];
        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @param $manifestFilename
     * @throws Exception
     */
    protected function loadAssetsManifest($manifestFilename) {
        $file = $this->assetManifestPath($manifestFilename);
        if (!is_file($file)) {
            throw new Exception('Could not load webpack-php manifest file ' . $file);
        }
        require_once($file);
    }
}
