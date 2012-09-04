<?php
/**
 * @var array $params
 */

if (isset($params['help']))
{
  use_helper('Markdown');
  echo '<div class="description parameter">' . esc_raw(Markdown($params['help'])) . '</div>';
}

