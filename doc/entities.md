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
* shortTitle
* url
* urlPattern
* urlFirstPage
* urlStep
* grabSelector
* allowedTags
* imageUrl
* categories : category.id, 1-N

# Category [category]

* id
* identifier
* name
* parent : category.id, 1-1

# Story [story]

* id
* text
* textSum
* site : site.id, 1-1
* grabbed