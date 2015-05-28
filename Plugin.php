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

        // YouTube tags
        Event::listen('markdown.beforeParse', function($data) {

            // [youtube]...[/youtube]
            $data->text = preg_replace(
                '/\[youtube\](.{1,11})\[\/youtube\]/im',
                '<div class="video-wrapper"><iframe type="text/html" src="http://www.youtube.com/embed/$1" frameborder="0"/></iframe></div>',
                $data->text
            );

            // [img]...[/img]
            $data->text = preg_replace(
                '/\[img\]([\S\s]+?)\[\/img\]/im',
                '<img src="$1">',
                $data->text
            );

            // [half]...[/half]
            $data->text = preg_replace(
                '/\[half\]([\S\s]+?)\[\/half\]/im',
                '<div class="md-layout half">$1</div>',
                $data->text
            );

            // [third]...[/third]
            $data->text = preg_replace(
                '/\[third\]([\S\s]+?)\[\/third\]/im',
                '<div class="md-layout third">$1</div>',
                $data->text
            );

            // [fourth]...[/fourth] or [quarter]...[/quarter]
            $data->text = preg_replace(
                '/\[(fourth|quarter)\]([\S\s]+?)\[\/(fourth|quarter)\]/im',
                '<div class="md-layout fourth">$2</div>',
                $data->text
            );
        });
    }

}
