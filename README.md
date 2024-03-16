replicate-sequences
===================

a small and simple tool to get the correct replicate sequence id of [OpenStreetMap's replation-diffs](http://planet.osm.org/replication/) from a timestamp.
Currently deployed on a server near you: https://osm.mazdermind.de/replicate-sequences/


API
===

Besides the Web-Form the tool can handle the following API-Call:
```
https://replicate-sequences.osm.mazdermind.de/?2013-01-01T10:00:00Z
```

This can be used to always fetch the latest state.file in conjunction with some unix-foo:
```
curl "https://replicate-sequences.osm.mazdermind.de/?$(date --utc "+%FT%TZ")"
```

And with even more unix-foo it can be used to fetch the right state.file for a given planet file, assuming that its modification time was preserved when downloading it ("curl" and "wget" usually do):
```
curl "https://replicate-sequences.osm.mazdermind.de/?$(date -u -d "@$(stat -c "%Y" planet-latest.osm.pbf)" +"%FT%TZ")"
```

Setting up you own
==================

Setting up your own copy of this service is quite easy. Check out into a PHP-Enabled docroot. Create a MySQL-Database and a User that has `INSERT` and `SELECT` permissions on it. Copy `conf.php.tpl` to `conf.php` and fill in your database name and user credentials. You also MUST FILL IN an E-Mail-Adress where admins of planet.osm.org can contact you, in case this script runs crazy on your server.
Now populate your database with `schema.sql` to get the empty tables. You could now start running `update.php` and it will download >750000 `state.txt`-files to populate your database. This will take at least one complete day. Be sure to keep running `update.php` every 1-2 minutes to keep your database up to date.


Running with Docker
===================
This tools consists of two Containers, both running the same image ([mazdermind/replicate-sequences:v2](https://hub.docker.com/r/mazdermind/replicate-sequences) but with different environment. Additionally a MySQL database container is needed. Check out [docker/compose.yaml](docker/compose.yaml) for an example on how to deploy the containers.

Building Docker Image
=====================
If you need to rebuild the docker image, use the scripts provided:
```
cd docker
./build-container.sh
./push-container.sh
```


Contact
=======

If you have any questions just ask at osm@mazdermind.de.

Peter
