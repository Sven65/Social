# Social
A Social Network thing

This is a twitter/facebook clone combined into one thing, complete with user profiles and timelines.

I originally made this back in 2015 due to boredom and I'm now releasing it.

To get it setup, you need a webserver with Apache 2 and a MySQL server ontop of that.

There's probably some issues in there that I haven't fixed, and I probably won't fix them either.

The images ``light.jpg`` and ``light.png`` are also made by me.

# Setting up the database
To get this to work, you're going to need a MySQL Database server with a database named ``social``. In this database, you're going to need four tables, ``notifications``, ``posts``, ``tags`` and ``users``.

The structure of these will be shown here:

### Notifications

| # | Name  | Type          | Collation | Attributes | Null | Default | Extra          |
|---|-------|---------------|-----------|------------|------|---------|----------------|
| 1 | id    | int(11)       |           |            | No   | None    | AUTO_INCREMENT |
| 2 | toUsr | int(11)       |           |            | No   | None    |                |
| 3 | body  | varchar(1024) |           |            | No   | None    |                |
| 4 | seen  | int(11)       |           |            | No   | None    |                |


### Posts

| # | Name     | Type         | Collation | Attributes | Null | Default | Extra          |
|---|----------|--------------|-----------|------------|------|---------|----------------|
| 1 | id       | int(11)      |           |            | No   | None    | AUTO_INCREMENT |
| 2 | from     | int(11)      |           |            | No   | None    |                |
| 3 | body     | varchar(256) |           |            | No   | None    |                |
| 4 | posted   | varchar(256) |           |            | No   | None    |                |
| 5 | star     | int(11)      |           |            |      |         |                |
| 6 | repost   | int(11)      |           |            |      |         |                |
| 7 | liked_by | text         |           |            |      |         |                |

### Tags

| # | Name | Type          | Collation | Attributes | Null | Default | Extra          |
|---|------|---------------|-----------|------------|------|---------|----------------|
| 1 | id   | int(11)       |           |            | No   | None    | AUTO_INCREMENT |
| 2 | post | int(11)       |           |            | No   | None    |                |
| 3 | tag  | varchar(1024) |           |            | No   | None    |                |

### Users

| # | Name  | Type          | Collation | Attributes | Null | Default | Extra          |
|---|-------|---------------|-----------|------------|------|---------|----------------|
| 1 | id    | int(11)       |           |            | No   | None    | AUTO_INCREMENT |
| 2 | name  | varchar(255)  |           |            | No   | None    |                |
| 3 | pass  | varchar(1024) |           |            | No   | None    |                |
| 4 | salt  | varchar(1024) |           |            | No   | None    |                |
| 5 | posts | int(11)       |           |            |      |         |                |
| 6 | subs  | text          |           |            |      |         |                |
| 7 | email | varchar(1024) |           |            |      |         |                |
| 8 | bio   | text          |           |            |      |         |                |