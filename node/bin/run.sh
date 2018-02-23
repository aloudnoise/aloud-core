#!/bin/sh

COMMAND1="npm install"
COMMAND2="npm restart"
COMMAND3="npm start"
COMMAND4="forever list"

PREF="  ->  "

OS=`uname`
KERNEL=`uname -r`
MACH=`uname -m`

echo "SYSTEM\n $PREF ${OS} ${KERNEL} ${MACH}\n"

if $($COMMAND1 >> /dev/null  2>&1); then
  echo "STAGE\n $PREF INSTALLATION $PREF COMPLETE\n"
else
  echo "STAGE\n $PREF INSTALLATION $PREF ERROR check npm-debug.log\n"
fi

if $($COMMAND2 >> /dev/null  2>&1); then
  echo "STAGE\n $PREF RESTART      $PREF COMPLETE\n"
else
  $($COMMAND3 >> /dev/null  2>&1)
  echo "STAGE\n $PREF START        $PREF COMPLETE\n"
fi

$COMMAND4
