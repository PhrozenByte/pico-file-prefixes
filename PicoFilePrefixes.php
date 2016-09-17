<?php

/**
 * Pico file prefixes plugin - drop file prefixes from page URLs
 *
 * PicoFilePrefixes removes file prefixes (e.g. date identifiers) from page
 * URLs (e.g. http://example.com/20160707.page --> http://example.com/page).
 * This makes organizing your files on the filesystem easier than ever before,
 * while maintaining user-friendly URLs.
 *
 * @author  Daniel Rudolf
 * @link    http://picocms.org
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version 1.0.2
 */
class PicoFilePrefixes extends AbstractPicoPlugin
{
    /**
     * Regex pattern matching directory paths with prefixed files
     * @var string
     */
    protected $filePathRegex = '';

    /**
     * List of pages whose URL has been altered
     * @var array
     */
    protected $prefixPages = array();

    /**
     * Prepare the plugin's configuration and prepare the file path regex
     *
     * @see    DummyPlugin::onRequestFile()
     */
    public function onConfigLoaded(array &$config)
    {
        $defaultPluginConfig = array(
            'recursiveDirs' => array('blog'),
            'dirs' => array()
        );

        if (!isset($config['PicoFilePrefixes']) || !is_array($config['PicoFilePrefixes'])) {
            $config['PicoFilePrefixes'] = $defaultPluginConfig;
            $this->filePathRegex = '#^(blog(?:/.+)?)$#';
            return;
        }

        $config['PicoFilePrefixes'] += $defaultPluginConfig;

        if (empty($config['PicoFilePrefixes']['recursiveDirs']) && empty($config['PicoFilePrefixes']['dirs'])) {
            // disable plugin when no dirs were configured
            $this->setEnabled(false);
            return;
        }

        // prepare file path regex
        $this->filePathRegex = '#^(';
        if (!empty($config['PicoFilePrefixes']['recursiveDirs'])) {
            if (in_array('.', $config['PicoFilePrefixes']['recursiveDirs'])) {
                // enable plugin for any directory
                $this->filePathRegex = '#^(.+)$#';
                return;
            }

            $this->filePathRegex .= '(?:' . implode('|', array_map(function ($recursiveDir) {
                return preg_quote($recursiveDir, '#');
            }, $config['PicoFilePrefixes']['recursiveDirs'])) . ')(?:/.+)?';

            if (!empty($config['PicoFilePrefixes']['dirs'])) {
                $this->filePathRegex .= '|';
            }
        }
        if (!empty($config['PicoFilePrefixes']['dirs'])) {
            $this->filePathRegex .= implode('|', array_map(function ($dir) {
                return preg_quote($dir, '#');
            }, $config['PicoFilePrefixes']['dirs']));
        }
        $this->filePathRegex .= ')$#';
    }

    /**
     * Rewrite shortened URLs to their matching file on the filesystem
     *
     * @see    DummyPlugin::onRequestFile()
     */
    public function onRequestFile(&$file)
    {
        if (file_exists($file)) {
            // don't do anything when the requested file exists
            return;
        }

        $contentDir = $this->getConfig('content_dir');
        $contentDirLength = strlen($contentDir);
        if (substr($file, 0, $contentDirLength) === $contentDir) {
            $filePath = dirname(substr($file, $contentDirLength));
            if (preg_match($this->filePathRegex, $filePath)) {
                $filePattern = (($filePath !== '.') ? $filePath . '/' : '') . '*.' . basename($file);
                $matchingFiles = glob($contentDir . $filePattern);
                if ($matchingFiles) {
                    // use the last matching file in alphabetic order
                    // (i.e. the "highest" prefix wins)
                    $file = end($matchingFiles);
                }
            }
        }
    }

    /**
     * Alter URLs of prefixed files
     *
     * @see    DummyPlugin::onPagesLoaded()
     */
    public function onPagesLoaded(
        array &$pages,
        array &$currentPage = null,
        array &$previousPage = null,
        array &$nextPage = null
    ) {
        foreach ($pages as &$pageData) {
            $filePath = dirname($pageData['id']);
            if (preg_match($this->filePathRegex, $filePath)) {
                $file = basename($pageData['id']);
                if (($removeIdentifierPos = strpos($file, '.')) === false) {
                    // don't alter URLs of files without a prefix
                    continue;
                }

                $file = (($filePath !== '.') ? $filePath . '/' : '') . substr($file, $removeIdentifierPos + 1);
                if (isset($pages[$file])) {
                    // don't do anything when a file of this name exists
                    continue;
                }

                if (isset($this->prefixPages[$file])) {
                    // found a conflicting file
                    // alter the URL of the file with the "highest" prefix only
                    if (strcmp($pageData['id'], $this->prefixPages[$file]['id']) <= 0) {
                        continue;
                    }

                    // restore the URL of the previously altered page
                    $pages[$this->prefixPages[$file]['id']]['url'] = $this->prefixPages[$file]['url'];
                }

                $this->prefixPages[$file] = $pageData;
                $pageData['url'] = $this->getPageUrl($file);
            }
        }
    }
}
