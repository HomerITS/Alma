#!/bin/sh
#get /home/libhomer/dropbox/alma/bursar/BURSAR.EXPT.`date +%m_%d_%y`.xml /usr/local/data/Alma/working/bursarOut.xml
cat > /home/dneary/scripts/alma_getbursar.txt << __EOF
mget /home/libhomer/dropbox/alma/bursar/student/BURSARSTU* /usr/local/data/Alma/working/bursarOut.xml
#rm /home/libhomer/dropbox/alma/bursar/student/BURSARSTU*
quit
__EOF

sftp -b /home/dneary/scripts/alma_getbursar.txt libhomer@glue3.uits.uconn.edu

php -f /data/websites/FinancialServices/bin/AlmaBursarParser.php

# cd /usr/local/data/Alma/completed
# remove file from completed if rerun on same day.
rm -rf /usr/local/data/Alma/completed/bursarOut.`date +%m_%d_%y`.sif
mv /usr/local/data/Alma/working/bursarOut.sif /usr/local/data/Alma/working/bursarOut.`date +%m_%d_%y`.sif
# zip patronExtr.zip ALMA_Patron.`date +%m_%d_%y`.xml

cat > /home/dneary/scripts/alma_putbursar.txt << __EOF
put /usr/local/data/Alma/working/bursarOut.`date +%m_%d_%y`.sif /home/libhomer/dropbox/alma/bursar/student/BURSAR.STU-`date +%m_%d_%y`.sif
#put /usr/local/data/Alma/working/bursarOut.`date +%m_%d_%y`.sif /home/libhomer/dropbox/sif-`date +%m_%d_%y`.burs
put /usr/local/data/Alma/working/bursarOut.`date +%m_%d_%y`.sif /home/libhomer/dropbox/sif.burs
put /usr/local/data/Alma/working/bursarOut.xml /home/libhomer/dropbox/alma/bursar/student/BURSAR.STU-`date +%m_%d_%y`-complete.xml
rm /home/libhomer/dropbox/alma/bursar/student/BURSARSTU*
quit
__EOF

sftp -b /home/dneary/scripts/alma_putbursar.txt libhomer@glue3.uits.uconn.edu
mv /usr/local/data/Alma/working/bursarOut.`date +%m_%d_%y`.sif /usr/local/data/Alma/completed
mv /usr/local/data/Alma/working/bursarOut.xml /usr/local/data/Alma/completed-`date +%m_%d_%y`.xml
