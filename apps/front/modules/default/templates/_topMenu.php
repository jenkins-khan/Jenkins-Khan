<?php /** @var array   $menus */ ?>
<?php /** @var string  $activeLink */ ?>
<?php /** @var int     nb_delayed */ ?>
<?php /** @var myUser  $user */ ?>
<?php /** @var string  $available_jenkins_url */ ?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <h1><a class="brand" href="#">Jenkins Khan</a></h1>
      <?php if ($user->isAuthenticated()): ?>
        <ul class="nav">
          <?php foreach ($menus as $name => $menu): ?>
            <?php if (!isset($menu['dropdowns'])): ?>
              <li class="<?php $menu['url'] === $activeLink && print 'active' ; isset($menu['class']) && print ' ' . $menu['class'] ?>">
                <?php echo link_to($name, $menu['url'], array(
                  'title' => isset($menu['title']) ? $menu['title'] : $name
                )); ?>
              </li>
            <?php else: ?>
              <li>
                <div class="btn-group">
                  <?php echo link_to($name, $menu['url'], array(
                    'title' => isset($menu['title']) ? $menu['title'] : $name,
                    'class' => $menu['class'],
                  )); ?>
                  <a href="#" class="dropdown-toggle <?php echo $menu['dropdown_class'] ?>" data-toggle="dropdown"><b class="caret"></b></a>
                  <ul class="dropdown-menu dropdown-right">
                    <?php foreach ($menu['dropdowns'] as $name => $dropdown): ?>
                      <li>
                        <?php echo link_to($name, $dropdown['url'], array(
                          'title' => isset($dropdown['title']) ? $dropdown['title'] : $name
                        )) ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </li>
            <?php endif ?>
          <?php endforeach; ?>
        </ul>
        
        <ul class="nav pull-right">
          <li class>
            <?php if (null === $available_jenkins_url): ?> 
              <a href="#" class="icon jenkinsbig stopped" title="Jenkins is not started">Jenkins CI</a>
            <?php else: ?>
              <a href="<?php echo $available_jenkins_url ?>" class="icon jenkinsbig" title="Open Jenkins CI" target="_blank">Jenkins CI</a>
            <?php endif ?>
          </li>
          <li class="<?php 'user/configure' === $activeLink && print 'active' ?>">
            <?php echo link_to('Configuration', 'user/configure', array('class' => 'icon settings', 'title' => 'Configure your profile')) ?>
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

