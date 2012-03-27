<?php /** @var string $name */ ?>
<?php /** @var string $title */ ?>

<div class="modal hide" id="<?php echo md5($name) ?>">

  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo $title ?></h3>
  </div>

  <div class="modal-body">
    <?php include_partial('help/helps/' . $name) ?>
  </div>

  <div class="modal-footer">
    <a href="#" class="btn btn-large btn-primary" data-dismiss="modal">Close</a>
  </div>

</div>
