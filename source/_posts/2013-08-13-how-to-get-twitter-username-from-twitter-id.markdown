---
layout: post
title: "How to get Twitter username from Twitter ID"
date: 2013-08-13 11:18
comments: true
categories: Twitter
description: How to get Twitter username from Twitter ID without using the Twitter API
keywords: Twitter, API, Username, Screen name, handle, User ID, access token, rate limit, redirect_by_id
---

### Twitter ID and Username

The Twitter ID is a unique number identifying an account on Twitter. 
Upon registration, users can also choose a username (a.k.a. screen name or @handle).
However an account can never change its Twitter ID, but it can change its username.


### Getting Twitter username from Twitter ID using the Twitter API (rate limited)

I was trying to lookup some Twitter IDs and find out what their corresponding usernames are. My first approach was using the code below using 
`http://twitter.com/users/show/[ID]?format=json` as the endpoint:

{% include_code Getting Twitter username from Twitter ID using the Twitter API (limited) lang:php TwitterUserIdToScreenName_limited.php %}

This code calls Twitter API which has two issues:

1. It needs an access token after the release of Twitter API version 1.1.

2. The number of requests is limited to 150 requests per hour.

### Getting Twitter username from Twitter ID without using the Twitter API (scalable)

I tried another approach using `http://twitter.com/account/redirect_by_id?id=` which doesn't call the Twitter API and consequently has no rate limit. This approach worked fine for me and I could easily look up thousands of Twitter IDs. Here is the code:

{% include_code Getting Twitter username from Twitter ID without using the Twitter API lang:php TwitterUserIdToScreenName.php %}

Usage Example:

```
> echo 1180576736 | php TwitterUserIdToScreenName.php
1180576736,AccessToken
```
or you can lookup a list of IDs:

```
> cat list_IDs | php TwitterUserIdToScreenName.php
```


