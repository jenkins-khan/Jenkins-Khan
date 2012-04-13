<?php $class = isset($class) ? $class : ""; ?>
<?php $label = isset($label) ? $label : ""; ?>
<?php $align_right = isset($align_right) ? $align_right : false; ?>
<?php $links = isset($links) ? $links : array(); ?>

<div class="btn-group">
  <a class="btn dropdown-toggle <?php echo $class?>" data-toggle="dropdown" href="#">
    <?php echo $label ?>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu<?php $align_right && print " dropdown-right" ?> ">
    <?php foreach ($links as $link): ?>
      <?php $options = array('title' => $link['label']) ?>
      <?php if (isset($link['options'])): ?>
        <?php foreach ($link['options'] as $key => $value): ?>
          <?php $options[$key] = $value; ?>
        <?php endforeach; ?>
      <?php endif; ?> 
      <li><?php echo link_to($link['label'], $link['url'], $options) ?></li>
    <?php endforeach; ?>
  </ul>
</div>
  
  
  