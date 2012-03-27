<?php /** @var string $name */ ?> 
<?php $title_link = isset($title_link) ? $title_link : 'Display help' ?> 
<a data-toggle="modal" href="#<?php echo md5($name) ?>" title="<?php echo $title_link?>">
  <span class="jk-icon-help"></span>
</a>

