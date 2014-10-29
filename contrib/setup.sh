#!/bin/sh

cp contrib/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
mkdir www/cache
chown -R :www-data www/cache
chmod -R 0775 www/cache
