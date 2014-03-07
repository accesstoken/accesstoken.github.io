---
layout: post
title: "Plotting the frequency distribution using R"
date: 2013-07-03 12:22
comments: true
categories: Tutorial
description: How to plot the frequency distribution using R
keywords: R, frequency, distribution, plot, filter, read, csv, barplot
---

### Introduction

[R](http://www.r-project.org/) is an open source language and environment for statistical computing and graphics. 
It's an implementation of the [S language](http://en.wikipedia.org/wiki/S_(programming_language)) which was developed at Bell Laboratories by John Chambers and colleagues.
R provides a wide variety of statistical and graphical techniques, including linear and nonlinear modeling, classical statistical tests, time-series analysis, classification, clustering, and others. 
It is also an interpreted language and can be accessed through a command-line interpreter: For example, if a user types "2+2" at the R command prompt and press enter, the computer replies with "4".
R is freely available under the GNU General Public License.

### Plotting The Frequency Distribution

####Frequency distribution

A frequency distribution shows the number of occurrences in each category of a categorical variable. 
For example, in a sample set of users with their favourite colors, we can find out how many users like a specific color.

####Data set

Suppose a data set of 30 records including user ID, favorite color and gender:

{% include_code Sample Set lang:text sample.csv %}

####Reading the csv file

Let's start with reading the csv file:

``` text
data <- read.csv(file = 'sample.csv', header = TRUE, sep = ',')
```

The first argument which is mandatory is the name of file. The second argument indicates whether or not the first row is a set of labels and the third argument indicates the delimiter. The above command will read in the csv file and assign it to a variable called "data".

You can use the following command to see the list of column names:

``` text
names(data)
```
which results:

``` text
[1] "ID"     "Color"  "Gender"
```

Or you can use following command to see a summary of the data:

``` text
summary(data)
```

``` text
       ID       Color       Gender  
 792141B: 1   Blue :20   Female: 7  
 795156A: 1   Green: 1   Male  :23  
 795156B: 1   Red  : 7              
 795156C: 1   White: 2              
 795156E: 1                         
 795156G: 1                         
 (Other):24  
```
As you see, the number of occurrences of each color is shown in the summary. 

####Table function

*table()*  uses the cross-classifying factors to build a contingency table of the counts at each combination of factor levels.

``` text
table(data$Color)
```

``` text

 Blue Green   Red White 
   20     1     7     2 
```

####Plotting

Now we can plot it easily using the *barplot* command:

``` text
barplot(table(data$Color))
```

####Save the plot as an image

I can see the plot on my machine, but to put it here on my weblog, I have to save it as an image:

``` text
dev.copy(png, 'freq.png')
dev.off()
```

Here you go...

{%img center /images/freq.png 380 380 %}


####Factor

The *factor* function is used to create a factor (or category) from a vector. 

``` text
factor(data$Color)
```

``` text
[1] Blue  Blue  Blue  Blue  Blue  Blue  Blue  White Red   Blue  Green Red  
[13] Blue  White Blue  Red   Red   Blue  Blue  Blue  Red   Blue  Blue  Blue 
[25] Blue  Blue  Blue  Blue  Red   Red  
Levels: Blue Green Red White
```

Levels is a unique set of values in the vector.

Now, suppose that "Yellow" was also an option for the users but nobody has chosen it as the favourite color. 
We can use the *factor* command to customize the categories:

``` text
factor(data$Color, levels = c('Blue', 'Green', 'Yellow', 'Red', 'White'))
```

``` text
 [1] Blue  Blue  Blue  Blue  Blue  Blue  Blue  White Red   Blue  Green Red  
[13] Blue  White Blue  Red   Red   Blue  Blue  Blue  Red   Blue  Blue  Blue 
[25] Blue  Blue  Blue  Blue  Red   Red  
Levels: Blue Green Yellow Red White
```

Now, we can see Yellow in the frequency distribution:

``` text
table(factor(data$Color, levels = c('Blue','Green','Yellow','Red','White')))
```

``` text
  Blue  Green Yellow    Red  White 
    20      1      0      7      2 
```

And we can see it on the plot:

``` text
barplot(table(factor(data$Color, levels = c('Blue', 'Green', 'Yellow', 'Red', 'White'))))
```

{%img center /images/freq1.png 380 380 %}

if you want to see the percentages instead of the values, you can try this:

``` text
t <- table(factor(data$Color, levels = c('Blue', 'Green', 'Yellow', 'Red', 'White')))
barplot(t / sum(t))
```

{%img center /images/freq2.png 380 380 %}

####Filtering 

Now, let's imagine that we want to plot the frequency distribution of favourite colors for men and women separately. 
The following commands create two subsets of data by filtering the gender and store it to two different variables (Don't forget the comma!):

``` text
men <- data[data$Gender == 'Male',]

women <- data[data$Gender == 'Female',]

```

now we can plot the distributions seperately:

``` text
l <- c('Blue', 'Green', 'Yellow', 'Red', 'White')

barplot(table(factor(men$Color, levels = l, main = 'Men')

barplot(table(factor(women$Color, levels = l, main = 'Women')
```

{%img /images/freq_men.png 380 380 %}{%img /images/freq_women.png 380 380 %}


####Colors and Labels

Do you like colors and labels?! Here you go...

``` text
l <- c('Blue','Green','Yellow','Red','White')

barplot(table(factor(data$Color, levels = l)) , col = c('blue', 'green', 'yellow', 'red', 'white'), xlab = 'Favourite Color', ylab = 'Number Of Users')
```

{%img center /images/freq3.png 480 480 %}



