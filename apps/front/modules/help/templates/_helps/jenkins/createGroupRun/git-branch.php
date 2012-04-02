<h3 data-modal-title="add">Git Branch</h3>

<p>
  Here, you must input the name of the local git branch that
  you want to build.
</p>

<p>
 This branch name will be passed to Jenkins as a parameter 
  called <code>BRANCH</code>, and that git branch will be
  cloned / updated when the build starts.
</p>

<p>
  Note that the branch will be cloned from your local computer:
  there is no need to push it to your remote.
</p>
  
<p>
  In this this field, you can use everything that is 
  <a href="http://book.git-scm.com/4_git_treeishes.html" title="Git Treeishes" alt="Git Treeishes">treeish</a>.
</p>