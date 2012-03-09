<?php /** @var array   $menus */ ?>
<?php /** @var string  $activeLink */ ?>
<?php /** @var int     nb_delayed */ ?>
<?php /** @var myUser  $user */ ?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <h1><a class="brand" href="#">Jenkins Khan</a></h1>
      <?php if ($user->isAuthenticated()): ?>
        <ul class="nav">
          <?php foreach ($menus as $name => $menu): ?>
            <li class="<?php $menu['url'] === $activeLink && print 'active' ; isset($menu['class']) && print ' ' . $menu['class'] ?>">
              <?php echo link_to($name, $menu['url'], array(
                'title' => isset($menu['title']) ? $menu['title'] : $name
              )); ?>
            </li>
            <?php if (isset($menu['dropdowns']) && count($menu['dropdowns'])): ?>
            <li class="dropdown <?php echo $menu['dropdown_class'] ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b></a>
              <ul class="dropdown-menu">
                <?php foreach ($menu['dropdowns'] as $name => $dropdown): ?>
                  <li>
                    <?php echo link_to($name, $dropdown['url'], array(
                      'title' => isset($dropdown['title']) ? $dropdown['title'] : $name
                    )) ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </li>
            <?php endif ?>
          <?php endforeach; ?>
        </ul>
        
        <ul class="nav pull-right">
          <li class="<?php 'user/configure' === $activeLink && print 'active' ?>">
            <?php echo link_to('Configuration', 'user/configure', array('class' => 'settings', 'title' => 'Configure your profile')) ?>
          </li>
          <li><?php echo link_to('Logout', '@sf_guard_signout') ?><li>
        </ul>
      <?php endif; ?> 
    </div>
  </div>
</div>
  
<script type="text/javascript">
  $('.dropdown-toggle').dropdown();
</script>

