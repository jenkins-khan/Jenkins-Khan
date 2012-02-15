<?php /** @var array   $menus */ ?>
<?php /** @var string  $activeLink */ ?>
<?php /** @var int     nb_delayed */ ?>
<?php /** @var myUser  $user */ ?>

<div class="topbar">
  <div class="topbar-inner">
    <div class="container-fluid">
      <h1><a class="brand" href="#">Jenkins Khan</a></h1>
      <?php if ($user->isAuthenticated()): ?>
        <ul class="nav">
          <?php foreach ($menus as $name => $menu): ?>
            <li class="<?php $menu['url'] === $activeLink && print 'active' ; isset($menu['class']) && print ' ' . $menu['class'] ?>">
              <?php echo link_to($name, $menu['url'], array(
                'title' => isset($menu['title']) ? $menu['title'] : $name
              )); ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="pull-right">
          <ul>
            <li class="<?php 'user/configure' === $activeLink && print 'active' ?>">
              <?php echo link_to('Configuration', 'user/configure', array('class' => 'settings', 'title' => 'Configure your profile')) ?>
            </li>
            <li><?php echo link_to('Logout', '@sf_guard_signout') ?><li>
          </ul>
        </div>
      <?php endif; ?> 
    </div>
  </div>
</div><?php

