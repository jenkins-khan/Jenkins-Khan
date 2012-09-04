<?php

class sfWidgetFormSchemaFormatterJobParameter extends sfWidgetFormSchemaFormatterDiv
{

  /**
   * @param string $help
   *
   * @return string
   */
  public function formatHelp($help)
  {
    if (!$help)
    {
      return '';
    }

    use_helper('Partial');
    return get_partial('help/link-modal', array(
      'name'   => 'jenkins/addBuild/parameter-description',
      'title'  => "Parameter",
      'params' => array('help' => $help),
    ));
  }

}
