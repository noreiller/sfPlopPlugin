#!/bin/bash
# @author: Aurélien MANCA
# @description: Prepare the sandbox to the deploy task

find * -type f -exec chmod 644 {} \;
find * -type d -exec chmod 705 {} \;
chmod +x ./symfony
chmod a+rw ./cache/ ./log/