#!/bin/bash
template_website_almidon_demodir = ${datarootdir}/almidon/demo
template_almidon_doc_phpdocdir  = ${docdir}/almidon/phpdoc

phpdoc -t $almidon_doc_phpdocdir/ -f php/db2.class.php, php/db.dal.php, php/db.const.php,$template_website_almidon_demodir/classes/config.ori.php
