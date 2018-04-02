# Client/Server example

## Usage

```bash
$ docker build -t twirphpexample .
$ docker run --rm -it twirphpexample php -S 0.0.0.0:8080 server.php
$ docker run --rm -it twirphpexample php client.php http://localhost:8080
```