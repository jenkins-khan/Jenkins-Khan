<div class="modal hide" id="<?php echo md5($name) ?>">

  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3></h3>
  </div>

  <div class="modal-body">
    <?php include_partial('help/helps/' . $name) ?>
  </div>

  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
  </div>

</div>
