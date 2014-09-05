<h2>Login</h2>
<?php
echo $this->Form->create('User', array(
    'url' => array(
        'controller' => 'users',
        'action' => 'login'
    )
));
echo $this->Form->input('User.email');
echo $this->Form->input('User.password');
echo $this->Form->end( 'Login' );
?>


<div id="signinButton">
  <span class="g-signin"
    data-scope="https://www.googleapis.com/auth/plus.login"
    data-clientid="355448840085-0b92qc1ksodcd03ca2fidmbfu2iv5f3l.apps.googleusercontent.com"
    data-redirecturi="/users/oauth2redirect"
    data-accesstype="offline"
    data-cookiepolicy="single_host_origin"
    data-callback="signInCallback">
  </span>
</div>
<div id="result"></div>
