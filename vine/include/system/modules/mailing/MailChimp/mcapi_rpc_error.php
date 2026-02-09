<?php
/*
 *      MailChimp API RPC Wrapper
 *      @version 1.0
 *      @author Andrew A. Aculana (xdreucker@gmail.com)
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

class mcapi_rpc_error{

    private $errorCode;
    private $errorMessage;

    public function setErrorCode($errorCode){
        $this->errorCode = $errorCode;
    }
    public function getErrorCode(){
        return $this->errorCode;
    }
    public function setErrorMessage($errorMessage){
        $this->errorMessage = $errorMessage;
    }
    public function getErrorMessage(){
        return $this->errorMessage;
    }
    public function __construct($errorCode, $errorMessage){
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }
    static function isError($MCPAPI_OBJECT){
        if($MCPAPI_OBJECT instanceof mcapi_rpc_error){
            return true;
        }
        return false;
    }
}

?>
