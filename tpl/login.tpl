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
	        		<div align="center"><input type="submit" name="submit" class="button" value="Entrar" /></div>
        		</div>
		</form>
		<div class="error">{if $bError}Usuario y Contrase&ntilde;a incorrectos{/if}</div>
	</div>
</div>
</body>
</html>
