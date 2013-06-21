# User [user]

* id
* login
* password
* name
* email
* catalog : site.id, 1-N

# Site [site]

* id
* identifier
* title
* short_title
* url
* url_pattern
* url_first_page
* url_step
* grab_selector
* allowed_tags
* image_tag

# Story [story]

* id
* text
* site : site.id, 1-1
* grabbed