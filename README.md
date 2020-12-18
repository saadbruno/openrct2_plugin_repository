# OpenRCT2 Plugin Repository
A place where users can share OpenRCT2 plugins

## Live at [https://openrct2plugins.org](https://openrct2plugins.org)

![image](https://user-images.githubusercontent.com/23201434/81117476-88ba4400-8efd-11ea-82dc-d01a9048cbb5.png)

For now, users can submit plugis hosted on GitHub repos. There is a button in the top right to submit links, without needing to log in.

There is server side validation to make sure it's a `https://github.com` link. I'm alsothinking about adding validation to check if the repo has `rct2` or `OpenRCT2` in the tags, but not everyone uses tags.

There is basic listing (sorting new and rating, which is based on when users submitted the plug-in, and how many stars it has on GitHub), also a basic search functionality.

Users can also check details about the plug-in (which is basicallt the README.md form the GitHub repo), and also list plugins by user (by clicking on their profiles)

## Setup:
### Development:
- Edit your `/etc/hosts` and add the line:
```
127.0.0.1 openrct2plugins.test pma.openrct2plugins.test traefik.openrct2plugins.test portainer.openrct2plugins.test
```
- Create a [GitHub Access Token](https://help.github.com/en/github/authenticating-to-github/creating-a-personal-access-token-for-the-command-line) with the `public_repo` option.
- Copy `docker/template.env` to `docker/.env` and edit it with your GitHub Token
- `cd` into `./docker` and run `make setup` (if you get a mysql error 2002, wait a bit (mysql is still starting up), and run `make import-schema` again)

You should now be able to acess the page at [http://openrct2plugins.test](http://openrct2plugins.test)

### Prod:
Prod is running on port 4000, so it requires a reverse proxy pointing to that server (example for NGINX is in `docker/nginx/prod/reverse-proxy.conf`)

Aside from that is pretty much the same process:
- Copy `docker/template.env` to `docker/.env` and edit it with your GitHub Token, and also change the MySQL password
- `cd` into `./docker`
- Run `make redeploy-prod`
- Import the DB schema with `make import-schema` (might need to change the password for this one)
- Traefik should automatically install the SSL certificates

## Links and other third party libraries used:
[Docker LEMP stack](https://github.com/cvaclav/docker-lemp-stack)
[Parsedown](https://github.com/erusev/parsedown)
[HTML Purifier](http://htmlpurifier.org/)
