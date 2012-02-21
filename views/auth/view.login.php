<?php
$this->header(_('Login'));
?>
<section class="login-box">
<label for="login-identity"><?=_('Username or Email:')?></label><input type="email" name="identity" id="login-identity" placeholder="you@yourdomain.com">
<label for="login-password"><?=$_('Password:')?></label><input type="password" name="password" id="login-password" placeholder="******">
<input type="hidden" id="login-challenge" value="<?=$this->challenge->challenge;?>">
<input type="hidden" id="login-challenge-id" value="<?=$this->challenge->id;?>">
</section>
<?php
$this->footer();
?>
