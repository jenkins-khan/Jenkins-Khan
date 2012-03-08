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
class sfLESS
{
  /**
   * Array of LESS styles
   *
   * @var array
   */
  protected static $results = array();

  /**
   * Errors of compiler
   *
   * @var array
   */
  protected static $errors  = array();

  /**
   * Current LESS file to be parsed. This var used to help output errors in callCompiler()
   *
   * @var string
   */
  protected $currentFile;

  /**
   * LESS configuration manager
   *
   * @var LESSConfig
   */
  protected static $config;

  /**
   * Constructor
   *
   * @param   LESSConfig  $config   configuration manager
   */
  public function __construct(LESSConfig $config = null)
  {
    if ($config)
    {
      self::$config = $config;
    }
  }

  /**
   * Returns configuration manager
   *
   * @return  LESSConfig  configurator instance
   */
  public static function getConfig()
  {
    self::$config = self::$config?self::$config:new sfLESSConfig();
    return self::$config;
  }

  /**
   * Returns array of compiled styles info
   *
   * @return  array
   */
  public static function getCompileResults()
  {
    return self::$results;
  }

  /**
   * Returns array of compiled styles errors
   *
   * @return  array
   */
  public static function getCompileErrors()
  {
    return self::$errors;
  }

  /**
   * Returns all CSS files under the CSS directory
   *
   * @return  array   an array of CSS files
   */
  public function findCssFiles()
  {
    return sfFinder::type('file')
      ->exec(array('sfLESSUtils', 'isCssLessCompiled'))
      ->name('*.css')
      ->in(self::getConfig()->getCssPaths());
  }

  /**
   * Returns all LESS files under the LESS directories
   *
   * @return  array   an array of LESS files
   */
  public function findLessFiles()
  {
    return sfFinder::type('file')
      ->name('*.less')
      ->discard('_*')
      ->follow_link()
      ->in(self::getConfig()->getLessPaths());
  }

  /**
   * Returns CSS file path by its LESS alternative
   *
   * @param   string  $lessFile LESS file path
   *
   * @return  string            CSS file path
   */
  public function getCssPathOfLess($lessFile)
  {
    $file = preg_replace('/\.less$/', '.css', $lessFile);
    $file = preg_replace(sprintf('/^%s/', preg_quote(self::getConfig()->getLessPaths(), '/')), self::getConfig()->getCssPaths(), $file);
    return $file;
  }

  /**
   * Compiles LESS file to CSS
   *
   * @param   string  $lessFile a LESS file
   *
   * @return  boolean           true if succesfully compiled & false in other way
   */
  public function compile($lessFile)
  {
    // Creates timer
    $timer = new sfTimer;

    // Gets CSS file path
    $cssFile = $this->getCssPathOfLess($lessFile);
    sfLESSUtils::createFolderIfNeeded($cssFile);

    // Is file compiled
    $isCompiled = false;

    // If we check dates - recompile only really old CSS
    if (self::getConfig()->isCheckDates())
    {
      try
      {
        $d = new sfLESSDependency(sfConfig::get('sf_web_dir'),
          sfConfig::get('app_sf_less_plugin_check_dependencies', false));
        $shouldCompile = !is_file($cssFile) || $d->getMtime($lessFile) > filemtime($cssFile);
      }
      catch (Exception $e)
      {
        $shouldCompile = false;
      }
    }
    else
    {
      $shouldCompile = true;
    }

    if ($shouldCompile)
    {
      $buffer = $this->callLesscCompiler($lessFile, $cssFile);
      if ($buffer !== false)
      {
        $isCompiled = $this->writeCssFile($cssFile, $buffer) !== false;
      }
    }

    // Adds debug info to debug array
    self::$results[] = array(
      'lessFile'   => $lessFile,
      'cssFile'    => $cssFile,
      'compTime'   => $timer->getElapsedTime(),
      'isCompiled' => $isCompiled
    );

    return $isCompiled;
  }

  /**
   * Write the CSS content to a file
   *
   * @param   string  $cssFile  a CSS file
   * @param   string  $buffer   the css content
   *
   * @return  boolean           true if succesfully written & false in other way
   */
  public function writeCssFile($cssFile, $buffer)
  {
    // Fix duplicate lines
    if (self::getConfig()->getFixDuplicate())
    {
      $buffer = sfLESSUtils::fixDuplicateLines($buffer);
    }

    // Compress CSS if we use compression
    if (self::getConfig()->isUseCompression())
    {
      $buffer = sfLESSUtils::getCompressedCss($buffer);
    }

    // Add compiler header to CSS & writes it to file
    $status = file_put_contents($cssFile, sfLESSUtils::getCssHeader() . "\n\n" . $buffer);

    if ('\\' != DIRECTORY_SEPARATOR && $status !== false && fileowner($cssFile) == posix_geteuid())
    {
      // Only attempt to chmod files we own
      chmod($cssFile, 0666);
    }

    return $status;
  }

  /**
   * Calls lessc compiler for LESS file
   *
   * @param   string  $lessFile a LESS file
   * @param   string  $cssFile  a CSS file
   *
   * @return  string            output
   */
  public function callLesscCompiler($lessFile, $cssFile)
  {
    // Setting current file. We will output this var if compiler throws error
    $this->currentFile = $lessFile;
		
    if (self::getConfig()->isUseLessphp())
    {
    	require_once(sfConfig::get('sf_lib_dir').'/vendor/lessphp/lessc.inc.php');
			$less = new lessc($lessFile);
			file_put_contents($cssFile, $less->parse());
    }
    else
    {
	    // Compile with lessc
	    $fs = new sfFilesystem;
	    $command = sprintf('lessc "%s" "%s"', $lessFile, $cssFile);
	
	    if ('1.3.0' <= SYMFONY_VERSION)
	    {
	      try
	      {
	        $fs->execute($command, null, array($this, 'throwCompilerError'));
	      }
	      catch (RuntimeException $e)
	      {
	        return false;
	      }
	    }
	    else
	    {
	      $fs->sh($command);
	    }
    }

    // Setting current file to null
    $this->currentFile = null;
    
    return file_get_contents($cssFile);
  }
  
  /**
   * Returns true if compiler can throw RuntimeException
   *
   * @return boolean
   */
  public function canThrowExceptions()
  {
    return (('prod' !== sfConfig::get('sf_environment') || !sfConfig::get('sf_app')) &&
        !(sfConfig::get('sf_web_debug') && sfConfig::get('app_sf_less_plugin_toolbar', true))
    );
  }

  /**
   * Throws formatted compiler error
   *
   * @param   string  $line error line
   *
   * @return  boolean
   */
  public function throwCompilerError($line)
  {
    // Generate error description
    $errorDescription = sprintf("LESS parser error in \"%s\":\n\n%s", $this->currentFile, $line);

    // Adds error description to list of errors
    self::$errors[$this->currentFile] = $errorDescription;

    // Throw exception if allowed & log error otherwise
    if ($this->canThrowExceptions())
    {
      throw new sfException($errorDescription);
    }
    else
    {
      sfContext::getInstance()->getLogger()->err($errorDescription);
    }

    return false;
  }
}
