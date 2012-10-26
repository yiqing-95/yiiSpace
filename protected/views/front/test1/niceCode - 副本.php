http://stackoverflow.com/questions/1443960/how-to-implement-the-activity-stream-in-a-social-network/1766371#1766371
down vote
accepted
I use a plain old MySQL table for dealing with about 15 million activities.

It looks something like this:

id             
user_id       (int)
activity_type (tinyint)
source_id     (int)  
parent_id     (int)
parent_type   (tinyint)
time          (datetime but a smaller type like int would be better) 
activity_type tells me the type of activity, source_id tells me the record that the activity is related to. So if the activity type means "added favorite" then I know that the source_id refers to the ID of a favorite record.

The parent_id/parent_type are useful for my app - they tell me what the activity is related to. If a book was favorited, then parent_id/parent_type would tell me that the activity relates to a book (type) with a given primary key (id)

I index on (user_id, time) and query for activities that are user_id IN (...friends...) AND time > some-cutoff-point. Ditching the id and choosing a different clustered index might be a good idea - I haven't experimented with that.

Pretty basic stuff, but it works, it's simple, and it is easy to work with as your needs change. Also, if you aren't using MySQL you might be able to do better index-wise.

For faster access to the most recent activities, I've been experimenting with Redis. Redis stores all of its data in-memory, so you can't put all of your activities in there, but you could store enough for most of the commonly-hit screens on your site. The most recent 100 for each user or something like that. With Redis in the mix, it might work like this:

Create your MySQL activity record
For each friend of the user who created the activity, push the ID onto their activity list in Redis.
Trim each list to the last X items
Redis is fast and offers a way to pipeline commands across one connection - so pushing an activity out to 1000 friends takes milliseconds.

For a more detailed explanation of what I am talking about, see Redis' Twitter example: http://code.google.com/p/redis/wiki/TwitterAlikeExample

Update February 2011 I've got 50 million active activities at the moment and I haven't changed anything. One nice thing about doing something similar to this is that it uses compact, small rows. I am planning on making some changes that would involve many more activities and more queries of those activities and I will definitely be using Redis to keep things speedy. I'm using Redis in other areas and it really works well for certain kinds of problems.

This wasn't a clever or especially interesting solution but it has served me well.

==================================================================================================================================

This is my implementation of an activity stream, using mysql. There are three classes: Activity, ActivityFeed, Subscriber.

Activity represents an activity entry, and its table looks like this:

id
subject_id
object_id
type
verb
data
time
Subject_id is the id of the object performing the action, object_id the id of the object that receives the action. type and verb describes the action itself (for example, if a user add a comment to an article they would be "comment" and "created" respectively), data contains additional data in order to avoid joins (for example, it can contain the subject name and surname, the article title and url, the comment body etc.).

Each Activity belongs to one or more ActivityFeeds, and they are related by a table that looks like this:

feed_name
activity_id
In my application I have one feed for each User and one feed for each Item (usually blog articles), but they can be whatever you want.

A Subscriber is usually an user of your site, but it can also be any object in your object model (for example an article could be subscribed to the feed_action of his creator).

Every Subscriber belongs to one or more ActivityFeeds, and, like above, they are related by a link table of this kind:

feed_name
subscriber_id
reason
The reason field here explains why the subscriber has subscribed the feed. For example, if a user bookmark a blog post, the reason is 'bookmark'. This helps me later in filtering actions for notifications to the users.

To retrieve the activity for a subscriber, I do a simple join of the three tables. The join is fast because I select few activities thanks to a WHERE condition that looks like now - time > some hours. I avoid other joins thanks to data field in Activity table.

Further explanation on reason field. If, for example, I want to filter actions for email notifications to the user, and the user bookmarked a blog post (and so he subscribes to the post feed with the reason 'bookmark'), I don't want that the user receives email notifications about actions on that item, while if he comments the post (and so it subscribes to the post feed with reason 'comment') I want he is notified when other users add comments to the same post. The reason field helps me in this discrimination (I implemented it through an ActivityFilter class), together with the notifications preferences of the user.

