Pico File Prefixes Plugin
=========================

This is the repository of Pico's official file prefixes plugin.

Pico is a stupidly simple, blazing fast, flat file CMS. See http://picocms.org/ for more info.

`PicoFilePrefixes` removes file prefixes (e.g. date identifiers) from page URLs. For example, the blog article `content/blog/20160707.visit-us-on-github.md` normally corresponds to the page URL http://example.com/pico/blog/20160707.visit-us-on-github, however, by installing this plugin, the article will be accessible through the much more user-friendly URL http://example.com/pico/blog/visit-us-on-github. This makes organizing your website's pages on the filesystem easier than ever before.

Install
-------

Just [download the latest release](https://github.com/PhrozenByte/pico-file-prefixes/releases/latest) and upload the `PicoFilePrefixes.php` file to the `plugins` directory of your Pico installation (e.g. `/var/www/html/pico/plugins/`). The plugin requires Pico 1.0+

The plugin recursively drops file prefixes of all files in the `content/blog/` directory by default. You can specify other directories by altering the `$config['PicoFilePrefixes']['recursiveDirs']` and/or `$config['PicoFilePrefixes']['dirs']` config variables in your `config/config.php`. The former parses all files of a directory recursively (i.e. including all its subfolders), whereas the latter parses just files in this particular directory. The default configuration looks like the following:

```php
$config['PicoFilePrefixes']['recursiveDirs'] => array('blog');
$config['PicoFilePrefixes']['dirs'] = array();
```

If you want to additionally enable the plugin for the `content/showcase/` directory, try the following configuration:

```php
$config['PicoFilePrefixes']['recursiveDirs'] => array('blog', 'showcase');
$config['PicoFilePrefixes']['dirs'] = array();
```

If you want to enable the plugin for any folder, try the following:

```php
$config['PicoFilePrefixes']['recursiveDirs'] => array('.');
$config['PicoFilePrefixes']['dirs'] = array();
```
