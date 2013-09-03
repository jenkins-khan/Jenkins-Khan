<?php
/**
 * @var string $status
 * @var string $label
 * @var string $url
 */

$class    = null;
$message  = null;

switch ($status)
{
  case JenkinsRun::UNSTABLE:
    $class    = 'warning';
    $message  = 'UNSTABLE';
    break;
  
  case JenkinsRun::FAILURE:
    $class    = 'important';
    $message  = 'FAILURE';
    break;
  
  case JenkinsRun::SUCCESS:
    $class    = 'success';
    $message  = 'SUCCESS \o/';
    break;
  
  case JenkinsRun::RUNNING:
    $class    = 'notice';
    $message  = 'RUNNING...';
    break;
  
  case JenkinsRun::WAITING:
    $class    = 'waiting';
    $message  = 'WAITING';
    break;

  case JenkinsRun::UNREACHABLE:
    $class    = 'black';
    $message  = '???';
    break;
  
  case JenkinsRun::ABORTED:
    $class    = 'aborted';
    $message  = 'ABORTED';
    break; 
  
  case JenkinsRun::DELAYED:
    $class    = 'delayed';
    $message  = 'DELAYED';
    break;  
  
  case JenkinsRun::SCHEDULED:
    $class    = 'scheduled';
    $message  = 'SCHEDULED';
    break;
}

if (isset($label))
{
  $message = $label;
}

?>

<?php if (isset($url)): ?>
  <a href="<?php echo $url; ?>"><span class="label jenkins-status <?php echo $class ?>"><?php echo $message ?></span></a>
<?php else: ?>
  <span class="label jenkins-status <?php echo $class ?>"><?php echo $message ?></span>
<?php endif; ?>
