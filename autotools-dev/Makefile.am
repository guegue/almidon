## Process this file with automake to produce Makefile.in
## Created by Anjuta

SUBDIRS = po \
	      atma_core \
	      demo \
	      doc   \
	      scripts 

## List of files included into directory target from instalation
## Reedit only if goes to include new file into directory target from instalation

almidonprojectbuilddoc_DATA = \
	README\
	COPYING\
	ChangeLog\
	INSTALL\
	NEWS 

## Directory target

almidonprojectbuilddocdir = ${prefix}/share/almidon/extra

## List of files included into directory target from instalation
## Reedit only if goes to include new file into directory target from instalation

almidon_rpm_DATA = \
     almidon.spec

## Directory target

almidon_rpmdir = \
	 $(datarootdir)/doc/almidon/pkg/rpm


EXTRA_DIST = $(almidonprojectbuilddoc_DATA) \
             $(almidon_rpm_DATA)
                               
# Copy all the spec files. Of cource, only one is actually used.
dist-hook:
	for specfile in *.spec; do \
		if test -f $$specfile; then \
			cp -p $$specfile $(distdir); \
		fi \
	done




