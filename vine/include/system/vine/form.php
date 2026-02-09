<?php

/**
 * HTML Forms
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Form
{
    /**
     * Form data/values.
     * ---
     * @var  array
     */
    protected $data = [];

    /**
     * Data for field-specific option lists.
     * ---
     * @var  array
     */
    protected $options = [];

    /**
     * Form action URL.
     * ---
     * @var  string
     */
    protected $action = NULL;

    /**
     * Form method ("get" or "post").
     * ---
     * @var  string
     */
    protected $method = 'post';

    /**
     * When TRUE, the form's enctype will be "multipart/form-data."
     * ---
     * @var  bool
     */
    protected $uploads = FALSE;

    /**
     * Class constructor. Retain form data from session for a failed form submission.
     * ---
     * @param   bool  Retain form data from session?
     * @return  void
     */
    public function __construct($retain = TRUE)
    {
        // Dump saved session form data to oblivion
        if ( ! $retain) {
            Vine_Session::getData(TRUE);
        // Save session form data to form
        } else {
            $this->data = Vine_Session::getData(TRUE);
        }
    }

    /**
     * Get a form field's value.
     * ---
     * @param   string  Field name.
     * @param   bool    [optional] Escape field value? Defaults to TRUE.
     * @return  mixed
     */
    public function get($field, $escape = TRUE)
    {
        return $escape
            ? Vine_Html::output(Vine_Array::getKey($this->data, $field))
            : Vine_Array::getKey($this->data, $field);
    }

    /**
     * Generate a checkbox <input> for a specific field name.
     * ---
     * @param   string  Field name.
     * @param   mixed   Checkbox's value.
     * @param   mixed   Default value for this field name.
     * @param   array   Additional HTML attributes, formatted as: ['name' => 'value']
     * @return  string
     */
    public function getCheckbox($field, $value, $default = NULL, $attr = NULL)
    {
        // Already checked?
        $checked = Vine_Array::getKey($this->data, $field);

        // Not already checked, use default value for this field name
        if ((FALSE === $checked || NULL === $checked) && NULL !== $default) {
            $checked = $default;
        }

        // (string) Checked checkbox <input>
        if ($checked == $value) {
            return '<input type="checkbox" name="' . $field
                 . '" value="' . Vine_Html::output($value) . '" '
                 . Vine_Html::attributes($attr)
                 . 'checked="checked" />';
        // (string) Checkbox <input>
        } else {
            return '<input type="checkbox" name="' . $field
                 . '" value="' . Vine_Html::output($value) . '" '
                 . Vine_Html::attributes($attr)
                 . '/>';
        }
    }

    /**
     * Generate an <option> list for countries, using two-letter ISO 3166-1 country codes
     * as the option values.
     * ---
     * @param   string  Field name.
     * @param   string  Default value for this field name.
     * @return  string
     */
    public function getCountries($field, $default = NULL)
    {
        // Get countries array
        $countries = require VINE_PATH . 'countries.php';

        // Start with an empty <option> list
        $options = '';

        // Already selected?
        $selected = Vine_Array::getKey($this->data, $field);

        // Not already selected, use default selection
        if ((FALSE === $selected || NULL === $selected) && NULL !== $default) {
            $selected = $default;
        }

        // Loop through each country and make an <option>
        foreach ($countries as $code => $country) {
            $options .= Vine_Html::option($code, $country[0], $code == $selected);
        }

        // (string) <options> list
        return $options;
    }

    /**
     * Generate a form's headers (for the <form> tag).
     * ---
     * @return  void
     */
    public function getHeaders()
    {
        // No action URL set, use self (anti-XSS when you use htmlentities())
        if (NULL === $this->action) {
            $this->action = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, Vine::UNICODE);
        }

        // Build form headers
        $form  = 'method="' . $this->method . '" ';
        $form .= TRUE === $this->uploads ? 'enctype="multipart/form-data" ' : '';
        $form .= 'action="' . $this->action . '"';

        // Output form headers (should be inside a <form> tag)
        return $form;
    }

    /**
     * Generate a hidden <input> for a specific field name.
     * ----
     * @param   string  Field name.
     * @param   mixed   Default value for this field name.
     * @param   array   Additional HTML attributes, formatted as: array(name => value)
     * @return  string
     */
    public function getHidden($field, $default = NULL, $attr = NULL)
    {
        // Current value?
        $value = Vine_Array::getKey($this->data, $field);

        // Not already checked, use default value for this field name
        if ((FALSE === $value || NULL === $value) && NULL !== $default) {
            $value = $default;
        }

        // (string) Hidden <input>
        return '<input type="hidden" name="' . $field
             . '" value="' . Vine_Html::output($value) . '" '
             . Vine_Html::attributes($attr)
             . ' />';
    }

    /**
     * Generate an <option> list for months in a year.
     * ---
     * @param   string  Field name.
     * @param   string  Default value for this field name.
     * @param   string  Date format, according to strftime().
     * @return  string
     */
    public function getMonths($field, $default = NULL, $format = '%b')
    {
        // Get months array
        $months = Vine_Date::getMonths($format);

        // Already selected?
        $selected = Vine_Array::getKey($this->data, $field);

        // Not already selected, use default selection
        if ((FALSE === $selected || NULL === $selected) && NULL !== $default) {
            $selected = $default;
        }

        // Start with an empty <option> list
        $options = '';

        // Loop through each month and make an <option>
        foreach ($months as $value => $text) {
            $options .= Vine_Html::option($value, $text, $value == $selected);
        }

        // (string) <options> list
        return $options;
    }

    /**
     * Generate an <option> list, usually using options previously set using setOptions().
     * ---
     * @param   string  Field name.
     * @param   string  Default value for this field name.
     * @param   array   Use array keys to auto-select option, or array values?
     * @return  string
     */
    public function getOptions($field, $default = NULL, $useKey = TRUE)
    {
        try {
            // Get options provided earlier using setOptions()
            $workspace = Vine_Array::getKey($this->options, $field);

            // Invalid workspace, return an empty string
            if ( ! is_array($workspace) || empty($workspace)) {
                return '';
            }

            // Already selected?
            $selected = Vine_Array::getKey($this->data, $field);

            // Not already selected, use default selection
            if ((FALSE === $selected || NULL === $selected) && NULL !== $default) {
                $selected = $default;
            }

            // Start with an empty <option> list
            $options = '';

            // Loop through each item and make an <option>
            foreach ($workspace as $value => $option) {
                // (bool) Use array values instead of keys to determine selected option
                if ( ! $useKey) {
                    $compare = (string) $option == (string) $selected;
                // (bool) Use array keys to determine selected option
                } else {
                    $compare = (string) $value == (string) $selected;
                }

                // Compile option
                $options .= Vine_Html::option($value, $option, $compare);
            }

            // (string) <options> list
            return $options;
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Generate an <option> list for states/provinces of a supported country.
     * ---
     * @param   string  Field name.
     * @param   string  ISO 3166-1 country code.
     * @param   string  Default value for this field name.
     * @return  string
     */
    public function getProvinces($field, $country = 'US', $default = NULL)
    {
        try {
            // Get provinces array
            $provinces = require VINE_PATH . 'provinces.php';

            // Array for this country's provinces is not available
            if ( ! isset($provinces[$country])) {
                throw new VineBadValueException('Country Code "' . $country . '" is not '
                        . 'supported at this time.');
            }

            // Start with an empty <option> list
            $options = '';

            // Already selected?
            $selected = Vine_Array::getKey($this->data, $field);

            // Not already selected, use default selection
            if ((FALSE === $selected || NULL === $selected) && NULL !== $default) {
                $selected = $default;
            }

            // Loop through each province and make an <option>
            foreach ($provinces[$country] as $code => $province) {
                $options .= Vine_Html::option($code, $province, $code == $selected);
            }

            // (string) <options> list
            return $options;
        } catch (VineBadValueException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Generate a radio <input> for a specific field name.
     * ---
     * @param   string  Field name.
     * @param   mixed   Radio's value.
     * @param   mixed   Default value for this field name.
     * @param   array   Additional HTML attributes, formatted as: array(name => value)
     * @return  string
     */
    public function getRadio($field, $value, $default = NULL, $attr = NULL)
    {
        // Already checked?
        $checked = Vine_Array::getKey($this->data, $field);

        // Not already checked, use default value for this field name
        if ((FALSE === $checked || NULL === $checked) && NULL !== $default) {
            $checked = $default;
        }

        // (string) Checked radio <input>
        if ($checked == $value) {
            return '<input type="radio" name="' . $field
                 . '" value="' . Vine_Html::output($value) . '" '
                 . Vine_Html::attributes($attr)
                 . 'checked="checked" />';
        // (string) Radio <input>
        } else {
            return '<input type="radio" name="' . $field
                 . '" value="' . Vine_Html::output($value) . '" '
                 . Vine_Html::attributes($attr)
                 . '/>';
        }
    }

    /**
     * @see  getProvinces()
     */
    public function getStates($field, $country = 'US', $default = NULL)
    {
        return $this->getProvinces($field, $country, $default);
    }

    /**
     * Generate an <option> years.
     * ---
     * @param   string  Field name.
     * @param   mixed   Field's default value.
     * @param   int     The number of years before current year.
     * @param   int     The number of years after current year.
     * @return  string
     */
    public function getYears($field, $default = NULL, $before = 0, $after = 10)
    {
        // Get years array
        $years = Vine_Date::getYears((int) $before, (int) $after);

        // Already selected?
        $selected = Vine_Array::getKey($this->data, $field);

        // Not already selected, use default selection
        if ((FALSE === $selected || NULL === $selected) && NULL !== $default) {
            $selected = $default;
        }

        // Start with an empty <option> list
        $options = '';

        // Loop through each year and make an <option>
        foreach ($years as $value => $text) {
            $options .= Vine_Html::option($value, $text, $value == $selected);
        }

        // (string) <options> list
        return $options;
    }

    /**
     * Echo a form's headers (for the <form> tag).
     * ---
     * @return  void
     */
    public function putHeaders()
    {
        echo $this->getHeaders();
    }

    /**
     * Set a form field's value.
     * ---
     * [!!!] Will NOT overwrite existing field data unless third parameter is TRUE.
     * ---
     * @param   string  Field name.
     * @param   mixed   Value to set.
     * @param   bool    Force field data overwrite?
     * @return  void
     */
    public function set($field, $value, $force = FALSE)
    {
        if ($force || FALSE === Vine_Array::getKey($this->data, $field)) {
            Vine_Array::setKey($this->data, $field, $value);
        }
    }

    /**
     * Set the form's action URL.
     * ---
     * @param   string
     * @return  void
     */
    public function setAction($url)
    {
        $this->action = trim($url);
    }

    /**
     * Set the values for multiple fields in form.
     * ---
     * [!!!] Will NOT overwrite existing field data.
     * ---
     * @param   array  Multi-dimensional arrays ARE fully supported.
     * @return  void
     */
    public function setData(array $data)
    {
        // No data to set, finished
        if (empty($data)) {
            return;
        }

        // Merge with existing data, giving existing data precedence
        $this->data = Vine_Array::extend(TRUE, $data, $this->data);
    }

    /**
     * Set the form's request method ("post" or "get").
     * ---
     * @param   string
     * @param   bool    Are file <input>'s part of this form?
     * @return  void
     */
    public function setMethod($method, $uploads = FALSE)
    {
        // Standardize
        $method  = strtolower(trim($method));
        $uploads = filter_var($uploads, FILTER_VALIDATE_BOOLEAN);

        // POST or GET?
        $this->method = $method === 'get' ? 'get' : 'post';

        // Does form contain file upload fields?
        $this->uploads = is_bool($uploads) ? $uploads : FALSE;
    }

    /**
     * Set an array of option values to use in an <options> list.
     * ---
     * @param   string  The field name.
     * @param   array   'option-value => 'display-value' array.
     * @return  void
     */
    public function setOptions($field, array $options)
    {
        Vine_Array::setKey($this->options, $field, $options);
    }

    /**
     * Generate a hidden <input> field for anti-CSRF security token.
     * ---
     * @param   string
     * @return  string
     */
    public static function getToken($field = 'security_token')
    {
        return '<input type="hidden" '
             . 'name="' . $field . '" '
             . 'value="' . Vine_Security::makeToken() . '" />'
             . "\n";
    }
}
