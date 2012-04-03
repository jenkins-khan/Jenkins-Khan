<h3 data-modal-title="add">Jenkins Url</h3>

<p>
  Jenkins Khan acts as a layer over Jenkins, and, as such, needs to be able
  to call Jenkins' API.
</p>
<p>
  You have to set up the URL of the Jenkins instance you want to use.
  <br />Something like:
</p>
<pre>http://localhost:8080</pre>

<h3>If Jenkins's security is enabled</h3>

<p>
  If your Jenkins server is secured <em>(configured to require HTTP BASIC authentication)</em>,
  you need to enter your jenkins user and token api before the hostname.
  <br />In this case, you should indicate an URL like this:
</p>
  <pre>http://user:token@ci.mydomain.org:8080</pre>
<p>
  More information about this setup is available on <a href="https://wiki.jenkins-ci.org/display/JENKINS/Remote+access+API" target="_blank">Jenkins wiki</a>.
</p>
