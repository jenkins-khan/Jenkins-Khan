<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLESSListeners is LESS listeners manager for symfony.
 *
 * @package    sfLESSPlugin
 * @subpackage lib
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */
class sfLESSListeners
{
  /**
   * Listens to the less_js.compile event.
   * Update the response by fixing less stylesheet path and adding the less js engine when required
   *
   * @param   sfEvent       $event    An sfEvent instance
   */
  static public function findAndFixContentLinks(sfEvent $event)
  {
    $response = $event->getSubject();
    $hasLess  = false;
    
    foreach ($response->getStylesheets() as $file => $options)
    {
      if (
           '.less' === substr($file, -5) && 
           (!isset($options['type']) || 'text/less' !== $options['type'])
         )
      {
        $response->removeStylesheet($file);
        if (sfLESS::getConfig()->isClientSideCompilation())
        {
          $response->addStylesheet(
            '/less/' . $file, '', array_merge($options, array('type' => 'text/less'))
          );
          $hasLess = true;
        }
        else
        {
          $response->addStylesheet('/css/' . substr($file, 0, -5) . '.css', '', $options);
        }
      }
    }

    if ($hasLess)
    {
      if (sfConfig::get('symfony.asset.javascripts_included', false))
      {
        throw new LogicException(
          "The stylesheets must be included before the javascript in your layout"
        );
      }
      else
      {        
        $response->addJavascript(sfLESS::getConfig()->getLessJsPath());
      }
    }
  }

  /**
   * Listens to the routing.load_configuration event. Finds & compiles LESS files to CSS
   *
   * @param   sfEvent $event  an sfEvent instance
   */
  static public function findAndCompile(sfEvent $event)
  {
    // Start compilation timer for debug info
    $timer = sfTimerManager::getTimer('Less compilation');

    // Create new helper object & compile LESS stylesheets with it
    $less = new sfLESS();
    foreach ($less->findLessFiles() as $lessFile)
    {
      $less->compile($lessFile);
    }

    // Stop timer
    $timer->addTime();
  }
}
