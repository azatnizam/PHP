#!/bin/bash

re='^[+-]?[0-9]+([.][0-9]+)?$'

if ! [ $# -eq 2 ]
then
  echo Скрипт работает с двумя параметрами.
  exit 0;
fi

if ! [[ $1 =~ $re ]] || ! [[ $2 =~ $re ]]; then
  echo Один или оба параметра не являются числами.
  exit 0;
fi

echo "Сумма чисел $1 и $2:"
echo "$1 + $2" | bc

