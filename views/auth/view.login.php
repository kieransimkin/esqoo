<?php
$this->header(_('Login'));
?>
<section>
<input type="email" name="identity" id="login-identity" placeholder="you@yourdomain.com">
<input type="password" name="password" id="login-password" placeholder="******">
</section>
<?php
$this->footer();
?>