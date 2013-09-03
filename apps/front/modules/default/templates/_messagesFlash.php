<?php /** @var $messages_by_level array */ ?>
<?php foreach ($messages_by_level as $level => $messages): ?>
  <?php foreach ($messages as $message): ?>
    <div class="alert alert-<?php echo $level; ?>">
      <?php echo $message ?>
    </div>
  <?php endforeach; ?>
<?php endforeach; ?>
