#!/usr/bin/env bash

usage="Usage: ./export.sh <dest dir>"
if [ ! -f "beerxml-shortcode.php" ]; then
    echo "Could not find beerxml-shortcode.php, are you sure this is the right source directory?"
    exit
fi

dest="$1"
if [ $# -eq 0 ]; then
    echo "No arguments supplied"
    echo $usage
    exit
fi

if [ ! -d "$dest" ]; then
    echo "$dest is not a valid directory"
    echo $usage
    exit
fi

cp beerxml-shortcode.php $dest
cp uninstall.php $dest
cp readme.txt $dest
cp license.txt $dest
cp -R includes $dest
cp -R languages $dest

echo "Successfully exported to $dest"
