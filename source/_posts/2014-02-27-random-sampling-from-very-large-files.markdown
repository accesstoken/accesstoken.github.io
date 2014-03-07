---
layout: post
title: "Random sampling from very large files"
date: 2014-02-27 14:49
comments: true
sharing: true
footer: true
categories: Tutorials
description: Suppose we want to randomly select k lines from a large text file containing hundreds of millions of lines. We explain some approaches to do it efficiently.
keywords: random sampling, random sample, reservoir sampling, python, large files, random lines, linecache, benchmark
---
### Random Sampling

[Random sampling](http://en.wikipedia.org/wiki/Sampling_(statistics)) from a set of entities means any entity has the same chance of selection as any other such entities.
Suppose we want to randomly select $k$ lines from a large text file containing hundreds of millions of lines. We desire that the probability of being selected be the same for every line in the file.

### Algorithm 1

The first approach which comes in mind is to 

- Count the number of lines in the file, 
- Create a sorted random set of $k$ integers between 1 and number of lines in the file,
- Iterate over the random integers and read the file line by line. 
Pick the line if the line number matches one of the the random integers.


This algorithm in Python is shown below.

{% include_code Random Sampling 1 lang:python random_sampler1.py %}

### Algorithm 2: Reservoir sampling

As you see, in previous algorithm, we scan the file two times. First time for counting the number of lines in the file, and second time to select random lines. 
There are some algorithms which even work without knowing in advance the total number of items. One classical algorithm form Alan Waterman called [Reservoir sampling](http://en.wikipedia.org/wiki/Reservoir_sampling) is exposed in the second volume of Donald Knuth's "[The Art of Computer Programming](http://en.wikipedia.org/wiki/The_Art_of_Computer_Programming)". 

Suppose we want to select $k$ items from a set of items. We start by filling the “reservoir” with the first $k$ items, and then for each $i^{th}$ item remaining in the set, we generate a random number $r$ between $1$ and $i$. If $r$ is less than $k$, we replace the $r^{th}$ item of the reservoir with the $i^{th}$ item of the set. We continue processing items until we reach the end of the set. 


{% include_code Random Sampling 2 lang:python random_sampler2.py %}

It is easy to prove by induction that this approach works and each line has the same probability of being selected as the other lines:

Suppose we need to collect a random sample of $k$ items from a list of items coming as an online stream. We desire that after seeing $n$ item, each item in the sample set had $\frac{k}{n}$ chance to be there.

For example, suppose $k=10$. According to the algorithm, the first $10$ items go directly to the reservoir. So for them, the probability of being selected is $\frac{10}{10} = 1 \checkmark$.

Now, suppose the $11^{th}$ item comes. The desired probability is now $\frac{k}{n} = \frac{10}{11}$. We have:

- According to the reservoir sampling algorithm above, the probability of $11^{th}$ item to being selected is $\frac{10}{11} \checkmark$.

- For the items already in the reservoir, the chance of being in the sample set and also remaining in the sample set after seeing the $11^{th}$ item, is their previous probability to be there, multiple the probability of not being replace by the $11^{th}$. So we have:

	Pr = Probability that a selected item remains in the reservoir

	= Previous probability to be there * Probability of not being replaced

	= Previous probability to be there * ( 1 - Probability of being replaced by $11^{th}$ item)

	The chance that an item in the reservoir being replaced with $11^{th}$ item is the probability of $11^{th}$ item to be selected, which is $\frac{10}{11}$, multiple the probability of being the replacement candidate between 10 items, which is $\frac{1}{10}$. So we have: $$Pr = \frac{10}{10}*(1-\frac{10}{11}*\frac{1}{10})=\frac{10}{11} \checkmark$$.

Likewise, for the $12^{th}$ item we have:

- Probability of $12^{th}$ item to being selected is $\frac{10}{12} \checkmark$.

- For the items already in the reservoir: $$Pr = \frac{10}{11}*(1-\frac{10}{12}*\frac{1}{10})=\frac{10}{12} \checkmark$$

And this can be extended for the $n^{th}$ item. Although reservoir sampling is an interesting approach but it is too slow for our problem here.

### Algorithm 3

There is another interesting approach when the lines have approximately the same length (for example, we deal with a huge list of email addresses). In this case, there is a correlation between line numbers and the file size. So, we can use the algorithm below:

{% include_code Random Sampling 3 lang:python random_sampler3.py %}

Basically, we get the file size. Create a sorted random set of k random positions in the file (between 1 and the file size). For each random position, we seek that position, skip a line, and put the next line to the sample set.

### Benchmark

The table below shows the elapsed time for selecting 1000 lines from a large (~ 40M lines) and a very large file(~ 300M lines) for each algorithm. We see that the algorithm 3 is much faster. As I mentioned before, the only assumption is that the lines should have approximately the same length.


| Algorithm           | File 1 (~ 40M lines)  | File 2 (~ 300M lines) |
| :------------------ | --------------------: | ---------------------:|
| random_sampler1.py  | 6.641s                | 1m14.184s             |
| random_sampler2.py  | 50.406s               | 6m51.078s             |
| random_sampler3.py  | 0.019s                | 3.119s                |


