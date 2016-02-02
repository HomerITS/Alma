#!/bin/sh
#get /home/libhomer/dropbox/alma/bursar/BURSAR.EXPT.`date +%m_%d_%y`.xml /usr/local/data/Alma/working/bursarOut.xml
cat > /home/dneary/scripts/alma_getNONbursar.txt << __EOF
mget /home/libhomer/dropbox/alma/bursar/nonstudent/BURSARNON* /usr/local/data/Alma/working/NONbursarOut.xml
rm /home/libhomer/dropbox/alma/bursar/nonstudent/BURSARNON*
quit
__EOF

sftp -b /home/dneary/scripts/alma_getNONbursar.txt libhomer@glue3.uits.uconn.edu

php -f /data/websites/FinancialServices/bin/AlmaNONBursarParser.php

# cd /usr/local/data/Alma/completed
# remove file from completed if rerun on same day.
rm -rf /usr/local/data/Alma/completed/NONbursarOut.`date +%m_%d_%y`.csv
mv /usr/local/data/Alma/working/NONbursarOut.csv /usr/local/data/Alma/working/NONbursarOut.`date +%m_%d_%y`.csv
# zip patronExtr.zip ALMA_Patron.`date +%m_%d_%y`.xml

cat > /home/dneary/scripts/alma_putNONbursar.txt << __EOF
put /usr/local/data/Alma/working/NONbursarOut.`date +%m_%d_%y`.csv /home/libhomer/dropbox/alma/bursar/nonstudent/NONSTU-`date +%m_%d_%y`.csv
put /usr/local/data/Alma/working/NONbursarOut.xml /home/libhomer/dropbox/alma/bursar/nonstudent/NONSTU-`date +%m_%d_%y`-complete.xml
quit
__EOF

sftp -b /home/dneary/scripts/alma_putNONbursar.txt libhomer@glue3.uits.uconn.edu
mv /usr/local/data/Alma/working/NONbursarOut.`date +%m_%d_%y`.csv /usr/local/data/Alma/completed
mv /usr/local/data/Alma/working/NONbursarOut.xml /usr/local/data/Alma/completed-`date +%m_%d_%y`.xml
