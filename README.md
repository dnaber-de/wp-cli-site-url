# WP CLI site url

Adds the command `site-url` to WP-CLI. It allows you to change site URLs via the command line interface.

## Commands

### `get`
Gets the site URL by site ID.

Example:
```
$ wp site-url get 2
http://my-site.eu/en/
```

### `update`
Updates a site URL. **Not supported for the networks main site.**

Example:
```
$ wp site-url update 2 http://my-site.co.uk
Success: Update site URL to http://my-site.co.uk/
```

## Crafted by Inpsyde

The team at [Inpsyde](https://inpsyde.com) is engineering the Web since 2006.

## License

Good news, this plugin is free for everyone! Since it's released under the MIT License. You can use it free of charge on your personal or commercial website.

## Contributing

All feedback / bug reports / pull requests are welcome.
