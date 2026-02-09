<?php

/**
 * @date    2019-04-01
 * @author  Tell Konkle
 * ---
 * Please use password_hash() and password_verify() in production. The hash_hmac() method is for
 * PHP <= 5.6 (which Vine no longer supports anyways).
 */

/**
 * Login handler for Admin Dashboard.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-08
 */
class Login_Admin extends Login_Base
{
    /**
     * $_SESSION key, $_COOKIE key, COOKIE delimiter, max "remember me" length (seconds).
     */
    const SESSION      = 'demo-admin';
    const COOKIE       = 'demo-admin';
    const DELIMITER    = '%';
    const LOGIN_LENGTH = 1209600; // 2 weeks

    /**
     * Admin session data. NULL if not logged in.
     * @var  array
     * @see  __construct()
     */
    protected $session = NULL;

    /**
     * Class constructor.
     * ---
     * @param   object  Instance of Vine_Db
     * @return  void
     */
    public function __construct($db = NULL)
    {
        // Call parent constructor
        parent::__construct($db);

        // Default URL and access denied messages (may get overwritten)
        $this->loginUrl     = '../admin/';
        $this->accessDenied = "Sorry, you don't have access to that area.";

        // Set session data if logged in
        if (isset($_SESSION[self::SESSION])) {
            $this->session = $_SESSION[self::SESSION];
        }

        // Attempt a cookie-based login
        if ( ! $this->isLoggedIn()) {
            $this->doCookieLogin();
        }
    }

    /**
     * Attempt an admin login with cookie data.
     * ---
     * @return  bool  TRUE if cookie was valid and login successful, FALSE otherwise.
     */
    public function doCookieLogin()
    {
        // No login cookie
        if ( ! isset($_COOKIE[self::COOKIE])) {
            return FALSE;
        }

        // Convert data to array
        $data = explode(self::DELIMITER, $_COOKIE[self::COOKIE]);

        // Cookie's data is not complete
        if ( ! isset($data[0]) || ! isset($data[1])) {
            Vine_Log::logEvent('Invalid login cookie. Data: ' . $_COOKIE[self::COOKIE]);
            setcookie(self::COOKIE, '', time() - 3600);
            return FALSE;
        }

        // Admin's email, token hash, login created after
        $email   = $data[0];
        $token   = hash_hmac('sha512', $data[1], $this->loginKey);
        $created = date('Y-m-d H:i:s', time() - self::LOGIN_LENGTH);

        // Authenticate this cookie
        $sql = "SELECT l.id "
             . "     , l.row_id "
             . "     , l.email "
             . "     , l.created "
             . "FROM logins AS l "
             . "LEFT JOIN admins AS a ON (a.id = l.row_id) "
             . "WHERE l.email = ? "
             . "AND l.token = ? "
             . "AND l.genre = 'Admin' "
             . "AND l.created >= ? "
             . "AND a.status = 'Active' "
             . "AND a.deleted IS NULL "
             . "LIMIT 1";

        // Get admin ID
        $valid = $this->db->fetch($sql, $email, $token, $created);

        // Invalid credentials, take a nap (to stop brute force attacks), destroy cookie
        if ( ! $valid) {
            sleep(2);
            setcookie(self::COOKIE, '', time() - 3600);
            return FALSE;
        }

        // Create new authentification token
        $this->createPersistentSession(
            $valid['row_id'],
            $valid['email'],
            $valid['id'],
            $valid['created']
        );

        // (bool) Create the login
        return $this->createLogin($valid['row_id']);
    }

    /**
     * Attempt an admin login with form data.
     * ---
     * @param   string  The email from the form.
     * @param   string  The password from the form.
     * @param   bool    Create a persistent login ("remember me" cookie)?
     * @return  bool    TRUE if login successful, FALSE otherwise.
     */
    public function doFormLogin($email, $password, $remember)
    {
        // Query this admin based on email
        $sql = "SELECT id, salt, password "
             . "FROM admins "
             . "WHERE email = ? "
             . "AND status = 'Active' "
             . "AND deleted IS NULL "
             . "LIMIT 1";

        // Execute query
        $admin = $this->db->fetch($sql, $email);

        // Invalid email, take a nap (to stop brute force attacks)
        if ( ! $admin) {
            sleep(2);
            return FALSE;
        }

        // Invalid password, take a nap (to stop brute force attacks)
        if (hash_hmac('sha512', $password . $admin['salt'], $this->passwordKey)
            !== $admin['password']
        ) {
            sleep(2);
            return FALSE;
        }

        // Create a new persistent login
        if (TRUE === $remember) {
            $this->createPersistentSession($admin['id'], $email, 0);
        }

        // (bool) Do actual login
        return $this->createLogin($admin['id']);
    }

