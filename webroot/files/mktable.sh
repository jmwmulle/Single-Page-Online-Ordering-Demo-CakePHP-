#!/bin/sh
# pass in the file name as an argument: ./mktable filename.csv
echo "create table $1 ( "
echo "title varchar(255)",
head -1 $1 | sed -e 's/,/ tinyint(1),\n/g'
echo " tinyint(1) );"
