dnl Process this file with autoconf to produce a configure script.
dnl Created by Anjuta application wizard.

AC_INIT(almidon, 0.7.1)

AM_INIT_AUTOMAKE(AC_PACKAGE_NAME, AC_PACKAGE_VERSION)
AM_CONFIG_HEADER(config.h)
AM_MAINTAINER_MODE

AC_ISC_POSIX
AC_PROG_CXX
AM_PROG_CC_STDC
AC_HEADER_STDC

dnl ***************************************************************************
dnl Internatinalization
dnl ***************************************************************************
GETTEXT_PACKAGE=almidon
AC_SUBST(GETTEXT_PACKAGE)
AC_DEFINE_UNQUOTED(GETTEXT_PACKAGE,"$GETTEXT_PACKAGE", [GETTEXT package name])
AM_GLIB_GNU_GETTEXT
IT_PROG_INTLTOOL([0.35.0])

AM_PROG_LIBTOOL

AC_ARG_VAR(documentrootdir, AC_HELP_STRING([--documentrootdir=DIR],[ is a directory which stored the website managment under Web Server Apache2]) )
 
if [ test -z  "$documentrootdir" ]; then
  if [ test -d /var/www ];then
   documentrootdir=/var/www
  else
         if [ test -d /srv/www ];then
          documentrootdir=/srv/www
         fi
  fi
fi

AC_SUBST(documentrootdir)

