# KLRN City Showdown Voting App

This is a voting app for [KLRN's City Showdown](https://www.klrn.org/events/cityshowdown/). In the inaugural event, held in spring 2023, residents voted for their favorite performers representing San Antonio City Council districts, with a winner emerging after several rounds. KLRN staff logged into an admin page to view and sort totals and donwload votes.

![City Showdown logo and voting form](images/vote-logo.jpg)

## Setup

### MySQL database

Here is the SQL code to manually clear and create the database table, using a tool such as phpMyAdmin:

```
DROP TABLE votes;
```

```
CREATE TABLE votes (
    id int NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    to_district int NOT NULL,
    zip varchar(10),
    PRIMARY KEY (id)
);
```

This is the SQL code used to create the database table for the 2023 event:

```
CREATE TABLE votes (
    id int NOT NULL AUTO_INCREMENT,
    from_district int NOT NULL,
    to_district int NOT NULL,
    zip varchar(10),
    PRIMARY KEY (id)
);
```

### Admin page

- Add index.php and the /api and /includes folders to the app's root folder on the server
- Add the /assets folder to the app's root folder on the server
  - Except for /assets/klrn-form.css and /assets/html/index_results.html, which are not needed for production
- Edit /includes/contestants-list.php to include contestants, in in this format:
  - `council_district_number => 'contestant name'`
- Rename config-example.php as config.php
- Edit config.php to add database connection info, voter form's location urls, and a list of users as:
  - `'username' => 'password'`

```
// config-example.php

<?php
return array(
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => '',
    'db_name' => 'klrn_city_showdown',
    'vote_url_dev' => '../vote.html',
    'vote_url_prod' => 'https://www.klrn.org/events/cityshowdown/',
	'users' => ['dev1' => '123',
                'dev2', '234']
);
```

### Front-end voter form component

- In the script at the top of vote.html set the form's action url for `formActionDev` and `formActionProd`
- And set `showThankYou=true` when testing results from database, and to `false` for production
- Also in vote.html, edit the select element that has `id="to_district"` to include contestants for each council district
- Add vote.html to a web page
  - The file here is designed as a [PBS Bento 3 Embed Code](https://docs.pbs.org/display/B3/Embed) for [klrn.org](https://www.klrn.org/)
  - For localhost development, the file injects /assets/klrn-form.css to add additional styles from klrn.org

## Folders and files

- **/api**
  - **display-results.php** - gets voting results from database and parses into HTML
  - **download-results.php** - gets voting results from database and downloads as csv file
  - **submit-vote.php** - parses POST from web form and inserts into database
- **/assets**
  - **/html** - html templates for admin page (though index_results.html is for mockup only)
  - **/img** - logo and header image for admin page
  - **klrn-form.css** - additional styles from klrn.org that get injected into vote.html only during development
  - **scripts.js** - implements insertion sort for each table column on admin page
  - **styles.css** - for admin page
- **/images** - for README.md
- **/includes**
  - **contestants-list.php** - associative array of council districts and the contestants
  - **database-conn.php**
- **admin.html** - mockup only
- **index.php** - admin interface
- **sql.txt** - for reference, SQL code used in app
- **vote.html** - HTML, CSS and JavaScript component to manually insert as a [PBS Bento 3 Embed Code](https://docs.pbs.org/display/B3/Embed) for [klrn.org](https://www.klrn.org/)
- **vote-2023.html** - version from 2023 event
- **vote-script.html** - script to handle dynamic page elements outside of voting form, such as how many votes to allow, and when to show form, buttons and thank-you message
- **vote-script-2023.html** - version from 2023 event

![City Showdown results mockup](images/results.jpg)

## References

- [KLRN City Showdown](https://www.klrn.org/cityshowdown/)
- [Bento 3 Documentation](https://docs.pbs.org/display/B3)
- [Bento 3 Embed Code](https://docs.pbs.org/display/B3/Embed)
