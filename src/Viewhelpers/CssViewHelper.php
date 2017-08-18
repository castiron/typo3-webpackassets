<?php namespace Castiron\WebpackAssets\Viewhelpers;

/**
 * Class CssViewHelper
 * @package Castiron\WebpackAssets\Viewhelpers
 */
class CssViewHelper extends AbstractWebpackAssetsViewHelper {

    /**
     * @var \TYPO3\CMS\Core\Page\PageRenderer
     * @inject
     */
    var $pageRenderer;

    protected $fileType = 'css';

    /**
     * Add the CSS via the TSFE and just return a blank string for rendering in-place
     *
     * @param $files
     * @return string
     */
    protected function renderOutput($files) {
        foreach ($files as $file) {
            $this->pageRenderer->addHeaderData(static::renderLinkTag($file));
        }
        return '';
    }

    /**
     * @param $file
     * @return string
     */
    protected static function renderLinkTag($file) {
        return '<link rel="stylesheet" type="text/css" href="' . $file . '" media="all">';
    }
}
