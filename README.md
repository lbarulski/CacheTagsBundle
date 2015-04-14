# CacheTagsBundle

# varnish 3
```
sub vcl_recv {
    if (req.request == "BAN") {
        if (req.http.X-CACHE-TAG) {
            ban("obj.http.X-CACHE-TAGS ~ " + req.http.X-CACHE-TAG);
        } else {
            error 400 "Tag not given"
        }

        error 200 "Banned";
    }
}
```
