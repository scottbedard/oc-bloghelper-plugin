<?php namespace Bedard\BlogHelper;

use Event;
use System\Classes\PluginBase;

/**
 * BlogHelper Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Blog Helper',
            'description' => 'A tiny helper for RainLab.Blog markdown parsing.',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-plus'
        ];
    }

    public function boot()
    {
        // Inject CSS asset
        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            if (!$controller instanceof \RainLab\Blog\Controllers\Posts) return;
            $controller->addCss('/plugins/bedard/bloghelper/assets/css/style.css');
        });

        // Listen for markdown parsing
        Event::listen('markdown.beforeParse', function($data) {
            $data->text = preg_replace(
                '/\[youtube\](.{1,11})\[\/youtube\]/im',
                '<div class="video-wrapper"><iframe type="text/html" src="http://www.youtube.com/embed/$1" frameborder="0"/></iframe></div>',
                $data->text
            );
        });
    }

}
