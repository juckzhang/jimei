#!/bin/sh
cd /mnt/data/openresty/htdocs/jimei/console
process_num=$(ps -ef | grep 'php yii.php meal/run' | grep -v grep | wc -l)

echo "process num ${process_num}"
if [ $process_num -le 0 ]; then
        cd ${run_path} && nohup php yii.php meal/run&
fi