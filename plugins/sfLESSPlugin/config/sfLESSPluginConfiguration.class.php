<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLESSPluginConfiguration configures application to use LESS compiler.
 *
 * @package    sfLESSPlugin
 * @subpackage configuration
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */
class sfLESSPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // If sf_less_plugin_compile in app.yml is set to true
    if (sfConfig::get('app_sf_less_plugin_compile', false))
    {
      // Register listener to routing.load_configuration event
      $this->dispatcher->connect(
        'context.load_factories',
        array('sfLESSListeners', 'findAndCompile')
      );

      // If app_sf_less_plugin_toolbar in app.yml is set to true (by default)
      if (sfConfig::get('sf_web_debug') && sfConfig::get('app_sf_less_plugin_toolbar', true))
      {
        // Add LESS toolbar to Web Debug toolbar
        $this->dispatcher->connect('debug.web.load_panels', array(
          'sfWebDebugPanelLESS',
          'listenToLoadDebugWebPanelEvent'
        ));
      }
    }

    $this->dispatcher->connect(
      'less_js.compile',
      array('sfLESSListeners', 'findAndFixContentLinks')
    );
  }
}
