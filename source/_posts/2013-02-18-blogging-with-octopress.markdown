---
layout: post
title: "Blogging With Octopress"
date: 2013-02-18 13:18
comments: true
sharing: true
footer: true
categories: Tutorials
description: "A tutorial for how to use Octopress for blogging, how to get Octopress working on GitHub, and how to map your custom domain to your GitHub pages."
keywords: "octopress, tutorial for octopress, blogging with octopress, octopress on ubuntu, octopress on github, map domain to github pages, add page to octopress, math equation on blog, latex on blog, install ruby, setup octopress, disqus, disqus on octopress, comments octopress"
---
{% img right /images/octopress.png 227 227 Octopress %}
[Octopress](http://octopress.org/) is a “blogging framework for hackers” according to its author, "Brandon Mathis". 
It is built in Ruby and based on the static web-site generator 
[Jekyll](http://jekyllrb.com/) which powers [GitHub Pages](http://pages.github.com/). 
I tried setting up Octopress (on GitHub) by following various tutorials I found online. 
Not all of them worked properly.
So, now that I got it working, this tutorial details what worked for me; 
and how I was able to get Octopress working on GitHub.

### Install Octopress

Here you can find instructions on how to install Octopress on Ubuntu (I currently use version 12.10):

* #### Install [Git](http://git-scm.com/) using the guide [here](http://git-scm.com/book/en/Getting-Started-Installing-Git).

* #### Install Ruby 1.9.3 using the commands below.

``` bash
cd
git clone git://github.com/sstephenson/rbenv.git .rbenv

echo 'export PATH="$HOME/.rbenv/bin:$PATH"' >> ~/.bashrc
echo 'eval "$(rbenv init -)"' >> ~/.bashrc
source ~/.bashrc
git clone git://github.com/sstephenson/ruby-build.git ~/.rbenv/plugins/ruby-build

rbenv install 1.9.3-p194
rbenv rehash
rbenv global 1.9.3-p194
```

Run `ruby --version` to be sure is Ruby 1.9.3 installed properly.

Note that these instructions are a bit different from those given in
[Octopress Documentation](http://octopress.org/docs/setup/rbenv/):
I changed *.bash_profile* to *.bashrc* and *1.9.3-p0* to *1.9.3-p194* to make it work.

* #### Setup Octopress

``` bash
# Get Octopress
git clone git://github.com/imathis/octopress.git octopress

# Install dependencies.
cd octopress
gem install bundler
rbenv rehash
bundle install

# Install the default Octopress theme.
rake install
```

---
<br/>

### Deploying to GitHub Pages

* #### Create a GitHub Account
It has a very easy <a href="https://github.com/signup/">signup</a> process. Be creative to choose a cool username (My username is accesstoken. Is it cool?!). It would be good for your reputation if you can use the same username everywhere, e.g. on Twitter (You can follow me on Twitter with the same username: [http://twitter.com/accesstoken](http://twitter.com/accesstoken)).

* #### Create a GitHub repository for the blog
Create a new GitHub repository and name the repository with your user name: *[your-username].github.com*
	

* #### Generate a SSH key
Create a SSH key and add it to your GitHub account using the instructions [here](https://help.github.com/articles/generating-ssh-keys).

* #### Deploy
Now, run the following command:

``` bash
rake setup_github_pages 
```

It will ask you to enter the read/write url for your repository. 
Enter the SSH read/write url for your repository which should be like *git@github.com:username/username.github.com.git*.

Continue by running the following commands:

``` bash
rake generate
rake deploy
```

Commit everything in Git:

``` bash
git add .
git commit -m 'initial commit'
git push origin source
```

Now, you should have two branches, *source* and *master* on your GitHub repository. 
The *source* branch contains the files that are used to generate the blog and the *master* contains the blog itself. 

After a few minutes, you should be able to see the default Octopress page here: *http://[your-username].github.com/*. Hooray!

---
<br/>

### Start blogging

* #### Configure your blog

You can find a simple guide [here](http://octopress.org/docs/configuring/) for configuring your Octopress blog.

* #### Create a new post

``` bash
rake 'new_post[Title]'
```

This will tell you the name of the *Markdown* file for your new posting. Just open the file and start typing.

``` html
---
layout: post
title: "Blogging With Octopress"
date: 2013-02-18 13:18
comments: true
categories: Tutorials
description: "A tutorial for how to use Octopress for blogging."
keywords: "octopress, tutorial for octopress, blogging with octopress"
---
<h1>Hello World!</h1>
This is a link to <a href='http://octopress.org/'>Octopress</a>.
```
You can use [Markdown syntax](http://daringfireball.net/projects/markdown/basics).
Markdown allows you to write using an easy-to-read, easy-to-write plain text format, then convert it to structurally valid XHTML (or HTML).

```
# Hello World!
This is a link to [Octopress](http://octopress.org/).
```

You can also [write math equations](http://www.idryman.org/blog/2012/03/10/writing-math-equations-on-octopress) 
(e.g. 
$$J_\alpha(x) = \sum\limits_{m=0}^\infty \frac{(-1)^m}{m! \, \Gamma(m + \alpha + 1)}{\left({\frac{x}{2}}\right)}^{2 m + \alpha}$$
) 
using [kramdown](http://kramdown.rubyforge.org/) and [MathJax](http://www.mathjax.org/) (Update: see [here](http://www.lucypark.kr/blog/2013/02/25/mathjax-kramdown-and-octopress/)).


Now, run the command below to generate posts and pages into the public directory.

``` bash
rake generate
```

If you want to see a preview of your blog before publishing it to the server, run the command below.

``` bash
rake preview
```

You will see a message like this:

*&gt;&gt;&gt; Compass is polling for changes. Press Ctrl-C to Stop.*

Keep it listening (don't press Ctrl-C), open your browser and open *http://localhost:4000* to preview your post.

When you are happy with your new post, you can publish it to the server.

``` bash
rake deploy
```

* #### Create a new Page
If you want to create, for example, the “about” page for your blog, you need to run the command below.

``` bash
rake new_page["about"]
```

This will create a new file at *source/about/index.markdown* that you can edit to write about yourself. 
Running `rake generate` command will generate *public/about/index.html* from the *index.markdown*
and `rake deploy` will push changes to the server. 

You can add an “About” link in the navigation bar for this page. 
Simply, edit the file *source/_includes/custom/navigation.html* and add

``` html
<li><a href="/about">About</a></li>
```

Generate and deploy can be done in a single command:

``` bash
rake gen_deploy
```

* #### Enable comments

Create a [disqus](http://disqus.com/) account and add your disqus short name in *_config.yml* to enable comments on your blog. 

Done!

---
<br/>

### Mapping your custom domain to your GitHub pages

Here you can find instructions on how to map your custom domain to your blog:

* #### Register your domain
	
	I registered my domain *metadatascience.com* at [GoDaddy.com](http://godaddy.com) but you can use any domain name registrar.

* #### Configure the DNS

	Go to DNS Manager on the control panel of your domain. Point your host to *204.232.175.78*.

* #### Map your domain to your GitHub pages

	I used the commands below to map my domain *metadatascience.com* to my GitHub pages at *accesstoken.github.com*.
	Replace *metadatascience.com* with your domain name and *accesstoken* with your username on GitHub in the code below.

``` bash
echo 'metadatascience.com' >> source/CNAME
git add source/CNAME
git commit -m "map accesstoken.github.com to metadatascience.com"
git push origin source

# push changes to master 
rake generate
rake deploy 
```

Any time you make a DNS (domain name server) change it might take around 24-48 hours to complete, so be patient!
