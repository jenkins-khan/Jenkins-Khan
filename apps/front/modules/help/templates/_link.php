<?php /** @var string $name */ ?> 
<?php $identifier = isset($identifier) ? $identifier : md5($name); ?>
<?php $title_link = isset($title_link) ? $title_link : 'Display help' ?> 
<a data-toggle="modal" href="#<?php echo $identifier; ?>" title="<?php echo $title_link?>">
  <span class="jk-icon-help"></span>
</a>

