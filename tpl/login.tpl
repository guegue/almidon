<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$Title}</title>
<meta http-equiv="Content-Type" content="text/html" />
{literal}
<link rel="stylesheet" href="/cms/css/admin_login.css">
<script language="javascript" type="text/javascript">
	function setFocus() {
		document.loginForm.usrname.select();
		document.loginForm.usrname.focus();
	}
</script>
{/literal}
</head>
<body onload="setFocus();">
<div align="center">
	<div class="login">
		<div>
        		<p><img src="/img/almidon-atma.png" width="262" height="78"/></p>
    		</div>
        	<form action="" method="post" id="loginForm" name="loginForm">
			<div class="form-block">
	        		<b>Usuario</b>
		    		<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
	        		<b>Contrase&ntilde;a</b>
	    	  		<div><input name="pass" type="password" class="inputbox" size="15" /></div>
		                <b>CAPTCHA</b> <img src="captcha.png" width="55" height="20"/>
				<div><input name="txtcaptcha" class="inputbox" type="text" size="8"/></div>
	        		<div align="center"><input type="submit" name="submit" class="button" value="Entrar" /></div>
        		</div>
		</form>
                {if $sError}<div class="error">No hay soporte para sesisiones. Debe habilitar soporte para cookies antes de continuar.</div>{/if}
                {if $bError}<div class="error">Usuario y/o contrase&ntilde;a incorrectos</div>{/if}
	</div>
</div>
</body>
</html>
