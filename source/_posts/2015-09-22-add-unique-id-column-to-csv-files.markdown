---
layout: post
title: "Add Unique ID column to CSV Files"
date: 2015-09-22 15:23:14 -0700
comments: true
sharing: true
footer: true
categories: Tutorials
description: How to add a unique id to the end of each line of a CSV file/
---
If you want to add a unique id column to the end of a CSV file, you can simply do it via a bash script following the steps below:

- Count the number of lines in the input file using `wc` command and save it to a variable.
- Create a temoprary file using `tempfile` command.
- Using `uuidgen` command, generate n(=*#lines*) unique ids and save them to the temporary file.
- Merge the input file with the temporary file containing unique ids using `paste` command.
- Remove the temporary file.

{% include_code Add unique ids to a CSV file lang:bash gen_uid.sh %}

For example, running the script on the sample CSV file below:

{% include_code Sample CSV file lang:text test.csv %}

``` bash
$./gen_uid.sh test.csv
```

The output will be:

```
Mike,Smith,29,e802dbee-617a-11e5-9c46-0862664fe7a7
Jane,Clark,18,e802ef30-617a-11e5-9c46-0862664fe7a7
Brian,Jones,21,e80302e0-617a-11e5-9c46-0862664fe7a7

```


If first line of your CSV file is the column headings, you can use code below:

{% include_code Add unique ids to a CSV file with headers lang:bash gen_uid_header.sh %}

{% include_code Sample CSV file with headers lang:text test_header.csv %}

``` bash
$./gen_uid_heade.sh test_header.csv
```

```
first_name,last_name,age,uid
Mike,Smith,29,76de1cc4-617c-11e5-9c46-0862664fe7a7
Jane,Clark,18,76de2de0-617c-11e5-9c46-0862664fe7a7
Brian,Jones,21,76de3f2e-617c-11e5-9c46-0862664fe7a7
```