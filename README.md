# PhpBBForumBot
Automate Tasks on a phpbb forum

###Example Bot Manager
<a href="http://imgur.com/LPBoi8n"><img src="http://i.imgur.com/LPBoi8n.png" title="source: imgur.com" /></a>

###Important
Make sure to add these directories to a folder named tmp. This should be in the root directory of the bot.
<a href="http://imgur.com/haOjr9N"><img src="http://i.imgur.com/haOjr9N.png" title="source: imgur.com" /></a>

###Example Use:
```

$bot = new forumBot();

//Login
$bot->username = "example"
$bot->password = "password1234"
$bot->login();

//create a thread
$bot->mode = "post";
$bot->subject = "This is my first thread";
$bot->msg = "Wow did I just post that with the forum bot?";
$this->posting();

```
