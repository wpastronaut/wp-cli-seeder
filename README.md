# wpastronaut/wp-cli-seeder

Seeds dummy content into a WordPress installation for development purposes.

## Install

	wp package install git@github.com:wpastronaut/wp-cli-seeder.git

## Examples of usage

Seed to default posts and categories:

```
wp seeder seed posts
wp seeder seed terms
wp seeder attach terms-to-posts
```

Seed to pages:

```
wp seeder seed posts --post_type="page" --max_depth=4
```

Seed to custom post type "news" and to custom taxonomy "news_category":

```
wp seeder seed posts --post_type="news"
wp seeder seed terms --taxonomy="news_category"
wp seeder attach terms-to-posts --post_type="news" --taxonomy="news_category"
```

## Seed

### Posts

	wp seeder seed posts

Seeds dummy posts.

#### Options

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

**[--lang=&#x3C;lang&#x3E;]**\
Post language.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 code (eg. sv)
- 2 digit ISO 639-1 code with locale suffix separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
\-\-\-

**[--max_depth=&#x3C;number&#x3E;]**\
Max child depth for hierachial post types.\
\-\-\-\
default: 1\
\-\-\-

### Terms

	wp seeder seed terms

Seeds dummy terms.

#### Options

**[--count=&#x3C;number&#x3E;]**\
How many?\
\-\-\-\
default: 30\
\-\-\-

**[--taxonomy=&#x3C;taxonomy&#x3E;]**\
Taxonomy.\
\-\-\-\
default: category\
\-\-\-

**[--lang=&#x3C;lang&#x3E;]**\
Term language.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 code (eg. sv)
- 2 digit ISO 639-1 code with locale suffix separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
\-\-\-

**[--max_depth=&#x3C;number&#x3E;]**\
Max child depth for hierachial post types.\
\-\-\-\
default: 1\
\-\-\-

## Attach

### Terms to posts

	wp seeder attach terms-to-posts

Attach seeded terms to seeded posts.

#### Options

**[--post_type=&#x3C;type&#x3E;]**\
Post type.\
\-\-\-\
default: post\
\-\-\-

**[--taxonomy=&#x3C;taxonomy&#x3E;]**\
Taxonomy.\
\-\-\-\
default: category\
\-\-\-

## Delete

Please be aware that your client/co-worker might have used seeded content as a basis of some of real content, so you might accidentally delete some real content while using this command.

### All

	wp seeder delete all

Deletes all content seeded by this tool. 

### Posts

	wp seeder delete posts

Deletes all posts seeded by this tool.

#### Options

**[--post_type=&#x3C;type&#x3E;]**\
Post type.\
\-\-\-\
default: any\
\-\-\-

### Terms

	wp seeder delete terms

Deletes all terms seeded by this tool.

#### Options

**[--taxonomy=&#x3C;taxonomy&#x3E;]**\
Taxonomy.\
\-\-\-\
default: any\
\-\-\-
