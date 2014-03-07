---
layout: post
title: "Lookup Table for Inferring Facebook Account Creation Date From Facebook User ID"
date: 2013-03-14 12:49
comments: true
categories: Facebook
description: Inferring Facebook account creation date from Facebook User ID.
keywords: Lookup table, data set, Facebook, account creation date, account age, graph API, FQL, Facebook post, read_stream permission, Facebook stream, Facebook created_time, Facebook access token, Facebook join date, Facebook User ID, Facebook uid, correlation
---
In my [previous post](http://metadatascience.com/2013/03/11/inferring-facebook-account-creation-date-from-facebook-user-id/), I explained how we can estimate the account creation date of Facebook accounts that have a 15 digit UID without having to call the Facebook API and just based on the user’s Facebook UID. 

Table below shows the correlation between Facebook UID and Facebook Account Creation Date for the sample set that I analysed.
The table is represented in [CSV](http://en.wikipedia.org/wiki/Comma-separated_values) format as follows:

*Facebook UID, Account Creation Date(timestamp), Account Creation Date(date)*.

**Note #1:** To respect the users privacy I hided the last 5 digits of UIDs. You may replace 'x' by '0' and it should not cause any problem.

**Note #2:** For a more accurate result, this table should get updated.

{% include_code Correlation between Facebook UID and Facebook Account Creation Date lang:text fbid_accountage.csv %}