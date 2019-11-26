#!/bin/sh

set -eu

composer normalize --working-dir=./dev-tools ./../composer.json --dry-run
PHP_CS_FIXER_FUTURE_MODE=1 ./dev-tools/vendor/bin/php-cs-fixer fix --ansi --diff --dry-run --verbose
