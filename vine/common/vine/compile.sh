#!/bin/bash

rm vine.js

for i in `ls -a js/*.js*`; do
    echo "item: ${i}"
    cat ${i} >> vine.tmp
done

mv vine.tmp vine.js
