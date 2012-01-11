<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
{* Default meta tags *}
{almtemplate file="meta.tpl"}
<title>{if $title}{$title}{else}{$smarty.const.ALM_TITLE_INDEX}{/if} - {$smarty.session.alm_user} @ {$smarty.const.DOMAIN}</title>
{* Minumun required js for admin *}
{almtemplate file="js.tpl"}
{* Default CSS style, depend on the theme *}
{almtemplate file="css.tpl"}
</script>
</head>
<body>
<h1>{$title|default:""}</h1>
