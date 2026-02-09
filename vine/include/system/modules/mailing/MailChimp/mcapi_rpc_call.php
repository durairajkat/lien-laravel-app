<?php
/*
 *      MailChimp API RPC Wrapper
 *      Wrapper Class for MailChimp API version 1.2
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

require_once('xmlrpc/xmlrpc.inc');
require_once('mcapi_rpc_error.php');

class mcapi_rpc_call{

    private $apiKey;

    const API_VERSION = "1.2";
    const API_URL     = "api.mailchimp.com";

    public function getApiKey(){
        return $this->apiKey;
    }

    public function __construct($apiKeyOrLoginInfo){

        if(is_array($apiKeyOrLoginInfo) && isset($apiKeyOrLoginInfo['username']) && isset($apiKeyOrLoginInfo['password'])){
            $apiKey = $this->login($apiKeyOrLoginInfo['username'], $apiKeyOrLoginInfo['password']);
            if(mcapi_rpc_error::isError($apiKey)){
                $apiKey = "";
            }
        }else{
            $apiKey = $apiKeyOrLoginInfo;
        }

        if(!empty($apiUrl)){
            $apiUrl = $apiUrl;
        }

        $this->apiKey       = $apiKey;
    }

    public function executeMethod($method,$params){
        $dc = "us1";
        if(strstr($this->apiKey,"-")){
            list($key, $dc) = explode("-",$this->apiKey,2);
            if(!$dc){
                $dc = "us1";
            }
        }

        $host = $dc.".".mcapi_rpc_call::API_URL;

        $params['apikey'] =  new xmlrpcval($this->apiKey);

        $xmlrpcmsg = new xmlrpcmsg($method, array(new xmlrpcval($params, 'struct')));
        //$xmlrpc_client = new xmlrpc_client("/".mcapi_rpc_call::API_VERSION."/", "{$host}", 80);
        $xmlrpc_client = new xmlrpc_client("/".mcapi_rpc_call::API_VERSION."/", "{$host}", 443, 'https'); // Adjusted 2018-06-27 by Tell Konkle
        mcapi_rpc_call::LogToFile("Host:".$host." Method:".$method,false);

        $response =& $xmlrpc_client->send($xmlrpcmsg);

        if(!$response->faultCode()){
            mcapi_rpc_call::LogToFile(" Status: Success", true);
            return $response->value();
        }else{
            $value = $response->value();
            mcapi_rpc_call::LogToFile(" Status: Failed [".$response->faultString()."]", true);
            return new mcapi_rpc_error($response->faultCode(), $response->faultString());
        }

    }

    public function login($username, $password){

        $param = array();
        $param['username'] = new xmlrpcval($username);
        $param['password'] = new xmlrpcval($password);
        $response = $this->executeMethod('login', $param);

        if(mcapi_rpc_error::isError($response)){
            return $response;
        }else{
            $apiKey = $response->scalarval();
            return $apiKey;
        }
    }

    static function LogToFile($line, $nextline){
        return; // Added 2013-01-08 by Tell Konkle
        @file_put_contents("upload_log_".date("Ymd").".log", date("Y-m-d H:i:s - ").$line, FILE_APPEND);
        if($nextline){
            @file_put_contents("upload_log_".date("Ymd").".log", "\r\n", FILE_APPEND);
        }
    }
}

?>
