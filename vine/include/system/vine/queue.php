<?php

/**
 * Queue and background process helper.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Queue
{
    /**
     * Has the queue been prepared?
     * ---
     * @var  bool
     */
    protected $prepared = FALSE;

    /**
     * Has the queue been started?
     * ---
     * @var  bool
     */
    protected $started = FALSE;

    /**
     * Has the queue been finished?
     * ---
     * @var  bool
     */
    protected $finished = FALSE;

    /**
     * Class destructor. Finish the queue if it's not already been finished.
     * ---
     * @return  void
     */
    public function __destruct()
    {
        if ( ! $this->finished && $this->started) {
            $this->finish();
        }
    }

    /**
     * Prepare to run a background process. This method should be called BEFORE anything
     * intended to be shown to the user has been echoed.
     * ---
     * @return  void
     */
    public function prepare()
    {
        // Disable automatic output buffering
        ini_set('output_buffering', 0);
        ini_set('zlib.output_compression', 'Off');
        ini_set('implicit_flush', 'On');

        // Discard all current output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Close connection for user but keep PHP process running
        header("Connection: close\r\n");
        header("Content-Encoding: none\r\n");
        ignore_user_abort(TRUE);

        // Start a new output buffer
        ob_start();

        // Queue has been prepared
        $this->prepared = TRUE;
    }

    /**
     * Begin running the process in the background. This method should be called
     * immediately AFTER any/all data intended for the user has been echoed.
     * ---
     * @return  void
     */
    public function start()
    {
        try {
            // Process must be prepared before it can be started
            if ( ! $this->prepared) {
                throw new VineBadObjectException('You can\'t start a background process '
                                               . 'without first calling prepare().');
            }

            // Display intended user output (if any)
            header('Content-Length: ' . ob_get_length());
            session_write_close();
            ob_end_flush();
            flush();

            // FastCGI/nginx only
            if (function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }

            // Begin background process
            sleep(3);
            echo "=== Background Process Started ===\n";

            // Queue has been started
            $this->started = TRUE;
        } catch (VineBadObjectException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Finish the background process. This method should called at the end of the
     * code pertaining to the background process that had previously been started.
     * ---
     * @return  void
     */
    public function finish()
    {
        try {
            // Process must be started before it can be finished
            if ( ! $this->started) {
                throw new VineBadObjectException('You can\'t finish a background process '
                                               . 'without first calling start().');
            }

            // Finish background process
            echo "\n=== Background Process Finished ===";
            exit(1);

            // Queue has been finished
            $this->finished = TRUE;
        } catch (VineBadObjectException $e) {
            Vine_Exception::handle($e);
        }
    }
}
