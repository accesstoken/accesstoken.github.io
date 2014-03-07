---
layout: post
title: "Inferring Facebook account creation date from Facebook User ID"
date: 2013-03-11 11:27
comments: true
categories: Facebook
description: Inferring Facebook account creation date from Facebook User ID.
keywords: Facebook, account creation date, account age, graph API, FQL, Facebook post, read_stream permission, Facebook stream, Facebook created_time, Facebook access token, Facebook join date, Facebook User ID, Facebook uid, correlation
---
Calling the Facebook API is a (relatively) slow operation; especially if you have to call it multiple times. 
So, when possible, it is a good idea to get the information you need, without making API calls.

Here I show you how to figure out the *creation date* of a Facebook account without having to call the Facebook API, just based on the user's Facebook UID.

### The Bad Way To Do It

As I explained in [my previous post](http://metadatascience.com/2013/03/05/how-to-estimate-the-facebook-account-creation-date/),
it is possible to estimate the Facebook account creation date by retrieving the date of user's oldest post. 
This method has a couple of draw backs:

**Draw Back #1**: {%img right /images/screenshot_read_stream.png 356 262 read_stream %} You must have *'read_stream'* permission which is an extended Facebook permission to read the user post stream. From a user's point of view, this sounds scarier than the other basic permissions you probably ask for.

**Draw Back #2**: As an extended permission it triggers a second permission screen that dramatically increases the [UX](http://en.wikipedia.org/wiki/User_experience) friction for the users.  (You want *low* friction UX.)

**Draw Back #3**: The overhead of walking the entire post stream to determine age is very costly for the simple piece of information we synthesize. (You have to call the Facebook API over and over and over and over ... again, since the post stream is paginated. I.e., this is at best an O(n) operation, where "n" relates to the user's activity on Facebook.)

### My Search For A Better Way

To overcome these issues I tried an to find an alternative, asynchronous approach.
I was wondering if it is possible to estimate a Facebook account creation date by looking at Facebook User ID.
I couldn't find any official documentation on how Facebook generates a new Facebook user ID and how they are accomplishing that in a scalable fashion.
One answer I could find was from *Jack Lindamood, Software Engineer at Facebook 2008-2012* which I found [here](http://www.quora.com/Facebook-1/How-do-Facebook-use-incremented-IDs-for-both-users-and-Pages):

> 'Lots' of MySQL DBs. Each with their own unique number.  Also, each has an autoincrement table. Then it's just some math on the autoincrement value + unique_number * some_cap_per_db (it's a bit more complicated due to special cases, but that's pretty much how it works). 

Another explanation was from *Justin Mitchell, former engineering manager*. He explains [here](http://www.quora.com/Facebook-Company-History/What-is-the-history-of-Facebooks-user-ID-numbering-system) the history of Facebook user ID numbering system:

> Facebook's user ID schema reflects the history of the site as it transitioned from a single-server single-school operation to 400 million users.  User ID assignment has gone through several phases, notably:
> 
> Harvard only.  Facebook (or thefacebook.com, as it was called back then) was opened up to Harvard running off a single box that had mysql and apache.  IDs were auto-incremented, starting at 4 (hi Zuck).
> 
> Other schools.  Other schools were initially completely separate sites, operating on their own boxes.  IDs were still auto-increment per SQL box, but each server/school had a different prefix.  For instance, all Columbia IDs are between 100000-199999 and all Stanford IDs are between 200000-299999.  You can determine what school any early Facebook user attended based on his or her user ID.
> 
> High schools. Someone must have figured out that this ID system didn't scale very well, so Facebook changed its DB layout when high schools were introduced.  While all the college users maintained their current DB, high school users were randomly assigned to one of many many high school DBs.  These users IDs hash to the correct database, rather than simply being floor(ID / 100000).
> 
> Open registration.  Facebook maintained a similar layout once open reg was launched, except the new databases weren't signified as "high school."
> 
> 64 bit.  Given Facebook's growth rate, it was estimated that the entire world would be on the site by 2011, overflowing 32-bit space.  While we considered limiting the site to the first 4-billion people to register and lobbying governments to reduce the world's population, the growth team pushed pretty hard to just increase the ID space to 64-bit.

### Using Facecbook UIDs For Predictions

So it seems that new Facebook IDs are 64 bits and contain 15 digits.
There is a [post](https://developers.facebook.com/blog/post/45/) dating from October 2007 that mentions that Facebook had plans to do this long time ago but
according to this [post](https://developers.facebook.com/blog/post/226/) from May, 2009, Facebook was going to release 64 bit user IDs back to 2009.

I studied the correlation between Facebook User ID and Account Creation Date for a *tiny sample set* of 77 Facebook accounts. 41 accounts of this sample set had a user ID containing 15 digits and for the rest the user ID has less than 15 digits. Figures below illustrate this correlation seperately for 64 bit UIDS (left) and old style UIDs (right).

{%img /images/fbid_age_15.png 390 390 uid==15 %}{%img /images/fbid_age_less.png 390 390 uid<15 %}

The graph on the left is for the new(er) Facebook UIDs. The graph on the right is for the old style Facebook IDs. You can see that the correlation between Facebook UID and its *creation date* is **a lot** better for the new(er) Facebook UIDs than the old ones.

Or in other words, as we observe, there is an interesting correlation between Facebook User ID and Account Creation Date for 64 bit user IDs (see figure on left).
Also in this sample set, old UIDs are more than 800 days old (see figure on right). The overlap between two graphs might be a period that Facebook was moving from old UIDs to 64 bit ones. 

**Therefore as an alternative approach to estimate the Facebook account creation date, we may leverage the monotonically increasing property of 64 bit Facebook user IDs and create a table of bounds that would give us at least a quarterly estimate on the creation date for the account - an appropriate level of granularity for this purpose.**
Taking this approach will reduce the number of permissions your application need and dramatically decrease the amount of processing time and remove a variable around the elapsed time to deliver a response.

<span style="color:red"><b>Update (March 14, 2013):</b></span> 
See [here](http://metadatascience.com/2013/03/14/lookup-table-for-inferring-facebook-account-creation-date-from-facebook-user-id/) 
to download the data set.