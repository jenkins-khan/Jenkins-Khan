<?php /** @var array $parameters */ ?>

<?php if (count($parameters) > 0): ?>
  <ul class="build-parameters">
    <?php foreach ($parameters as $parameter => $value): ?>
      <li><?php echo $parameter ?> : <?php echo $value ?> </li>
    <?php endforeach; ?>
  </ul>
<?php endif;
  