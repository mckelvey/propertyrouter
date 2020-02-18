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
    public $schemaVersion = '1.0.1';

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
    }

    // Protected Methods
    // =========================================================================

}
