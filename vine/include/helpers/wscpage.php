<?php

/**
 * Simple Template Manager
 * ---
 * @author  Tell Konkle
 * @date    2017-08-09
 */
class WscPage
{
    /**
     * @var  array
     */
    private $_fields = [];

    /**
     * @var  array
     */
    private $_javaScripts = [];

    /**
     * @var  string
     */
    private $_page = NULL;

    /**
     * @var  array
     */
    private $_styleSheets = [];

    /**
     * @var  array
     */
    private $_templates = [];

    /**
     * @var  string
     */
    private $_title = NULL;

    /**
     * @var  string
     */
    private $_titleWindow = NULL;

    /**
     * @var  string
     */
    private $_titleImage = NULL;

    /**
     * @var  string
     */
    private $_titleUrl = NULL;

    /**
     * @var  string
     */
    private $_description = NULL;

    /**
     * Class constructor.
     * ---
     * @param   string  Optional. The page name (used for dynamic navigation).
     * @return  void
     */
    public function __construct($page = NULL)
    {
        $this->_page = $page;
    }

    /**
     * Get the value of a field.
     * ---
     * @param   string
     * @return  mixed
     */
    protected function _getField($field)
    {
        // Field not found
        if ( ! array_key_exists($field, $this->_fields)) {
            return FALSE;
        }

        // Return the field value
        return $this->_fields[$field];
    }

    /**
     * Get all of the JavaScript dependencies for this page.
     * ---
     * @return  bool|array  FALSE if no JavaScript dependencies found, array otherwise.
     */
    protected function _getJavaScripts()
    {
        return empty($this->_javaScripts) ? FALSE : $this->_javaScripts;
    }

    /**
     * Get the page name (used for dynamic navigation).
     * ---
     * @return  string
     */
    protected function _getPage()
    {
        return $this->_page;
    }

    /**
     * Get all of the CSS dependencies for this page.
     * ---
     * @return  bool|array  FALSE if no CSS dependencies found, array otherwise.
     */
    protected function _getStyleSheets()
    {
        return empty($this->_styleSheets) ? FALSE : $this->_styleSheets;
    }

    /**
     * Get the title of this page.
     * ---
     * @return  string
     */
    protected function _getTitle()
    {
        return $this->_title;
    }

    /**
     * Get the title for this page's window/tab. If not provided, page title is returned.
     * ---
     * @return  string
     */
    protected function _getTitleWindow()
    {
        return $this->_titleWindow ? $this->_titleWindow : $this->_title;
    }

    /**
     * Get the title image for this page.
     * ---
     * @return  string
     */
    protected function _getTitleImage()
    {
        return $this->_titleImage;
    }

    /**
     * Get the title URL for this page.
     * ---
     * @return  string
     */
    protected function _getTitleUrl()
    {
        return $this->_titleUrl;
    }

    /**
     * Get the description for this page.
     * ---
     * @return  string
     */
    protected function _getDescription()
    {
        return $this->_description;
    }

    /**
     * Display a template for this page.
     * ---
     * @return  void
     */
    public function putTemplate($name)
    {
        require_once $this->_templates[$name];
    }

    /**
     * Set a field value for this page.
     * ---
     * @param   string
     * @param   mixed
     * @return  void
     */
    public function setField($field, $data)
    {
        $this->_fields[$field] = $data;
    }

    /**
     * Set a JavaScript dependency for this page.
     * ---
     * @param   string  The URL to JavaScript file (relative to page).
     * @return  void
     */
    public function setJavaScript($path)
    {
        $this->_javaScripts[] = $path;
    }

    /**
     * Set a CSS dependency for this page.
     * ---
     * @param   string  The URL to StyleSheet (relative to page).
     * @return  void
     */
    public function setStyleSheet($path)
    {
        $this->_styleSheets[] = $path;
    }

    /**
     * Set a template for this page.
     * ---
     * @param   string  The name of the template.
     * @param   string  The file path to the template.
     * @return  void
     */
    public function setTemplate($name, $path)
    {
        $this->_templates[$name] = $path;
    }

    /**
     * Set the title for this page.
     * ---
     * @param   string
     * @return  void
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * Set the window/tab title for this page.
     * ---
     * @param   string
     * @return  void
     */
    public function setTitleWindow($title)
    {
        $this->_titleWindow = $title;
    }

    /**
     * Set the title image for this page.
     * ---
     * @param   string
     * @return  void
     */
    public function setTitleImage($titleImage)
    {
        $this->_titleImage = $titleImage;
    }

    /**
     * Set the title URL for this page.
     * ---
     * @param   string
     * @return  void
     */
    public function setTitleUrl($titleUrl)
    {
        $this->_titleUrl = $titleUrl;
    }

    /**
     * Set the description for this page.
     * ---
     * @param   string
     * @return  void
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }
}