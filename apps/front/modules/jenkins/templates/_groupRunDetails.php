<?php /** @var string $id */ ?>
<?php /** @var string $date */ ?>
<?php /** @var string $status */ ?>

<div class="popover-content" id="<?php echo $id; ?>">
  <ul>
    <li>Creation date: <?php echo $date; ?></li>
    <?php if (strlen($status)): ?>
      <li>Status: <?php echo $status; ?></li>
    <?php endif; ?>
  </ul>
</div>