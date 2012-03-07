<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebDebugPanelLESS implements LESS web debug panel.
 *
 * @package    sfLESSPlugin
 * @subpackage debug
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */
class sfWebDebugPanelLESS extends sfWebDebugPanel
{
  /**
   * Some files have been compiled
   */
  protected $compiled = false;

  /**
   * Some errors where triggered during the compilation
   */
  protected $errors = false;

  /**
   * Configuration
   */
  protected $config;

  /**
   * Listens to LoadDebugWebPanel event & adds this panel to the Web Debug toolbar
   *
   * @param   sfEvent $event
   */
  public static function listenToLoadDebugWebPanelEvent(sfEvent $event)
  {
    $event->getSubject()->setPanel(
      'documentation',
      new self($event->getSubject())
    );
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getTitle()
  {
    $path = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();
  	return '<img src="'.$path.'/sfLESSPlugin/images/css_go.png" alt="LESS helper" height="16" width="16" /> less';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelTitle()
  {
    return 'LESS Stylesheets';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelContent()
  {
    $panel = $this->getConfigurationContent() . '<table class="sfWebDebugLogs" style="width: 300px"><tr><th>less file</th><th>css file</th><th style="text-align:center;">time (ms)</th></tr>';
    $errorDescriptions = sfLESS::getCompileErrors();
    foreach (sfLESS::getCompileResults() as $info)
    {
      $info['error'] = isset($errorDescriptions[$info['lessFile']]) ? $errorDescriptions[$info['lessFile']] : false;
      $panel .= $this->getInfoContent($info);
    }
    $panel .= '</table>';

    $this->setStatus($this->errors?sfLogger::ERR:($this->compiled?sfLogger::WARNING:sfLogger::INFO));

    return $panel;
  }

  /**
   * Returns configuration information for LESS compiler
   *
   * @return  string
   */
  protected function getConfigurationContent()
  {
    $debugInfo = '<dl id="less_debug" style="display: none;">';
    $this->config = sfLESS::getConfig();
    foreach ($this->config->getDebugInfo() as $name => $value)
    {
      $debugInfo .= sprintf('<dt style="float:left; width: 100px"><strong>%s:</strong></dt>
      <dd>%s</dd>', $name, $value);
    }
    $debugInfo .= '</dl>';

    return sprintf(<<<EOF
      <h2>configuration %s</h2>
      %s<br/>
EOF
      ,$this->getToggler('less_debug', 'Toggle debug info')
      ,$debugInfo
    );
  }

  /**
   * Returns information row for LESS style compilation
   *
   * @param   array   $info info of compilation process
   * @return  string
   */
  protected function getInfoContent($info, $error = false)
  {
    // ID of error row
    $errorId = md5($info['lessFile']);

    // File link for preferred editor
    $fileLink = $this->formatFileLink(
      $info['lessFile'], 1, str_replace($this->config->getLessPaths(), '', $info['lessFile'])
    );

    // Checking compile & error statuses
    if ($info['isCompiled'])
    {
      $this->compiled = true;
      $trStyle = 'background-color:#a1d18d;';
    }
    elseif ($info['error'])
    {
      $this->errors = true;
      $trStyle = 'background-color:#f18c89;';
      $fileLink .= ' ' . $this->getToggler('less_error_' . $errorId, 'Toggle error info');
    }
    else
    {
      $trStyle = '';
    }

    // Generating info rows
    $infoRows = sprintf(<<<EOF
      <tr style="%s">
        <td class="sfWebDebugLogType">%s</td>
        <td class="sfWebDebugLogType">%s</td>
        <td class="sfWebDebugLogNumber" style="text-align:center;">%.2f</td>
      </tr>
      <tr id="less_error_%s" style="display:none;background-color:#f18c89;"><td style="padding-left:15px" colspan="2">%s<td></tr>
EOF
      ,$trStyle
      ,$fileLink
      ,str_replace($this->config->getCssPaths(), '', $info['cssFile'])
      ,($info['isCompiled'] ? $info['compTime'] * 1000 : 0)
      ,$errorId
      ,$info['error']
    );

    return $infoRows;
  }
}
