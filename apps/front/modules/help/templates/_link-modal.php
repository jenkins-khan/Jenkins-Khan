<?php $title = isset($title) ? $title : ''?>
<?php $params = isset($params) ? $params : array()?>
<?php $identifier = md5(uniqid()); ?>
<?php include_partial('help/link', array('name' => $name, 'identifier' => $identifier)) ?>
<?php include_partial('help/modal', array('name' => $name, 'identifier' => $identifier, 'title' => $title, 'params' => $params)) ?>



