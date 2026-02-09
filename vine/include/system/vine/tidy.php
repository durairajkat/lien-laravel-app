<?php

/**
 * HTMLTidy Wrapper
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Tidy
{
    /**
     * Tidy configurations.
     * ---
     * @var  array
     */
    protected $config = [
        'xml' => [
            'anchor-as-name'              => FALSE,
            'break-before-br'             => TRUE,
            'char-encoding'               => 'utf8',
            'decorate-inferred-ul'        => FALSE,
            'doctype'                     => 'omit',
            'drop-empty-paras'            => FALSE,
            'drop-proprietary-attributes' => FALSE,
            'fix-backslash'               => FALSE,
            'fix-bad-comments'            => FALSE,
            'fix-uri'                     => TRUE,
            'force-output'                => TRUE,
            'hide-comments'               => FALSE,
            'indent'                      => TRUE,
            'indent-attributes'           => FALSE,
            'indent-spaces'               => 4,
            'input-encoding'              => 'utf8',
            'input-xml'                   => TRUE,
            'join-classes'                => FALSE,
            'join-styles'                 => TRUE,
            'logical-emphasis'            => FALSE,
            'markup'                      => TRUE,
            'merge-divs'                  => FALSE,
            'merge-spans'                 => FALSE,
            'ncr'                         => TRUE,
            'newline'                     => 'LF',
            'numeric-entities'            => TRUE,
            'output-bom'                  => FALSE,
            'output-encoding'             => 'utf8',
            'output-html'                 => FALSE,
            'output-xhtml'                => TRUE,
            'output-xml'                  => FALSE,
            'preserve-entities'           => TRUE,
            'quiet'                       => TRUE,
            'quote-ampersand'             => TRUE,
            'quote-marks'                 => TRUE,
            'repeated-attributes'         => 'keep-last',
            'replace-color'               => FALSE,
            'show-body-only'              => 'auto',
            'show-errors'                 => FALSE,
            'show-warnings'               => FALSE,
            'sort-attributes'             => FALSE,
            'tab-size'                    => 0,
            'tidy-mark'                   => FALSE,
            'vertical-space'              => TRUE,
            'uppercase-attributes'        => FALSE,
            'uppercase-tags'              => FALSE,
            'word-2000'                   => FALSE,
            'wrap'                        => 0,
            'wrap-asp'                    => FALSE,
            'wrap-attributes'             => FALSE,
            'wrap-jste'                   => FALSE,
            'wrap-php'                    => FALSE,
            'wrap-script-literals'        => FALSE,
            'wrap-sections'               => FALSE,
            'write-back'                  => FALSE,
        ],
        'html' => [
            'anchor-as-name'              => FALSE,
            'break-before-br'             => TRUE,
            'char-encoding'               => 'utf8',
            'decorate-inferred-ul'        => FALSE,
            'doctype'                     => 'omit',
            'drop-empty-paras'            => FALSE,
            'drop-proprietary-attributes' => FALSE,
            'fix-backslash'               => FALSE,
            'fix-bad-comments'            => FALSE,
            'fix-uri'                     => TRUE,
            'force-output'                => TRUE,
            'hide-comments'               => FALSE,
            'indent'                      => TRUE,
            'indent-attributes'           => FALSE,
            'indent-spaces'               => 4,
            'input-encoding'              => 'utf8',
            'input-xml'                   => TRUE,
            'join-classes'                => FALSE,
            'join-styles'                 => TRUE,
            'logical-emphasis'            => FALSE,
            'markup'                      => TRUE,
            'merge-divs'                  => FALSE,
            'merge-spans'                 => FALSE,
            'ncr'                         => TRUE,
            'newline'                     => 'LF',
            'numeric-entities'            => TRUE,
            'output-bom'                  => FALSE,
            'output-encoding'             => 'utf8',
            'output-html'                 => FALSE,
            'output-xhtml'                => TRUE,
            'output-xml'                  => FALSE,
            'preserve-entities'           => TRUE,
            'quiet'                       => TRUE,
            'quote-ampersand'             => TRUE,
            'quote-marks'                 => TRUE,
            'repeated-attributes'         => 'keep-last',
            'replace-color'               => FALSE,
            'show-body-only'              => 'auto',
            'show-errors'                 => FALSE,
            'show-warnings'               => FALSE,
            'sort-attributes'             => FALSE,
            'tab-size'                    => 0,
            'tidy-mark'                   => FALSE,
            'vertical-space'              => TRUE,
            'uppercase-attributes'        => FALSE,
            'uppercase-tags'              => FALSE,
            'word-2000'                   => FALSE,
            'wrap'                        => 0,
            'wrap-asp'                    => FALSE,
            'wrap-attributes'             => FALSE,
            'wrap-jste'                   => FALSE,
            'wrap-php'                    => FALSE,
            'wrap-script-literals'        => FALSE,
            'wrap-sections'               => FALSE,
            'write-back'                  => FALSE,
        ],
    ];

    /**
     * Markup to tidy.
     * ---
     * @var  string
     */
    protected $markup = NULL;

    /**
     * Prepare markup for tidying.
     * ---
     * @param  string  Markup needing tidied.
     * @param  array   [optional] Custom Tidy configuration.
     */
    public function __construct($markup, array $config = [])
    {
        $this->config = Vine_Array::extend(TRUE, $this->config, $config);
        $this->markup = $markup;
    }

    /**
     * Tidy markup as HTML.
     * ---
     * @return  string
     */
    public function html()
    {
        return $this->tidy('html');
    }

    /**
     * Tidy markup as XML.
     * ---
     * @return  array
     */
    public function xml()
    {
        return $this->tidy('xml');
    }

    /**
     * Tidy markup using a specified mode.
     * ---
     * @param   string  'html' or 'xml'
     * @return  string  Tidied markup.
     */
    protected function tidy($mode)
    {
        $tidy = new tidy();
        $tidy->parseString($this->markup, $this->config[$mode]);
        $tidy->cleanRepair();
        return tidy_get_output($tidy);
    }
}
