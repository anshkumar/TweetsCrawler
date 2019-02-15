Tweets Crawler Search Engine 	

### 1. Introduction
This is the project I’ve been assigned:

> Microblog Search Engine: 
> a user wants to search microblogs like Tweets.
> You have to build a system that gathers a large collection of tweets
> related to the current USA Elections and enable to search them. The
> system has to provide the best interface for searching, browsing, and
> presenting the data to the user. The system should also have the

The main aim of this project is to crawl a large dataset of tweets and allow a
user to search them through a user interface for browsing through all the
results. The additional feature is to implement a feedback relevance system in
order to rank the tweets for relevance depending on the choice of the user
whether to signal a tweet as more relevant or less.

### 2. Challenges of the project

In order to deal with the requirements for developing this project, a few challanges have to
be faced up to.
The first obviously is the difficulty to crawl twitter.com using traditional approaches. The
robots.txt file already suggests how the twitter webpages have been blocked to crawlers as
also other social networks (i.e. Facebook, Instagram, etc..) have also done over the years.
This is the robots.txt file of Twitter, regarding all the private crawlers excluding the ones
from Google, Yandex and Microsoft search engines.


```
# Every bot that might possibly read and respect this file.
User-agent: *
Allow: /*?lang=
Allow: /hashtag/*?src=
Allow: /search?q=%23
Disallow: /search/realtime
Disallow: /search/users
Disallow: /search/*/grid
Disallow: /*?
Disallow: /*/followers
Disallow: /*/following
Disallow: /account/not_my_account
Disallow: /oauth
Disallow: /1/oauth
Disallow: /i/streams
Disallow: /i/hello
# Wait 1 second between successive requests. See ONBOARD-2698 for details.
Crawl-delay: 1
```

As it results there are a few pages left to crawl, but hopefully enough to test a system and
get some results.
Therefore — as most people also suggest online — the best approach to crawl Twitter and
analyse large quantities of datas as many companies do for social or politics analysis is to
use the Twitter APIs, which provide a series of tools (where most interesting and useful
ones only for Enterprise accounts) to crawl tweets and not only.
The other main challange to solve once a large dataset of tweets is obtained is to index
them correcly and parse the data to obtain the desidered results.
In the case of this project it was all about the coming US elections in 2020. Many people
tweet about them, but how many are really relevant as data, given that nowadays on
Twitter also many companies advertise their products and services?

### 3. Crawling tweets with Nutch

In order to obtain tweets and given the compulsory requirements of the project I’ve used
Nutch to crawl the data from Twitter.
As mentioned before the best approach that nowadays everyone uses is through the
Twitter APIs, as much as othe social networks provide in order to grab correcly data from
their servers.
But I accepted the challange and started crawling with Nutch.
The approach I used is the follows:
in the beginning I started crawling tweets from specific Twitter accounts, such as @POTUS
(US presidential account), @HouseDemocrats, @SenateDems, @StateDept,
@realDonaldTrump, @SenateGOP, @GOP; this can be done in Nutch setting the urls in the
seed file.
But then, after crawling many pages and testing various results and options, I realized the
best approach to obtain an interesting dataset of tweet regarding the coming US elections
was to crawl specific hashtags. The best which I choose (I could have used more, but it
was enough for testing purposes) are the following: #uselections, #uselections2018,
#2020Elections.
Therefore the seed file would have these URLs

```
https://twitter.com/search?q=%23uselections
https://twitter.com/search?q=%23uselections2018
https://twitter.com/search?q=%232020Elections
```

At this point other problems started to come up, the main one are the resulting tweets
being crawled: once started crawling using the Nutch command:

```
./bin/crawl -s urls/ crawl 3
```

where in this case 3 is the depth of the crawl, numerous useless pages came up, the main
way to tackle this problem was to use the regex system of Nutch but then numerous other
related tweets would have been removed from the results; therefore in the end I choose to
crawl all the pages and on a later stage to index and parse the correct ones I needed.
The main reason to this (as it will be better exaplained later) is that Twitter limits the
number of tweets crawled in general (also using the Twitter APIs) and when using
traditional technologies such as Nutch the tweets crawled per user was around 20, luckily I
managed to crawl enough pages so that in Solr there were 7876 pages indexed from
Twitter, but not all contained tweets...

