<?php namespace Castiron\WebpackAssets\Viewhelpers;

/**
 * Class JsViewHelper
 * @package Castiron\WebpackAssets\Viewhelpers
 */
class JsViewHelper extends AbstractWebpackAssetsViewHelper {

    protected $fileType = 'js';

    /**
     * @param $files
     * @return string
     */
    protected function renderOutput($files) {
        return implode("\n", array_map(function ($file) {
            return '<script src="' . $file . '"></script>';
        }, $files));
    }
}
