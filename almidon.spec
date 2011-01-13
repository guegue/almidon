%define contentdir /var/www/
%define logdir /var/log/

Name: almidon
Version: 0.9.1
Release: 1%{?dist}
Summary: Plataforma de desarrollo rapido php+postgresql

Group: Development/Languages
License: GPLv3
URL: http://almidon.org/
Source0: http://almidon.org/downloads/%{name}-%{version}.tar.gz
BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-root-%(%{__id_u} -n)
Requires: php, php-pgsql, postgresql, postgresql-server, httpd
BuildArch: noarch

%description
Almidón es una plataforma que permite un desarrollo sólido de un
sitio web, una administración sencilla, rápida, y un sitio web con
buen desempeño. Actualmente en su mayoría escrito para Linux usando
PHP, Apache y Postgresql, pero probado y usado en otras plataformas.

%install
rm -rf %{buildroot}
mkdir -p %{buildroot}/%{_docdir}/%{name}-%{version}
mkdir -p  %{buildroot}/%{contentdir}/%{name}
mkdir -p  %{buildroot}/%{_sysconfdir}/cron.d
mkdir -p  %{buildroot}/%{_sysconfdir}/httpd/conf.d
cat demo/create.sql core/sql/alm.tables.sql demo/demo.sql > %{_tmppath}/demo.sql
cp demo/country.sql %{_tmppath}/
cp demo/classes/config.ori.php  demo/classes/config.php
cp -a scripts demo core %{buildroot}/%{contentdir}/%{name}/
cp -a doc/* %{buildroot}/%{_docdir}/%{name}-%{version}/
cp -a almidon.cron  %{buildroot}/%{_sysconfdir}/cron.d/almidon
cp -a demo/demo.almidon.conf  %{buildroot}/%{_sysconfdir}/httpd/conf.d/

%files
%defattr(-,root,root,-)
%{contentdir}/%{name}/core
%{contentdir}/%{name}/scripts

%changelog

* Wed Sep 29 2010 Javier Wilson <javier@guegue.net> - 0.9.1
- Adding subpackages.
* Fri Aug 6 2010 Javier Wilson <javier@guegue.net> - 0.6.1
- Initial package.

%package demo

Summary: Demo para Almidon: Plataforma de desarrollo rapido
Group: Development/Languages
Requires(pre): %{_sbindir}/useradd, /sbin/runuser
Requires(postun): /sbin/service 
Requires: almidon
BuildArch: noarch

%description demo
El demo de Almidón incluye un ejemplo sencillo del uso de esta
plataforma de desarrollo rápido. Está elaborado usando PHP,
Apache y Postgresql.

%prep
%setup -q

echo "0 2 * * *	almidon	%{_sbindir}/tmpwatch -umc %{_datadir}/%{name}/demo/cache/ > /dev/null 2>&1" >almidon.cron

%clean
rm -rf %{buildroot}

%pre
echo "Instalando demo de almidon en `pwd` `date`" > %{logdir}/almidon.log
/sbin/runuser -c "psql -f %{_tmppath}/demo.sql" postgres >> %{logdir}/almidon.log 2>&1
/sbin/runuser -c "psql -f %{_tmppath}/country.sql" postgres >> %{logdir}/almidon.log 2>&1
# FIXME: keeps adding this line
echo "local almidondemo all md5" >>  %{_sharedstatedir}/pgsql/data/pg_hba.conf
%{_sbindir}/useradd -d %{_datadir}/%{name} -r -s /sbin/nologin almidon 2> /dev/null || :

%post
if [ $1 == 1 ]; then
	/sbin/service httpd condrestart > /dev/null 2>&1 || :
	/sbin/service postgresql condrestart > /dev/null 2>&1 || :
fi

%postun
/sbin/service httpd condrestart > /dev/null 2>&1 || :
/sbin/service postgresql condrestart > /dev/null 2>&1 || :

%files demo
%defattr(-,root,root,-)
%{contentdir}/%{name}/demo/public_html
%{contentdir}/%{name}/demo/misc
%{contentdir}/%{name}/demo/templates
%{contentdir}/%{name}/demo/.htpasswd
%{contentdir}/%{name}/demo/demo.almidon.conf
%{contentdir}/%{name}/demo/*sql
%{contentdir}/%{name}/demo/secure
%{contentdir}/%{name}/demo/classes/app.class.php
%{contentdir}/%{name}/demo/classes/extra.class.php
%{contentdir}/%{name}/demo/classes/config.ori.php
%config(noreplace) %{_sysconfdir}/httpd/conf.d/demo.almidon.conf
%config(noreplace) %{_sysconfdir}/cron.d/almidon
%attr(0660,almidon,apache) %config(noreplace) %{contentdir}/%{name}/demo/classes/config.php
%attr(0660,almidon,apache) %config(noreplace) %{contentdir}/%{name}/demo/classes/tables.class.php
#%attr(0660,almidon,apache) %config(noreplace) %{contentdir}/%{name}/demo/logs/install.log
%attr(0770,almidon,apache) %{contentdir}/%{name}/demo/cache
%attr(0770,almidon,apache) %{contentdir}/%{name}/demo/logs
%attr(0770,almidon,apache) %{contentdir}/%{name}/demo/files
%attr(0770,almidon,apache) %{contentdir}/%{name}/demo/templates_c

%package doc

Summary: Doc para Almidon: Plataforma de desarrollo rapido.
Group: Development/Languages
BuildArch: noarch

%description doc
Documentación de Almidón (plataforma de desarrollo rápido) incluye documentación phpdoc.

%files doc
%doc %{_docdir}/*
