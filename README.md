Pico File Prefixes
==================

This is the repository of Pico's official file prefixes plugin.

Pico is a stupidly simple, blazing fast, flat file CMS. See http://picocms.org/ for more info.

`PicoFilePrefixes` removes file prefixes (e.g. date identifiers) from page URLs. For example, the blog article `content/blog/20160707.visit-us-on-github.md` normally corresponds to the page URL http://example.com/pico/blog/20160707.visit-us-on-github, however, by installing this plugin, the article will be accessible through the much more user-friendly URL http://example.com/pico/blog/visit-us-on-github. This makes organizing your website's pages on the filesystem easier than ever before.

Install
-------

You can either install `PicoFilePrefixes` using [Composer](https://getcomposer.org/), or using a single PHP plugin file. We recommend you to use Composer whenever possible, because it allows you to keep the plugin up-to-date way more easily.

If you use a Composer-based installation of Pico and want to either remove or install `PicoFilePrefixes`, simply open a shell on your server and navigate to Pico's install directory (e.g. `/var/www/html/pico/`). Run `composer remove phrozenbyte/pico-file-prefixes` to remove `PicoFilePrefixes`, or run `composer require phrozenbyte/pico-file-prefixes` (via [Packagist.org](https://packagist.org/packages/phrozenbyte/pico-file-prefixes)) to install `PicoFilePrefixes`.

If you really want to install `PicoFilePrefixes` using a single PHP plugin file, [download the latest release](https://github.com/PhrozenByte/pico-file-prefixes/releases/latest) and upload the `PicoFilePrefixes.php` file to the `plugins` directory of your Pico installation (e.g. `/var/www/html/pico/plugins/`).

`PicoFilePrefixes` requires Pico 2.0+

Config
------

The plugin recursively drops file prefixes of all files in the `content/blog/` directory by default. You can specify other directories by altering the `PicoFilePrefixes.recursiveDirs` and/or `PicoFilePrefixes.dirs` config variables (both expect YAML lists) in your `config/config.php`. The former parses all files of a directory recursively (i.e. including all its subfolders), whereas the latter parses just files in this particular directory. The default configuration looks like the following:

```yaml
PicoFilePrefixes:
  recursiveDirs:
    - blog
  dirs: []
```

If you want to additionally enable the plugin for the `content/showcase/` directory, try the following configuration:

```yaml
PicoFilePrefixes:
  recursiveDirs:
    - blog
    - showcase
  dirs: []
```

If you want to enable the plugin for any folder, try the following:

```yaml
PicoFilePrefixes:
  recursiveDirs:
    - .
  dirs: []
```

To enable the plugin for pages in the `content/misc/` directory only (i.e. not including subfolders like `content/misc/sub/`), try the following:

```yaml
PicoFilePrefixes:
  recursiveDirs: []
  dirs:
    - misc
```
