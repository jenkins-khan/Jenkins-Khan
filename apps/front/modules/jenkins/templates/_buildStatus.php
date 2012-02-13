<?php

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
}

if (isset($label))
{
  $message = $label;
}

?>
<span class="label <?php echo $class ?>"><?php echo $message ?></span>