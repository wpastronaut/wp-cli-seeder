# wpastronaut/wp-cli-seeder

Seeds dummy content into a WordPress installation for development purposes.

## Install

	wp package install git@github.com:wpastronaut/wp-cli-seeder.git

## Examples of usage

Seed to default posts and categories with images:

```
wp seeder seed posts
wp seeder seed terms
wp seeder attach terms-to-posts
wp seeder seed images
wp seeder attach images-to-posts
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
wp seeder seed images
wp seeder attach images-to-posts --post_type="news"
```

Target different languages (currently supports only [Polylang](https://wordpress.org/plugins/polylang/)):

```
wp seeder seed posts --post_type="news" --lang="sv_SE"
wp seeder seed terms --taxonomy="news_category" --lang="sv_SE"
wp seeder attach terms-to-posts --post_type="news" --taxonomy="news_category" --lang="sv_SE"
wp seeder seed images --lang="sv_SE"
wp seeder attach images-to-posts --post_type="news" --lang="sv_SE"
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

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

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

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
\-\-\-

**[--max_depth=&#x3C;number&#x3E;]**\
Max child depth for hierachial post types.\
\-\-\-\
default: 1\
\-\-\-

### Images

	wp seeder seed images

Seeds dummy images.

#### Options

**[--count=&#x3C;number&#x3E;]**\
How many?\
\-\-\-\
default: 5\
\-\-\-

**[--lang=&#x3C;lang&#x3E;]**\
Image language.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
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

**[--lang=&#x3C;lang&#x3E;]**\
Language of the terms and posts you want to attach.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
\-\-\-

### Images to posts

	wp seeder attach images-to-posts

Attach seeded images to seeded posts as featured images.

#### Options

**[--post_type=&#x3C;type&#x3E;]**\
Post type.\
\-\-\-\
default: post\
\-\-\-

**[--lang=&#x3C;lang&#x3E;]**\
Language of the images and posts you want to attach.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
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

**[--lang=&#x3C;lang&#x3E;]**\
Language of the posts you want to delete.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
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

**[--lang=&#x3C;lang&#x3E;]**\
Language of the terms you want to delete.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
\-\-\-

### Images

	wp seeder delete images

Deletes all images seeded by this tool.

#### Options

**[--lang=&#x3C;lang&#x3E;]**\
Language of the images you want to delete.

Currently supports only [Polylang](https://wordpress.org/plugins/polylang/).

Supported values for Polylang:

- 2 digit ISO 639-1 alpha-2 code (eg. sv)
- 2 digit ISO 639-1 alpha-2 code combined with an ISO 3166-1 alpha-2 code separated with an underscore (eg. sv_SE)

\-\-\-\
default:\
\-\-\-
