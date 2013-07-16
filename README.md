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


Contact
=======

If you have any questions just ask at osm@mazdermind.de.

Peter
