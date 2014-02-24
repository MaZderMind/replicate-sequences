replicate-sequences
===================

a small and simple tool to get the correct replicate sequence id of [OpenStreetMap's replation-diffs](http://planet.osm.org/replication/) from a timestamp.
Currently deployed on a server near you: http://osm.personalwerk.de/replicate-sequences/


API
===

Besides the Web-Form the tool can handle the following API-Call:
```
http://osm.personalwerk.de/replicate-sequences/?2013-01-01T10:00:00Z
```

This can be used to always fetch the latest state.file in conjunction with some unix-foo:
```
curl "http://osm.personalwerk.de/replicate-sequences/?`date --utc "+%FT%TZ"`"
```

Setting up you own
==================

Setting up your own copy of this service is quite easy. Check out into a PHP-Enabled docroot. Create a MySQL-Database and a User that has `INSERT` and `SELECT` permissions on it. Copy `conf.php.tpl` to `conf.php` and fill in your database name and user credentials. You also MUST FILL IN an E-Mail-Adress where admins of planet.osm.org can contact you, in case this script runs crazy on your server.
Now populate your database with `schema.sql` to get the empty tables. You could now start running `update.php` and it will download >750000 `state.txt`-files to populate your database. This would take at least one complete day. Instead, i'd suggest to also import `initial.sql.gz`, which already contains the first 750000 state-files and pre-loads your database with this information. You can then use `update.php` to stay on track.


Contact
=======

If you have any questions just ask at osm@mazdermind.de.

Peter
