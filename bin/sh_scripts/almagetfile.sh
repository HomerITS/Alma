#!/bin/sh
cat > /home/dneary/scripts/alma_getfiles.txt << __EOF
get /home/libhomer/dropbox/patload /usr/local/data/patload.`date +%m_%d_%y`.sif
rm /home/libhomer/dropbox/patload
quit
__EOF

sftp -b /home/dneary/scripts/alma_getfiles.txt libhomer@glue3.uits.uconn.edu

php -f /data/websites/FinancialServices/bin/fixedToCSV_v2.3.php

cd /usr/local/data/working
zip patronExtr.zip ALMA_Patron.`date +%m_%d_%y`.xml

cat > /home/dneary/scripts/alma_putfiles.txt << __EOF
put /usr/local/data/working/patronExtr.zip /home/libhomer/dropbox/alma/sis
quit
__EOF

sftp -b /home/dneary/scripts/alma_putfiles.txt libhomer@glue3.uits.uconn.edu
mv /usr/local/data/working/patronExtr.zip /usr/local/data/completed