AC_OUTPUT([
Makefile
atma_core/Makefile
atma_core/cloudfiles/Makefile
atma_core/include.d/Makefile
atma_core/php/Makefile
atma_core/php/pear/Makefile
atma_core/php/pear/Calendar/Makefile
atma_core/php/pear/Calendar/Month/Makefile
atma_core/php/pear/Calendar/Decorator/Makefile
atma_core/php/pear/Calendar/Engine/Makefile
atma_core/php/pear/Calendar/Table/Makefile
atma_core/php/pear/Calendar/Util/Makefile
atma_core/php/pear/Date/Makefile
atma_core/php/pear/XML/Makefile
atma_core/php/pear/XML/Parser/Makefile
atma_core/php/Rounded/Makefile
atma_core/php/Smarty/Makefile
atma_core/php/Smarty/internals/Makefile
atma_core/php/Smarty/plugins/Makefile
atma_core/pub/Makefile
atma_core/pub/js/Makefile
atma_core/pub/js/autocomplete/Makefile
atma_core/pub/js/html/Makefile
atma_core/pub/js/html/icons/Makefile
atma_core/pub/js/html/popups/Makefile
atma_core/pub/js/html/styles/Makefile
atma_core/pub/js/tiny_mce/Makefile
atma_core/pub/js/tiny_mce/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/Makefile
atma_core/pub/js/tiny_mce/plugins/advhr/Makefile
atma_core/pub/js/tiny_mce/plugins/advhr/css/Makefile
atma_core/pub/js/tiny_mce/plugins/advhr/js/Makefile
atma_core/pub/js/tiny_mce/plugins/advhr/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/advimage/Makefile
atma_core/pub/js/tiny_mce/plugins/advimage/css/Makefile
atma_core/pub/js/tiny_mce/plugins/advimage/img/Makefile
atma_core/pub/js/tiny_mce/plugins/advimage/js/Makefile
atma_core/pub/js/tiny_mce/plugins/advimage/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/advlink/Makefile
atma_core/pub/js/tiny_mce/plugins/advlink/css/Makefile
atma_core/pub/js/tiny_mce/plugins/advlink/js/Makefile
atma_core/pub/js/tiny_mce/plugins/advlink/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/advlist/Makefile
atma_core/pub/js/tiny_mce/plugins/autoresize/Makefile
atma_core/pub/js/tiny_mce/plugins/autosave/Makefile
atma_core/pub/js/tiny_mce/plugins/autosave/images/Makefile
atma_core/pub/js/tiny_mce/plugins/autosave/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/bbcode/Makefile
atma_core/pub/js/tiny_mce/plugins/contextmenu/Makefile
atma_core/pub/js/tiny_mce/plugins/directionality/Makefile
atma_core/pub/js/tiny_mce/plugins/emotions/Makefile
atma_core/pub/js/tiny_mce/plugins/emotions/img/Makefile
atma_core/pub/js/tiny_mce/plugins/emotions/js/Makefile
atma_core/pub/js/tiny_mce/plugins/emotions/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/example/Makefile
atma_core/pub/js/tiny_mce/plugins/example/img/Makefile
atma_core/pub/js/tiny_mce/plugins/example/js/Makefile
atma_core/pub/js/tiny_mce/plugins/example/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/fullpage/Makefile
atma_core/pub/js/tiny_mce/plugins/fullpage/css/Makefile
atma_core/pub/js/tiny_mce/plugins/fullpage/js/Makefile
atma_core/pub/js/tiny_mce/plugins/fullpage/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/fullscreen/Makefile
atma_core/pub/js/tiny_mce/plugins/iespell/Makefile
atma_core/pub/js/tiny_mce/plugins/inlinepopups/Makefile
atma_core/pub/js/tiny_mce/plugins/inlinepopups/skins/Makefile
atma_core/pub/js/tiny_mce/plugins/inlinepopups/skins/clearlooks2/Makefile
atma_core/pub/js/tiny_mce/plugins/inlinepopups/skins/clearlooks2/img/Makefile
atma_core/pub/js/tiny_mce/plugins/insertdatetime/Makefile
atma_core/pub/js/tiny_mce/plugins/layer/Makefile
atma_core/pub/js/tiny_mce/plugins/legacyoutput/Makefile
atma_core/pub/js/tiny_mce/plugins/media/Makefile
atma_core/pub/js/tiny_mce/plugins/media/css/Makefile
atma_core/pub/js/tiny_mce/plugins/media/img/Makefile
atma_core/pub/js/tiny_mce/plugins/media/js/Makefile
atma_core/pub/js/tiny_mce/plugins/media/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/nonbreaking/Makefile
atma_core/pub/js/tiny_mce/plugins/noneditable/Makefile
atma_core/pub/js/tiny_mce/plugins/pagebreak/Makefile
atma_core/pub/js/tiny_mce/plugins/pagebreak/css/Makefile
atma_core/pub/js/tiny_mce/plugins/pagebreak/img/Makefile
atma_core/pub/js/tiny_mce/plugins/paste/Makefile
atma_core/pub/js/tiny_mce/plugins/paste/js/Makefile
atma_core/pub/js/tiny_mce/plugins/paste/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/preview/Makefile
atma_core/pub/js/tiny_mce/plugins/preview/jscripts/Makefile
atma_core/pub/js/tiny_mce/plugins/print/Makefile
atma_core/pub/js/tiny_mce/plugins/save/Makefile
atma_core/pub/js/tiny_mce/plugins/searchreplace/Makefile
atma_core/pub/js/tiny_mce/plugins/searchreplace/css/Makefile
atma_core/pub/js/tiny_mce/plugins/searchreplace/js/Makefile
atma_core/pub/js/tiny_mce/plugins/searchreplace/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/spellchecker/Makefile
atma_core/pub/js/tiny_mce/plugins/spellchecker/css/Makefile
atma_core/pub/js/tiny_mce/plugins/spellchecker/classes/Makefile
atma_core/pub/js/tiny_mce/plugins/spellchecker/img/Makefile
atma_core/pub/js/tiny_mce/plugins/spellchecker/includes/Makefile
atma_core/pub/js/tiny_mce/plugins/style/Makefile
atma_core/pub/js/tiny_mce/plugins/style/css/Makefile
atma_core/pub/js/tiny_mce/plugins/style/js/Makefile
atma_core/pub/js/tiny_mce/plugins/style/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/tabfocus/Makefile
atma_core/pub/js/tiny_mce/plugins/table/Makefile
atma_core/pub/js/tiny_mce/plugins/table/css/Makefile
atma_core/pub/js/tiny_mce/plugins/table/js/Makefile
atma_core/pub/js/tiny_mce/plugins/table/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/template/Makefile
atma_core/pub/js/tiny_mce/plugins/template/css/Makefile
atma_core/pub/js/tiny_mce/plugins/template/js/Makefile
atma_core/pub/js/tiny_mce/plugins/template/langs/Makefile
atma_core/pub/js/tiny_mce/plugins/visualchars/Makefile
atma_core/pub/js/tiny_mce/plugins/wordcount/Makefile
atma_core/pub/js/tiny_mce/plugins/xhtmlxtras/Makefile
atma_core/pub/js/tiny_mce/plugins/xhtmlxtras/css/Makefile
atma_core/pub/js/tiny_mce/plugins/xhtmlxtras/js/Makefile
atma_core/pub/js/tiny_mce/plugins/xhtmlxtras/langs/Makefile
atma_core/pub/js/tiny_mce/themes/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/img/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/js/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/langs/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/skins/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/skins/default/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/skins/default/img/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/skins/o2k7/Makefile
atma_core/pub/js/tiny_mce/themes/advanced/skins/o2k7/img/Makefile
atma_core/pub/js/tiny_mce/themes/simple/Makefile
atma_core/pub/js/tiny_mce/themes/simple/img/Makefile
atma_core/pub/js/tiny_mce/themes/simple/langs/Makefile
atma_core/pub/js/tiny_mce/themes/simple/skins/Makefile
atma_core/pub/js/tiny_mce/themes/simple/skins/default/Makefile
atma_core/pub/js/tiny_mce/themes/simple/skins/o2k7/Makefile
atma_core/pub/js/tiny_mce/themes/simple/skins/o2k7/img/Makefile
atma_core/pub/js/tiny_mce/utils/Makefile
atma_core/pub/themes/Makefile
atma_core/pub/themes/wax/Makefile
atma_core/pub/themes/wax/css/Makefile
atma_core/pub/themes/wax/img/Makefile
atma_core/pub/themes/wax/img/networks/Makefile
atma_core/pub/themes/wax/tpl/Makefile
atma_core/pub/themes/wax/tpl/checking/Makefile
atma_core/smarty/Makefile
atma_core/smarty/validate/Makefile
atma_core/sql/Makefile
atma_core/tpl/Makefile
po/Makefile.in
demo/Makefile
demo/cache/Makefile
demo/cache/smarty/Makefile
demo/cache/sql/Makefile
demo/cache/thumb/Makefile
demo/classes/Makefile
demo/files/Makefile
demo/files/enlace/Makefile
demo/files/foto/Makefile
demo/files/pagina/Makefile
demo/logs/Makefile
demo/misc/Makefile
demo/public_html/Makefile
demo/public_html/css/Makefile
demo/public_html/img/Makefile
demo/secure/Makefile
demo/templates/Makefile
demo/templates_c/Makefile
doc/Makefile
doc/media/Makefile
doc/phpdoc/Makefile
doc/phpdoc/almidon/Makefile
doc/phpdoc/media/Makefile
scripts/Makefile
])

