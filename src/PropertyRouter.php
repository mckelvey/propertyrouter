<?php
/**
 * Property Router plugin for Craft CMS 3.x
 *
 * Parses URL to provide for dynamic property searches.
 *
 * @link      https://sitesmade4people.co
 * @copyright Copyright (c) 2020 David McKelvey
 */

namespace mckelvey\propertyrouter;


use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\elements\Category;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;

use yii\base\Event;

/**
 * Class PropertyRouter
 *
 * @author    David McKelvey
 * @package   PropertyRouter
 * @since     1.0.1
 *
 */
class PropertyRouter extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var PropertyRouter
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.1.1';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'property-router',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $property_types = Category::find()
                    ->group('propertyTypes')
                    ->all();
                $types = array_map(
                    function($type) {
                        return $type->slug;
                    },
                    (array) $property_types
                );
                $types = strtolower(implode('|', $types));
                $term_pattern = '(for-sale|for-lease)+';
                $types_pattern = '((' . $types . ')+(,(' . $types . ')+)*)+';
                $search_pattern = '([-\w %"\*]){3,}';
                $template = ['template' => 'properties/index'];

                $event->rules['properties/<term:' . $term_pattern . '>/<types:' . $types_pattern . '>/search:<search:' . $search_pattern . '>'] = $template;
                $event->rules['properties/<term:' . $term_pattern . '>/<types:' . $types_pattern . '>'] = $template;
                $event->rules['properties/<term:' . $term_pattern . '>/search:<search:' . $search_pattern . '>'] = $template;
                $event->rules['properties/<types:' . $types_pattern . '>/search:<search:' . $search_pattern . '>'] = $template;
                $event->rules['properties/<term:' . $term_pattern . '>'] = $template;
                $event->rules['properties/<types:' . $types_pattern . '>'] = $template;
                $event->rules['properties/search:<search:' . $search_pattern . '>'] = $template;
            }
        );
    }

    // Protected Methods
    // =========================================================================

}
