# PhpBBForumBot
Automate Tasks on a phpbb forum

Example Use:
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
