<?php

class jkLaunchScheduledTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'jk';
    $this->name             = 'daemon-launch-scheduled';
    $this->briefDescription = 'Launch scheduled jobs';
    $this->detailedDescription = <<<EOF
The [init|INFO] task scheduled launchs jobs 
Call it with:

  [php symfony jk:daemon-launch-scheduled|INFO]
EOF;

    $this->addOptions(array(
      new sfCommandOption('polling-delay', null, sfCommandOption::PARAMETER_REQUIRED, 'délais entre chaque lancement de job', 5),
      new sfCommandOption('max-execution-time', null, sfCommandOption::PARAMETER_REQUIRED, 'temps d exec max', 3600),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'front'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment', 'prod'),
    ));
    
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $start_time = time();

    while (true)
    {
      try
      {
        $query = JenkinsRunQuery::create();
        $query->filterByLaunchDelayed(array('max' => 'now'));

        $jenkinsFactory = new Jenkins_Factory();
        $runs = $query->find();
        foreach ($runs as $run)
        {
          /** @var JenkinsRun $run */
          $user = $run->getJenkinsGroupRun()->getsfGuardUser();
          $jenkinsUrl = $user->getProfile()->getJenkinsUrl();
          $jenkins = $jenkinsFactory->build($jenkinsUrl);

          if (!$jenkins->isAvailable())
          {
            $this->log(sprintf('%s is unreachable', $jenkinsUrl));
            continue;
          }

          $run->launchDelayed($jenkins);
          $this->log(sprintf('%s [%s] has been launched for user %s', $run->getJobName(), $run->getGitBranch(), $user->getUsername()));
        }
      }
      catch (Exception $e)
      {
        $msg = '{jk:daemon-launch-scheduled} : Une erreur est survenue : '.$e->getMessage();
        // ecriture sur la sortie d'erreur
        file_put_contents('php://stderr', $msg);
        exit(1);
      }

      sleep($options['polling-delay']);
      if ((time() - $start_time) > $options['max-execution-time'])
      {
        exit(0);
      }
    }

   
  }
}
