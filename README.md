replicate-sequences
===================

a small and simple tool to get the correct replicate sequence id of [OpenStreetMap's replation-diffs](http://planet.osm.org/replication/) from a timestamp.
Currently deployed on a server near you: https://osm.mazdermind.de/replicate-sequences/


API
===

Besides the Web-Form the tool can handle the following API-Call:
```
https://osm.mazdermind.de/replicate-sequences/?2013-01-01T10:00:00Z
```

This can be used to always fetch the latest state.file in conjunction with some unix-foo:
```
curl "https://replicate-sequences.osm.mazdermind.de/?`date --utc "+%FT%TZ"`"
```

Setting up you own
==================

Setting up your own copy of this service is quite easy. Check out into a PHP-Enabled docroot. Create a MySQL-Database and a User that has `INSERT` and `SELECT` permissions on it. Copy `conf.php.tpl` to `conf.php` and fill in your database name and user credentials. You also MUST FILL IN an E-Mail-Adress where admins of planet.osm.org can contact you, in case this script runs crazy on your server.
Now populate your database with `schema.sql` to get the empty tables. You could now start running `update.php` and it will download >750000 `state.txt`-files to populate your database. This would take at least one complete day. Instead, i'd suggest to also import `initial.sql.gz`, which already contains the first 750000 state-files and pre-loads your database with this information. You can then use `update.php` to stay on track.


Running with Docker
===================
This tools consists of two Containers, both running the same image ([mazdermind/osm-replicate-sequenced](https://hub.docker.com/r/mazdermind/osm-replicate-sequenced/) but with different commands.
When the Image is run with "apache2-foreground" it will serve Records from the MySQL-Database. When it is run without a command it will start fetching Sequence-Files from the OSM Repository, updating the MySQL Database.
Both images take the following Environment-Variables:
- *DB_HOST*: Hostname of the MySQL Database-Server
- *DB_NAME*: Name of the Database
- *DB_USER*: Username on the Database-Server
- *DB_PASSWORD*: Password of the Database-User
- *ABUSE_MAIL*: Your Contact Mail-Address


Contact
=======

If you have any questions just ask at osm@mazdermind.de.

Peter
