#!/bin/bash

echo $LOCAL_DIR
echo $TARGET_FTP_PATH

lftp -u $TARGET_FTP_USER,$TARGET_FTP_PASS $TARGET_FTP_HOST << EOF
set ssl:verify-certificate no
mirror -R -c --log="/tmp/lftp.log" "$LOCAL_DIR" "$TARGET_FTP_PATH"
quit
EOF

echo done

