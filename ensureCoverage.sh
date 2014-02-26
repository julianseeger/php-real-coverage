#!/bin/sh
# expects a coverage-text report from phpunit as first argument
grep "Classes: 100.00%" $1 > /dev/null
grepResult=$?
if [ $grepResult -ne 0 ]; then
    echo "Coverage is below 100%!"
fi
exit $grepResult