### 4.Indexing results with Solr

At this point with enough pages crawled with Nutch I connected it with Solr and send all
the pages to be indexed, this can be done easily using the command:

```
bin/nutch index crawl/crawldb/ -linkdb crawl/linkdb/ crawl/segments/* -
filter -normalize -deleteGone
```

The resulting 7876 pages indexed — I could have crawled more, but the I focused mainly
to clean the data and have the correct results displayed — had a few more issues to be
solved. Querying all the results crawled shown the following results, in this case a JSON:

```json
{
    "responseHeader":{
        "status":0,
        "QTime":0,
        "params":{	
    		"q":"content:elections"}},
    "response":{"numFound":352,"start":0,"docs":[
    {
        "tstamp":"2018-12-06T13:48:34.539Z",
        "digest":"ebf21152a4b671a8d7e3649095c5c267",
        "boost":0.0010260198,
        "id":"https://mobile.twitter.com/hashtag/elections?src=hash",
        "title":"Search Twitter - #elections",
        "url":"https://mobile.twitter.com/hashtag/elections?src=hash",
        "_version_":1619122974726553600,
        "content":"Search Twitter - #elections\nLog in\nSign up\nBy using
    Twitter’s services you agree to our Cookie Use and Data Transfer outside
    the EU. We and our partners operate globally and use cookies, including
    for analytics, personalisation, and ads.\nSearch\nRefresh\nEnter a topic,
    @name, or fullname\nElectionsNB\n@ ElectionsNB\nVA Dept of Elections\n@
    vaELECT\nMass. Elections\n@ VotingInMass\nView more people\nDeals24*7\n@
    7Deals24\nDec 4\nHow many of you following #Telangana Elections here ? Let
    us know your prediction ..Let's see the closest match #Telangana\n#
    Elections\nView details\n·\nTOLOnews\n@ TOLOnews\n1h\n# Elections - IEC
    issues a declaration on the electoral complaints commission's decision to
    invalidate Kabul votes. The declaration says the decision on Kabul votes
    is illegal and that it is aimed at damaging the electoral process.\nView
    details\n·\nMallesh Avuti\n@ MalleshAvuti\n4h\nGood Afternoon Sir, #
    Elections to TSLA -2018,Model Polling Station No.123 ,Meeseva, Muncipality
    in 73-Narayanpet A/C
    .. more tweets ..
    },
{ .. more groups of tweets .. }
```

As it can be noticed the results need to be parsed a lot in order to extract the tweets.
The best approach was to query all the results to the following one:

```
query = content:elections
```

In this way only the tweets and the pages (to clean up all the useless other pages related
to Twitter and not only), containing the word “elections” — that’s the reason also why I
choose the above hastags — in their content results would have been displayed.

### 5. Parsing

Once I had the indexed results in Solr, I started developing a sketchy web interface in
HTML and making a GET request to the Solr system

```
http://localhost:8983/solr/nutch/select?q=content:elections&rows=10000
```

The main aim at this point was to clean up the resulting JSON from Solr and display the
tweets correctly.
In the end I decided to develop everything in PHP and therefore I started parsing the data,
which took quite some time given the structure of the JSON and the need to display the
tweets one after the other related to the US elections.
In order to parse the data I’ve used some handy PHP functions to decode the JSON and
iterate through it and saving in the end all the tweets into a single array.
The source code can be found in the index.php file.
Once I had all the tweets saved in this data structure I was able to display them in HTML
and then prettify everything with CSS. With 7876 pages indexed I was able to display
correcly 4199 tweets in total.

### 6. Conclusion

The project was definitely challanging, given especially the fact of approaching it using only
Nutch and Solr. The other feature for the Relevance Feedback was another difficult part.
Solr as now doesn’t provide an efficient way to handle relevance feedbacks from users, 
hence it still lacks this feature that will be implemented.