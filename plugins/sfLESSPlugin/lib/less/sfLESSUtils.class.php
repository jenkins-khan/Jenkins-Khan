<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Various utility functions
 *
 * @package    sfLESSPlugin
 * @subpackage lib
 * @author     Victor Berchet <victor@suumit.com>
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */

class sfLESSUtils
{
  /**
   * Determine if a filesystem path is absolute.
   *
   * @param  path $path  A filesystem path.
   *
   * @return bool true, if the path is absolute, otherwise false.
   */
  public static function isPathAbsolute($path)
  {
    if ($path[0] == '/' || $path[0] == '\\' ||
        (strlen($path) > 3 && ctype_alpha($path[0]) &&
         $path[1] == ':' &&
         ($path[2] == '\\' || $path[2] == '/')
        )
       )
    {
      return true;
    }
    return false;
  }

  /**
   * Strip comments from less content
   * 
   * @param   string  $less LESS code
   * 
   * @return  string        LESS code without comments
   */
  public static function stripLessComments($less)
  {
    // strip /* */ style comments
    $less = preg_replace('#/\*.*?\*/#ms', '', $less);
    // stip // style comments
    $less = preg_replace('#//.*$#m', '', $less);
    return $less;
  }

  /**
   * Returns path with changed directory separators to unix-style (\ => /)
   *
   * @param   string  $path basic path
   * 
   * @return  string        unix-style path
   */
  public static function getSepFixedPath($path)
  {
    return str_replace(DIRECTORY_SEPARATOR, '/', $path);
  }

  /**
   * Returns relative path from the project root dir
   *
   * @param   string  $fullPath full path to file
   * 
   * @return  string            relative path from the project root
   */
  public static function getProjectRelativePath($fullPath)
  {
    return str_replace(
      self::getSepFixedPath(sfConfig::get('sf_root_dir')) . '/',
      '',
      self::getSepFixedPath($fullPath)
    );
  }

  /**
   * Checks if CSS file was compiled from LESS
   *
   * @param   string  $dir    a path to file
   * @param   string  $entry  a filename
   * 
   * @return  boolean
   */
  static public function isCssLessCompiled($dir, $entry)
  {
    $file = $dir . '/' . $entry;
    $fp = fopen( $file, 'r' );
    $line = stream_get_line($fp, 1024, "\n");
    fclose($fp);

    return (0 === strcmp($line, self::getCssHeader()));
  }

  /**
   * Compress CSS by removing whitespaces, tabs, newlines, etc.
   *
   * @param   string  $css  CSS to be compressed
   * 
   * @return  string        compressed CSS
   */
  static public function getCompressedCss($css)
  {
    return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
  }

  /**
   * Returns header text for CSS files
   *
   * @return  string  a header text for CSS files
   */
  static public function getCssHeader()
  {
    return '/* This CSS is autocompiled by LESS parser. Don\'t edit it manually. */';
  }

  /**
   * Create the file's folder when it does not exist
   *
   * @param   string  $file  The file absolute path
   */
  static public function createFolderIfNeeded($file)
  {
    // Checks if path exists & create if not
    if (!is_dir(dirname($file)))
    {
      mkdir(dirname($file), 0777, true);
      // PHP workaround to fix nested folders
      chmod(dirname($file), 0777);
    }
  }

  /**
   * Remove duplicate lines introduce by a flaw in the current less compiler.
   * The CSS format is assumed to be non compressed lessc output
   *
   * @see http://github.com/cloudhead/less.js/issues#issue/49
   *
   * @param   string  $css  CSS to be fixed
   *
   * @return  string        fixed CSS
   */
  static public function  fixDuplicateLines($css)
  {
    $directives = array();
    $inBlock = false;
    $inComment = false;
    $output = array();

    preg_match_all('/.+/', $css, $lines);
    foreach ($lines[0] as $line)
    {
      $active = '';
      if (!$inComment)
      {
        $inComment = preg_match("#/\*#", $line);
        $active = preg_replace("#/\*.*#", '', $line);
      }

      if ($inComment)
      {
        if (preg_match("#\*/#", $line))
        {
          $inComment = false;
          $active .= preg_replace("#.*?\*/#", '', $line);
        }        
      }

      if (!$inBlock)
      {
        $output[] = $line;

        if (preg_match('/{/', $active))
        {
          $inBlock = true;
          $directives = array();
        }
      }
      else
      {
        if ($inComment)
        {
          $output[] = $line;
        }
        elseif (!in_array($line, $directives))
        {
          $output[] = $line;
          $directives[] = $line;
        }

        if (preg_match('/}/', $active))
        {
          $inBlock = false;
        }
      }
    }
    return join("\n", $output);
  }
}
