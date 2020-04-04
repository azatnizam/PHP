#!/bin/bash
# Первой строкой в скрипте всегда указывается путь к самой программе


if [[ $1 =~ ^[+-]?[0-9]+([.][0-9]+)?$  ]] && [[ $2 =~ ^[+-]?[0-9]+([.][0-9]+)?$ ]]
then
        echo $[ $1 + $2 ];
else 
      	echo "parameters should be numeric";
fi
