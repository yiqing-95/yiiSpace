#!/bin/bash

rm -rf galleriffic-2.0
rm galleriffic-2.0.zip

mkdir galleriffic-2.0

tar cvf - . | (cd galleriffic-2.0; tar xvf -)

zip -vr galleriffic-2.0.zip galleriffic-2.0/* -x@excludes.txt

rm -rf galleriffic-2.0
