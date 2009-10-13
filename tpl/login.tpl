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
<div id="ctr" align="center">
	<div class="login">
		<div class="login-form">
        	<form action="" method="post" name="loginForm" id="loginForm">
			<div class="form-block">
	        	<div class="inputlabel">Usuario</div>
		    	<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
	        	<div class="inputlabel">Contrase&ntilde;a</div>
	    	  <div><input name="pass" type="password" class="inputbox" size="15" /></div>
	        	<div align="left"><input type="submit" name="submit" class="button" value="Login" /></div>
        	</div>
			</form>
    	</div>
		<div class="login-text">
        	<p>Bienvenido al sitio de administraci&oacute;n de Almidon</p>
			<p>Use un usuario valido para acceder a la consola de administraci&oacute;n</p>
    	</div>
		<div class="clr"></div>
	</div>
</div>
<div id="break" class="login-text1" align="center">{if $bError}Usuario y Contrase&ntilde;a incorrectos{/if}</div>
</body>
</html>