share|improve this answer
answered Nov 22 '09 at 22:28

Nicol¨° Martini
1,150820
Nicolo martini i wanted to add reply comment on activity and show it under it, how is it possible with your structure? should i add another table or just use same, if same, then what are your suggestions? ¨C Basit Feb 3 '11 at 5:59
@Nicolo: Great answer! Practical application of concepts scattered here and there. ¨C Tomasz Zielinski Sep 7 '11 at 12:18
How is performance of this implementation? Any tests on large tables? ¨C Joshua Rountree Mar 19 at 13:46
================================================================================================


up vote
2
down vote
There is a current format for activity stream that is being developed by a bunch of well-know people.

http://activitystrea.ms/.

Basically, every activity has an actor (who performs the activity), a verb (the action of the activity), an object (on which the actor performs on), and a target.

For example: Max has posted a link to Adam's wall.

Their JSON's Spec has reached version 1.0 at the time of writing, which shows the pattern for the activity that you can apply.

Their format has already been adopted by BBC, Gnip, Google Buzz Gowalla, IBM, MySpace, Opera, Socialcast, Superfeedr, TypePad, Windows Live, YIID, and many others.

share|improve this answer
====================================================================================================
{
    "published": "2011-02-10T15:04:55Z",
    "actor": {
      "url": "http://example.org/martin",
      "objectType" : "person",
      "id": "tag:example.org,2011:martin",
      "image": {
        "url": "http://example.org/martin/image",
        "width": 250,
        "height": 250
      },
      "displayName": "Martin Smith"
    },
    "verb": "post",
    "object" : {
      "url": "http://example.org/blog/2011/02/entry",
      "id": "tag:example.org,2011:abc123/xyz"
    },
    "target" : {
      "url": "http://example.org/blog/",
      "objectType": "blog",
      "id": "tag:example.org,2011:abc123",
      "displayName": "Martin's Blog"
    }
  }
  
  {
    "items" : [
      {
        "published": "2011-02-10T15:04:55Z",
        "foo": "some extension property",
        "generator": {
          "url": "http://example.org/activities-app"
        },
        "provider": {
          "url": "http://example.org/activity-stream"
        },
        "title": "Martin posted a new video to his album.",
        "actor": {
          "url": "http://example.org/martin",
          "objectType": "person",
          "id": "tag:example.org,2011:martin",
          "foo2": "some other extension property",
          "image": {
            "url": "http://example.org/martin/image",
            "width": 250,
            "height": 250
          },
          "displayName": "Martin Smith"
        },
        "verb": "post",
        "object" : {
          "url": "http://example.org/album/my_fluffy_cat.jpg",
          "objectType": "photo",
          "id": "tag:example.org,2011:my_fluffy_cat",
          "image": {
            "url": "http://example.org/album/my_fluffy_cat_thumb.jpg",
            "width": 250,
            "height": 250
          }
        },
        "target": {
          "url": "http://example.org/album/",
          "objectType": "photo-album",
          "id": "tag:example.org,2011:abc123",
          "displayName": "Martin's Photo Album",
          "image": {
            "url": "http://example.org/album/thumbnail.jpg",
            "width": 250,
            "height": 250
          }
        }
      }
    ]
  }
  
  {
    "published": "2011-02-10T15:04:55Z",
    "actor": {
      "objectType" : "person",
      "id": "tag:example.org,2011:jane"
    },
    "verb": "share",
    "object" : {
      "objectType":"activity",
      "title": "John posted a photo",
      "id": "tag:example.org,2011:abc123",
      "verb": "post",
      "actor": {
        "objectType":"person",
        "id":"tag:example.org,2011:john"
      },
      "object": {
        "objectType":"photo",
        "url":"http://example.org/album/my_fluffy_cat.jpg"
      }
    }
  }