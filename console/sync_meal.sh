#!/bin/sh
process_num=$(ps -ef | grep 'php yii.php meal/run' | grep -v grep | wc -l)

echo "process num ${process_num}"
if [ $process_num -le 0 ]; then
        nohup php yii.php meal/run &
fi