<?php

class durationFormatter
{

  /**
   * @param float $value durée en secondes
   *
   * @return string
   */
  public function formatte($value)
  {
    if (null === $value)
    {
      return $value;
    }
    
    $unites = array(
      'h'   => array('divisor' => 3600),
      'min' => array('divisor' => 60),
      's'   => array('divisor' => null),
    );
    $unites['s']['round'] = 0;
    $foundUnites = array();
    foreach ($unites as $unite => $uniteInfos)
    {
      if (null !== $uniteInfos['divisor'])
      {
        $quotient = floor($value / $uniteInfos['divisor']);
        $foundUnites[$unite] = $quotient;
        $value -= $quotient * $uniteInfos['divisor'];
      }
      else
      {
        $foundUnites[$unite] = $value;
        break;
      }
    }

    // nombre d'éléments max à afficher (par exemple on n'affiche pas les secondes qd on a des heures)
    $maxElements = 2; 
    
    $str = array();
    $i = null;
    foreach ($foundUnites as $unite => $foundUnite)
    {
      if ($i>=$maxElements)
      {
        break;
      }
      if ($foundUnite)
      {
        if (null === $i)
        {
          $i = 0;
        }
        $roundedValue = isset($unites[$unite]['round']) ? round($foundUnite, $unites[$unite]['round']) : $foundUnite;

        $str[] = sprintf('%s%s', $roundedValue, $unite);
      }
      if (null !== $i)
      {
        $i++;
      }
    }

    return implode("&nbsp;", $str);
  }
}

