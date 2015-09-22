#!/bin/bash
# Author: Massoud Seifi, Ph.D. @ MetaDataScience.com

# Count the number of lines in the input file
lines=$(wc -l < "$1")

# Generate unique ids and store them in a temporary file
uids=$(tempfile)
for i in `seq 1 $lines`; do if [ $i == 1 ]; then echo "uid"; else uuidgen -t; fi; done > "$uids"

# Insert the unique ids at the end of each line of the input file
paste --delimiter "," "$1" "$uids"

# Remove the temporary file
rm "$uids"