    /**
     * Logout admin. Destroy session data and persistant login cookie.
     * ---
     * @return  bool  FALSE if wasn't logged in, TRUE if logout successful.
     */
    public function doLogout()
    {
        // Not logged in, stop here
        if ( ! $this->isLoggedIn()) {
            return FALSE;
        }

        // Destroy persistent login session associated with this cookie
        if (isset($_COOKIE[self::COOKIE])) {
            // Convert data to array
            $data = explode(self::DELIMITER, $_COOKIE[self::COOKIE]);

            // Cookie's data is not complete, just destroy it
            if ( ! isset($data[0]) || ! isset($data[1])) {
                setcookie(self::COOKIE, '', time() - 3600);
            // Destroy persistent session associated with this cookie
            } else {
                // The login email and hashed security token
                $email      = $data[0];
                $loginToken = hash_hmac('sha512', $data[1], $this->loginKey);

                // Query to delete persistent session
                $sql = "DELETE FROM logins "
                     . "WHERE genre = 'Admin' "
                     . "AND email = ? "
                     . "AND token = ? "
                     . "LIMIT 1";

                // Execute query, destroy cookie
                $this->db->query($sql, $email, $loginToken);
                setcookie(self::COOKIE, '', time() - 3600);
            }
        }

        // Destroy current login session
        $this->session = NULL;
        unset($_SESSION[self::SESSION]);

        // Successfully logged out
        return TRUE;
    }

    /**
     * Check if admin is logged in.
     * ---
     * @return  bool
     */
    public function isLoggedIn()
    {
        return NULL === $this->session ? FALSE : TRUE;
    }

    /**
     * Check if admin is NOT logged in.
     * ---
     * @return  bool
     */
    public function isNotLoggedIn()
    {
        return NULL === $this->session ? TRUE : FALSE;
    }

    /**
     * Check if admin has full access.
     * ---
     * @return  bool
     */
    public function hasFullAccess()
    {
        if ( ! $this->isLoggedIn()) {
            return FALSE;
        }

        return $this->get('access') > 1;
    }

    /**
     * Check to see if admin can delete buyers.
     * ---
     * @return  bool
     */
    public function canDeleteUsers()
    {
        return $this->hasFullAccess() ? TRUE : FALSE;
    }

    /**
     * Check to see if admin can delete coupons.
     * ---
     * @return  bool
     */
    public function canDeleteCoupons()
    {
        return $this->hasFullAccess() ? TRUE : FALSE;
    }

    /**
     * Check to see if admin can delete inventory categories.
     * ---
     * @return  bool
     */
    public function canDeleteCategories()
    {
        return $this->hasFullAccess() ? TRUE : FALSE;
    }

    /**
     * Check to see if admin can delete inventory.
     * ---
     * @return  bool
     */
    public function canDeleteItems()
    {
        return $this->hasFullAccess() ? TRUE : FALSE;
    }

    /**
     * Check to see if admin can delete orders.
     * ---
     * @return  bool
     */
    public function canDeleteOrders()
    {
        return $this->hasFullAccess() ? TRUE : FALSE;
    }

    /**
     * Check to see if admin can delete notes.
     * ---
     * @return  bool
     */
    public function canDeleteNotes()
    {
        return $this->hasFullAccess() ? TRUE : FALSE;
    }

    /**
     * Get a specific field from login session.
     * ---
     * @param   string  The field to get.
     * @return  mixed   The field value. FALSE if field not found.
     */
    public function get($field)
    {
        if ( ! $this->isLoggedIn()) {
            return FALSE;
        }

        return Vine_Array::getKey($this->session, $field);
    }

