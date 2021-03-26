# wpastronaut/wp-cli-seeder

Seeds dummy content into a WordPress installation for development purposes.

## Seed

### Posts

    wp seeder seed posts

Seeds dummy posts.

### Options

**[--count=&#x3C;number&#x3E;]**\
How many?\
\-\-\-\
default: 30\
\-\-\-

**[--post_type=&#x3C;type&#x3E;]**\
Post type.\
\-\-\-\
default: post\
\-\-\-

**[--post_status=&#x3C;status&#x3E;]**\
Post status.\
\-\-\-\
default: publish\
\-\-\-

**[--post_author=&#x3C;login&#x3E;]**\
Author.\
\-\-\-\
default:\
\-\-\-

**[--max_depth=&#x3C;number&#x3E;]**\
Max child depth for hierachial post types.\
\-\-\-\
default: 1\
\-\-\-

## Delete

Please be aware that your client might have used seeded content as a basis of some of real content, so you might accidentally delete some real content while using this command.

### All

    wp seeder delete all

Deletes all content seeded by this tool. 
