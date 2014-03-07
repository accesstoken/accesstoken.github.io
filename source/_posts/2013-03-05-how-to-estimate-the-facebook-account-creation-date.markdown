---
layout: post
title: "How to estimate the Facebook account creation date"
date: 2013-03-05 18:17
comments: true
categories: Facebook
description: Estimating the Facebook account age by finding the creation date of the oldest post.
keywords: Facebook, account creation date, account age, graph API, FQL, Facebook post, read_stream permission, Facebook stream, Facebook created_time, Facebook access token, Facebook join date
---
Facebook [Graph API](http://developers.facebook.com/docs/reference/api/) and 
[FQL](http://developers.facebook.com/docs/reference/fql/) don't provide you with a simple way of getting the creation date of a Facebook account. 
But if you have a valid Facebook [Access Token](http://developers.facebook.com/tools/explorer) with *'read_stream'* permission, it is possible to estimate the Facebook account creation date
by finding the creation date of the oldest user post. According to the Facebook documentation, 

> each query of the stream table is limited to the previous 30 days or 50 posts, whichever is greater, however you can use time-specific fields such as created_time along with FQL operators (such as < or >) to retrieve a much greater range of posts.

Also you must have *'read_stream'* permission:

> Querying without the *'read_stream'* permission will return only the public view of the data (i.e. data that can be see when the user is logged out).

Here is some code to do that:

{% include_code Estimate the Facebook account age lang:php AccountAge.php %}
