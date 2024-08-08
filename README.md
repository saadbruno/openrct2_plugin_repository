# OpenRCT2 Plugin Repository
A place where users can share OpenRCT2 plugins

## Live at [https://openrct2plugins.org](https://openrct2plugins.org)

![image](https://user-images.githubusercontent.com/23201434/117683284-4bc90300-b18a-11eb-9508-6bacb918523f.png)

For now, users can submit plugis hosted on GitHub repos. There is a button in the top right to submit links, without needing to log in.

There is server side validation to make sure it's a `https://github.com` link. I'm alsothinking about adding validation to check if the repo has `rct2` or `OpenRCT2` in the tags, but not everyone uses tags.

There is basic listing (sorting new and rating, which is based on when users submitted the plug-in, and how many stars it has on GitHub), also a basic search functionality.

Users can also check details about the plug-in (which is basicallt the README.md form the GitHub repo), and also list plugins by user (by clicking on their profiles)

## Setup:
### Development:
- Create a [GitHub Access Token](https://help.github.com/en/github/authenticating-to-github/creating-a-personal-access-token-for-the-command-line) with the `public_repo` option.
- Copy `template.env` to `.env` and edit it with your GitHub Token, and change the HTTP port if you'd like
- `make run`

You should now be able to access the page at [http://localhost](http://localhost), or http://localhost:PORT if you edited the port in the `.env` file

### Prod:
Reverse proxy example for NGINX is in `docker/nginx/prod/reverse-proxy.conf`

- Copy `template.env` to `.env` and edit it with your GitHub Token, and also change the MySQL password
- Run `make build-sass` to build the scss into a css file
- `make run-prod`

## API:
you can add a query string `json=true` to almost any page, and it will return the results in JSON.
This works all the lists, as well as individual plugin pages and user pages.

It will also work with any type of sorting, new, rating, updated, and also for searches.

Examples:
List of plugins (second page, showing 20 results per page):
/list/?sort=new&results=20&p=2

List of plugins by sadret:
/user/MDQ6VXNlcjE1ODk1NTMy?json=true

Individual plugin:
/plugin/R_kgDOL_w7NA?json=true

## Links and other third party libraries used:
- [Parsedown](https://github.com/erusev/parsedown)  
- [HTML Purifier](http://htmlpurifier.org/)  
