<p>
  URL of the Jenkins you want to use.<br />
  Something like
</p>
<pre>http://localhost:8080</pre>

<h3>If Jenkins's security is enabled</h3>

<p>
  If your Jenkins needs credentials you need to put your
  jenkins user and token api before the hostname.<br />
  So you should indicate an URL like this :
</p>
  <pre>http://user:token@ci.mydomain.org:8080</pre>
<p>
  More information about this could be found on <a href="https://wiki.jenkins-ci.org/display/JENKINS/Remote+access+API" target="_blank">Jenkins wiki</a>.
</p>
