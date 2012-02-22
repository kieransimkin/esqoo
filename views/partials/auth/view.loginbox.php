<?php
?>
<section class="login-box">
	<div id="login-errors" style="display: none;"></div>
	<label for="login-identity"><?=_('Username or Email:')?></label><input type="email" name="identity" id="login-identity" placeholder="<?=_('you@yourdomain.com');?>" autofocus="autofocus">
	<label for="login-password"><?=_('Password:')?></label><input type="password" name="password" id="login-password" placeholder="<?=_('******');?>">
	<input type="hidden" id="login-challenge" value="<?=$this->challenge['Challenge'];?>">
	<input type="hidden" id="login-forward" value="<?=$this->forward;?>">
	<input type="hidden" id="login-challenge-id" value="<?=$this->challenge['ChallengeID'];?>">
	<button id="login-button" onclick="esqoo_login.login(); return false;"><?=_('Login');?></button>
	<noscript>
		<?=_('You will need JavaScript enabled past this point.');?>
	</noscript>
	<script>
	<!--
	$('#login-identity').keypress(esqoo_login.form_keypress);
	$('#login-password').keypress(esqoo_login.form_keypress);
	-->
	</script>
</section>