    /**
     * Get the name of the logged in admin.
     * ---
     * @return  string|bool  FALSE if not logged in, string otherwise.
     */
    public function getName()
    {
        if (NULL === $this->session) {
            return FALSE;
        }

        return Vine_Html::output(
            $this->get('first_name') . ' ' .
            $this->get('last_name')
        );
    }

    /**
     * If logged in, re-sync login data with database. If unable to re-sync data, admin
     * will be logged out.
     * ---
     * @return  void
     */
    public function refreshLogin()
    {
        // Not logged in, do nothing
        if ( ! $this->isLoggedIn()) {
            return;
        }

        // Query latest admin data
        $sql = "SELECT * "
             . "FROM admins "
             . "WHERE id = ? "
             . "AND status = 'Active' "
             . "AND deleted IS NULL "
             . "LIMIT 1";

        // Execute query
        $row = $this->db->fetch($sql, $this->get('id'));

        // Refresh login data
        if ($row) {
            $_SESSION[self::SESSION] = $row;
            $this->session = $row;
        // Could not refresh data, logout admin
        } else {
            $this->doLogout();
        }
    }

    /**
     * Perform login for a specified admin ID. Assumes admin has already been
     * authenticated with the doCookieLogin() or doFormLogin() methods.
     * ---
     * @param   int   The admin ID to login.
     * @return  bool  TRUE if login is valid and was created, FALSE otherwise.
     */
    protected function createLogin($id)
    {
        // Query admin's login info
        $sql = "SELECT * "
             . "FROM admins "
             . "WHERE id = ? "
             . "AND status = 'Active' "
             . "AND deleted IS NULL "
             . "LIMIT 1";

        // Execute query
        $admin = $this->db->fetch($sql, $id);

        // Login failed
        if ( ! $admin) {
            return FALSE;
        }

        // Save login data
        $_SESSION[self::SESSION] = $admin;
        $this->session = $admin;

        // Compile tracking data
        $update = [
            'last_ip'    => Vine_Quick::storeIp(),
            'last_login' => date('Y-m-d H:i:s'),
        ];

        // Update admin's record (table, data, where, limit[, binds])
        $this->db->update('admins', $update, 'id = ?', 1, $id);

        // Admin logged in successfully
        return TRUE;
    }

    /**
     * Create or update an authentication cookie and login token for a persistent login
     * session.
     * ---
     * @param   int     The admin's database ID.
     * @param   string  The admin's email address.
     * @param   int     The ID of the existing session. If 0, session is assumed new.
     * @param   string  Timestamp of when existing session was created.
     * @return  bool    TRUE if token created successfully, FALSE otherwise.
     */
    protected function createPersistentSession($adminId, $email, $id = 0, $created = NULL)
    {
        // Generate new persistent login session data
        $rawToken    = Vine_Security::makeRandomString(32);
        $hashedToken = hash_hmac('sha512', $rawToken, $this->loginKey);

        // Create a new persistent login session
        if (0 === $id) {
            // Generate cookie's data
            $contents = $email . self::DELIMITER . $rawToken;
            $expires  = time() + self::LOGIN_LENGTH;

            // Generate persistent login session record
            $data = [
                'row_id'  => $adminId,
                'email'   => $email,
                'token'   => $hashedToken,
                'genre'   => 'Admin',
                'created' => date('Y-m-d H:i:s'),
            ];

            // Create persistent cookie
            setcookie(self::COOKIE, $contents, $expires);

            // (bool) Create persistent session record
            return $this->db->insert('logins', $data);
        // Update a persistent login session
        } else {
            // Generate cookie's data
            $contents = $email . self::DELIMITER . $rawToken;
            $expires  = strtotime($created) + self::LOGIN_LENGTH;

            // Generate updated login session record
            $data = [
                'token' => $hashedToken,
                'genre' => 'Admin',
            ];

            // Create persistent cookie (will replace the old one)
            setcookie(self::COOKIE, $contents, $expires);

            // (bool) Update this persistent login session
            return $this->db->update('logins', $data, 'id = ?', 1, $id);
        }
    }
}
