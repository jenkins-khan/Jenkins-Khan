<?php



/**
 * Skeleton subclass for representing a row from the 'jenkins_group_run' table.
 *
 *
 *
 * This class was autogenerated by Propel 1.6.3 on:
 *
 * Fri Jan 20 17:32:29 2012
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class JenkinsGroupRun extends BaseJenkinsGroupRun
{

  /**
   * @param Jenkins $jenkins
   * @return null
   */
  public function getResult(Jenkins $jenkins)
  {
    $lastWeight = -1;
    $result     = null;

    foreach ($this->getJenkinsRuns() as $jenkinsRun)
    {
      /** @var JenkinsRun $jenkinsRun */
      $runResult = $jenkinsRun->getJenkinsResult($jenkins);
      if (($weight = $this->getResultWeight($runResult)) > $lastWeight)
      {
        $result     = $runResult;
        $lastWeight = $weight;
      }
    }

    return $result;
  }

  /**
   * @param $result
   * @return int
   */
  private function getResultWeight($result)
  {
    switch($result)
    {
      case JenkinsRun::UNREACHABLE:
        $weight = 64;
        break;
      case JenkinsRun::FAILURE:
        $weight = 32;
        break;
      case JenkinsRun::RUNNING:
        $weight = 16;
        break;
      case JenkinsRun::WAITING:
        $weight = 8;
        break;
      case JenkinsRun::UNSTABLE:
        $weight = 4;
        break;
      case JenkinsRun::DELAYED:
        $weight = 2;
        break;
      case JenkinsRun::ABORTED:
        $weight = 1;
        break;
      default:
      case JenkinsRun::SUCCESS:
        $weight = 0;
        break;
    }
    return $weight;
  }

  /**
   * @param \Jenkins $jenkins
   * @param array    $default
   *
   * @return array
   */
  public function buildDefaultFormValue(Jenkins $jenkins, $default = array())
  {
    $default['git_branch'] = $this->getGitBranch();
    $default['label']      = $this->getLabel();
    foreach ($this->getJenkinsRuns() as $run)
    {
      /** @var JenkinsRun $run  */
      $default['builds'][$run->getJobName()] = array(
        'job_name'   => true,
        'parameters' => $run->getJenkinsBuildCleanedParameter($jenkins)
      );
    }

    return $default;
  }
  
} // JenkinsGroupRun
