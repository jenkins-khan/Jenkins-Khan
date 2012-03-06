<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLESS is helper class to provide LESS compiling in symfony projects.
 *
 * @package    sfLESSPlugin
 * @subpackage lib
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */
class sfLESSConfig extends LESSConfig
{
  /**
   * @see LESSConfig
   */
  public function isCheckDates()
  {
    return sfConfig::get('app_sf_less_plugin_check_dates', parent::isCheckDates());
  }

  /**
   * @see LESSConfig
   */
  public function isUseCompression()
  {
    return sfConfig::get('app_sf_less_plugin_use_compression', parent::isUseCompression());
  }

  /**
   * @see LESSConfig
   */
  public function getCssPaths()
  {
    return sfLESSUtils::getSepFixedPath(sfConfig::get('sf_web_dir')) . '/css/';
  }

  /**
   * @see LESSConfig
   */
  public function getLessPaths()
  {
    return sfLESSUtils::getSepFixedPath(sfConfig::get('sf_web_dir')) . '/less/';
  }

  /**
   * @see LESSConfig
   */
  public function getLessJsPath()
  {
    return sfConfig::get('app_sf_less_plugin_js_lib', parent::getLessJsPath());
  }

  /**
   * @see LESSConfig
   */
  public function isClientSideCompilation()
  {
    return sfConfig::get('app_sf_less_plugin_use_js', parent::isClientSideCompilation());
  }

  /**
   * @see LESSConfig
   */
  public function isUseLessphp()
  {
    return sfConfig::get('app_sf_less_plugin_use_lessphp', parent::isClientSideCompilation());
  }

  /**
   * @see  LESSConfig
   */
  public function getFixDuplicate()
  {
    return sfConfig::get('app_sf_less_plugin_fix_duplicate', parent::getFixDuplicate());
  }
}
