Jenkins Khan
============

Jenkins Khan is a php tool that helps you manage a [Jenkins](http://jenkins-ci.org/) integration server.

With JK you can easily launch different jobs on your working branches and check the result of each job, grouped by your branches.

Why ?
-------

When it comes to working with many branches, Jenkins is not very user-friendly.  
It's kind of a mess to check your jobs state on every branch. We need an overview.

How ?
--------

Jenkins Khan uses Jenkins' API to retrieve the configuration, jobs, jobs parameters, view, status, etc.  
You just have to register your Jenkins Url in Jenkins Khan and enjoy the fun.

Requirements
-------------

 * [Jenkins](http://jenkins-ci.org/)
 * [Jenkins Git Plugin](https://wiki.jenkins-ci.org/display/JENKINS/Git+Plugin) *(but it __should__ work on any VCS you want)*
 * Each Jenkins job must have a branch specifier named `BRANCH`
 * PHP >= 5.2