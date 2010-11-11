{capture name=alm_output}
<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>
{include file=$smarty.const.ALMIDONDIR|cat:"/tpl/meta.tpl"}
<title>almidon @ {$smarty.const.DOMAIN}</title>
{include file=$smarty.const.ALMIDONDIR|cat:"/tpl/css.tpl" login=true}
<script language="javascript" type="text/javascript">
{literal} function setFocus() {  document.loginForm.alm_user.select(); document.loginForm.alm_user.focus(); } {/literal}
</script>
</head>
<body onload="setFocus();">
<div align="center">
  <div class="login">
    <div>
      <p><img src="{$smarty.const.URL}/img/website-logo.png" width="262" height="78" alt="{$smarty.const.DOMAIN} - Website Logo" title="{$smarty.const.DOMAIN} - WebSite Logo" /></p>
    </div>
    <form action="" method="post" id="loginForm" name="loginForm">
    <div class="form-block">
      <b>{$smarty.const.ALM_USERNAME}</b>
      <div><input id="alm_user" name="alm_user" type="text" class="inputbox" size="15" /></div>
      <b>{$smarty.const.ALM_PASSWORD}</b>
      <div><input id="password" name="password" type="password" class="inputbox" size="15" /></div>
      {if !$whitelist}
      <b>CAPTCHA</b> <img src="captcha.png" width="55" height="20" alt="CAPTCHA"/>
      <div><input name="txtcaptcha" class="inputbox" type="text" size="8"/></div>
      {/if}
      <div align="center"><input type="submit" name="submit" class="button" value="{$smarty.const.LOGIN}" /></div>
    </div><!--#form-block-->
    </form>
    {if $bError}<div class="error">{$smarty.const.ALM_AUTH_ERROR}</div>{/if}
    {if $sError}<div class="error">{$smarty.const.ALM_NO_COOKIE}</div>{/if}
  </div><!--.login-->
</div>
</body>
</html>
{/capture}
{* Optimize the output *}
{$smarty.capture.alm_output|strip}
