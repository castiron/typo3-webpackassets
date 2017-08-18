## Webpack Assets in October CMS

This library for TYPO3 works in tandem with the node package `php-manifest-webpack-plugin` to include CSS and JS
 in your site based on a PHP manifest file written to a public directory. This will allow you to use hashed file
 names in your built files, and let TYPO3 pick up the paths effortlessly.
 
### Installation

```
composer require castiron/typo3-webpackassets
```

### Quick start

This plugin provides a view helper to render the asset inclusion strings in your markup. 
 Use it like this to include the js/css in your template (e.g. in a partial, layout, etc.):
 
```html
{namespace wp=Castiron\WebpackAssets\Viewhelpers}

<!-- include css <link> tags: -->
<wp:css />

```

```html
{namespace wp=Castiron\WebpackAssets\Viewhelpers}

<!-- include js <script> tags: -->
<wp:js />

```

#### Component method parameters

For either of the above, you can specify the specific manifest file name, like:

```html
{{ webpackAssets.css('my-manifest-file-name', 'MyManifestClassName') }}
```

The parameter options are provided in case you had to generally control either the manifest file name used, 
 or the name of the PHP class written to that file, when configuring `php-manifest-webpack-plugin`. However, the 
 defaults here are the same as those in the node module for convenience.

### Component options

`assetsFolder` (default: "assets")

The path to the folder, relative to your web root, to which webpack is writing your assets. This
 corresponds to `output.path` from your webpack config.

```html
<wp:js assetsFolder="assets/my-theme/whatever" />
```
