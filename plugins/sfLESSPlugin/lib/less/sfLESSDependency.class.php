<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLESSDependency checks for less dependencies
 *
 * @package    sfLESSPlugin
 * @subpackage lib
 * @author     Victor Berchet <victor@suumit.com>
 * @version    1.0.0
 */
class sfLESSDependency
{
  /**
   * @var string $path The base path
   */
  protected $path;

  /**
   * @var boolean $check Wether to check for dependency
   */
  protected $check = false;

  /**
   * @param   string  $path   Base path (web root folder)
   * @param   boolean $check  Whether to check for dependency
   */
  public function __construct($path, $check)
  {
    if (!sfLESSUtils::isPathAbsolute($path) || !is_dir($path))
    {
      throw new InvalidArgumentException("An existing absolute folder must be provided");
    }
    else
    {
      $this->path = preg_replace('/\/$/', '', $path);
    }
    $this->check = $check;
  }

  /**
   * Return the modification time of the file (optionally including its dependency)
   *
   * @param   string          $file   Filename
   * 
   * @return  integer|boolean         The time the files was last modified (unix timestamp)
   */
  public function getMtime($lessFile)
  {
    $mtime = filemtime($lessFile);

    if ($mtime !== false && $this->check)
    {
      $deps = $this->computeDependencies($lessFile, array());
      foreach ($deps as $file)
      {
        if (is_file($file))
        {
          $mtime = max($mtime, filemtime($file));
        }
      }
    }

    return $mtime;
  }
 
  /**
   * Compute the dependencies of the file
   *
   * @param   file  $lessFile A less file
   * @param   array $deps     An array of pre-existing dependencies
   * 
   * @return  array           The updated array of dependencies
   */
  protected function computeDependencies($lessFile, array $deps)
  {
    if (!sfLESSUtils::isPathAbsolute($lessFile))
    {
      $lessFile = realpath($this->path . '/' . $lessFile);
    }

    if (is_file($lessFile))
    {
      $less = sfLESSUtils::stripLessComments(file_get_contents($lessFile));
      if (preg_match_all("/@import\s+(?:url\s*\(\s*)?(['\"])(.*?)\\1\s*(?:\))?\s*;/", $less, $files))
      {
        foreach ($files[2] as $file)
        {
          // Append the .less when the extension is omitted
          if (!preg_match('/\.(le?|c)ss$/', $file))
          {
            $file .= '.less';
          }
          // Compute the canonical path
          if (sfLESSUtils::isPathAbsolute($file))
          {
            $file = realpath($this->path . $file);
          }
          else
          {
            $file = realpath(dirname($lessFile) . '/' . $file);
          }
          if ($file !== false && !in_array($file, $deps) && !preg_match('/\.css$/', $file))
          {
            $deps[] = $file;
            // Recursively add dependencies
            $deps += $this->computeDependencies($file, $deps);
          }
        }
      }
      return $deps;
    }
    else
    {
      return array();
    }
  }
}
