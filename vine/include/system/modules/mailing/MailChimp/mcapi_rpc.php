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

require_once('mcapi_rpc_call.php');

class mcapi_rpc{

    private $mcapi_rpc_call;

    public function __construct($apiKeyOrLoginInfo){
        $this->mcapi_rpc_call = new mcapi_rpc_call($apiKeyOrLoginInfo);
    }

    /**
     * Unschedule a campaign that is scheduled to be sent in the future
     * @param string cid the id of the campaign to unschedule
     * @return mixed | mcapi_rpc_call_error
     */
    public function campaignUnschedule($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignUnschedule', $params);
        return mcapi_rpc::getResult($response);
    }

    /**
     * Schedule a campaign to be sent in the future
     * @param string cid the id of the campaign to schedule
     * @param string schedule_time the time to schedule the campaign. For A/B Split "schedule" campaigns,
     *               the time for Group A - in YYYY-MM-DD HH:II:SS format in GMT
     * @param string schedule_time_b optional -the time to schedule Group B of an A/B Split "schedule"
     *               campaign - in YYYY-MM-DD HH:II:SS format in GMT
     * @return mixed | mcapi_rpc_call_error
     */
    public function campaignSchedule($cid, $schedule_time, $schedule_time_b=NULL){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["schedule_time"]     = php_xmlrpc_encode($schedule_time);
        $params["schedule_time_b"]   = php_xmlrpc_encode($schedule_time_b);
        $response = $this->mcapi_rpc_call->executeMethod('campaignSchedule', $params);
        return mcapi_rpc::getResult($response);
    }

    /**
     * Resume sending an AutoResponder or RSS campaign
     * @param string cid the id of the campaign to resume
     * @return mixed | mcapi_rpc_call_error
     */
    public function campaignResume($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignResume', $params);
        return mcapi_rpc::getResult($response);
    }

    /**
     * Pause an AutoResponder orRSS campaign from sending
     * @param string cid the id of the campaign to pause
     * @return mixed | mcapi_rpc_call_error
     */
    public function campaignPause($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignPause', $params);
        return mcapi_rpc::getResult($response);
    }

    /**
     * Send a given campaign immediately
     * @param string cid the id of the campaign to send
     * @return mixed | mcapi_rpc_call_error
     */
    public function campaignSendNow($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignSendNow', $params);
        return mcapi_rpc::getResult($response);
    }

    /**
     * Send a test of this campaign to the provided email address
     * @param string cid the id of the campaign to test
     * @param string test_emails an array of email address to receive the test message
     * @param string send_type optional by default (null) both formats are sent - "html" or "text" send just that format
     * @return mixed | mcapi_rpc_call_error
     */
    public function campaignSendTest($cid, $test_emails=array(), $send_type=NULL){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["test_emails"]       = php_xmlrpc_encode($test_emails);
        $params["send_type"]         = php_xmlrpc_encode($send_type);
        $response = $this->mcapi_rpc_call->executeMethod('campaignSendTest', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignTemplates(){
        $params = array();
        $response = $this->mcapi_rpc_call->executeMethod('campaignTemplates', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignSegmentTest($list_id, $options){
        $params                      = array();
        $params["list_id"]           = php_xmlrpc_encode($list_id);
        $params["options"]           = php_xmlrpc_encode($options);
        $response = $this->mcapi_rpc_call->executeMethod('campaignSegmentTest', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignCreate($type, $options, $content, $segment_opts=NULL, $type_opts=NULL){
        $params                      = array();
        $params["type"]              = php_xmlrpc_encode($type);
        $params["options"]           = php_xmlrpc_encode($options);
        $params["content"]           = php_xmlrpc_encode($content);
        $params["segment_opts"]      = php_xmlrpc_encode($segment_opts);
        $params["type_opts"]         = php_xmlrpc_encode($type_opts);
        $response = $this->mcapi_rpc_call->executeMethod('campaignCreate', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignUpdate($cid, $name, $value) {
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["name"]              = php_xmlrpc_encode($name);
        $params["value"]             = php_xmlrpc_encode($value);
        $response = $this->mcapi_rpc_call->executeMethod('campaignUpdate', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignReplicate($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignReplicate', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignDelete($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignDelete', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaigns($filters=array(), $start=0, $limit=25){
        $params["filters"]           = php_xmlrpc_encode($filters);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaigns', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignFolders(){
        $params                      = array();
        $response = $this->mcapi_rpc_call->executeMethod('campaignFolders', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignStats($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignStats', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignClickStats($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignClickStats', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignEmailDomainPerformance($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignEmailDomainPerformance', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignHardBounces($cid, $start=0, $limit=1000){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignHardBounces', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignSoftBounces($cid, $start=0, $limit=1000){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignSoftBounces', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignUnsubscribes($cid, $start=0, $limit=1000){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignUnsubscribes', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignAbuseReports($cid, $since=NULL, $start=0, $limit=500){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["since"]             = php_xmlrpc_encode($since);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignAbuseReports', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignAdvice($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignAdvice', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignAnalytics($cid){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $response = $this->mcapi_rpc_call->executeMethod('campaignAnalytics', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignBounceMessages($cid, $start=0, $limit=25, $since=NULL) {
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["since"]             = php_xmlrpc_encode($since);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignBounceMessages', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignEcommOrders($cid, $start=0, $limit=100, $since=NULL){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["since"]             = php_xmlrpc_encode($since);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignEcommOrders', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignShareReport($cid, $opts=array()){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["opts"]              = php_xmlrpc_encode($opts);
        $response = $this->mcapi_rpc_call->executeMethod('campaignShareReport', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignContent($cid, $for_archive=true){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["for_archive"]       = php_xmlrpc_encode($for_archive);
        $response = $this->mcapi_rpc_call->executeMethod('campaignContent', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignOpenedAIM($cid, $start=0, $limit=1000){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignOpenedAIM', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignNotOpenedAIM($cid, $start=0, $limit=1000){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignNotOpenedAIM', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignClickDetailAIM($cid, $url, $start=0, $limit=1000){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["url"]               = php_xmlrpc_encode($url);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignClickDetailAIM', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignEmailStatsAIM($cid, $email_address){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["email_address"]     = php_xmlrpc_encode($email_address);
        $response = $this->mcapi_rpc_call->executeMethod('campaignEmailStatsAIM', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignEmailStatsAIMAll($cid, $start=0, $limit=100){
        $params                      = array();
        $params["cid"]               = php_xmlrpc_encode($cid);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('campaignEmailStatsAIMAll', $params);
        return mcapi_rpc::getResult($response);
    }

    public function campaignEcommAddOrder($order){
        $params                      = array();
        $params["order"]             = php_xmlrpc_encode($order);
        $response = $this->mcapi_rpc_call->executeMethod('campaignEcommAddOrder', $params);
        return mcapi_rpc::getResult($response);
    }

    public function lists(){
        $params = array();
        $response = $this->mcapi_rpc_call->executeMethod('lists', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listMergeVars($id){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $response = $this->mcapi_rpc_call->executeMethod('listMergeVars', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listMergeVarAdd($id, $tag, $name, $req=array()){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["tag"]               = php_xmlrpc_encode($tag);
        $params["name"]              = php_xmlrpc_encode($name);
        $params["req"]               = php_xmlrpc_encode($req);
        $response = $this->mcapi_rpc_call->executeMethod('listMergeVarAdd', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listMergeVarUpdate($id, $tag, $options){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["tag"]               = php_xmlrpc_encode($tag);
        $params["options"]           = php_xmlrpc_encode($options);
        $response = $this->mcapi_rpc_call->executeMethod('listMergeVarUpdate', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listMergeVarDel($id, $tag){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["tag"]               = php_xmlrpc_encode($tag);
        $response = $this->mcapi_rpc_call->executeMethod('listMergeVarDel', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listInterestGroups($id){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $response = $this->mcapi_rpc_call->executeMethod('listInterestGroups', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listInterestGroupAdd($id, $group_name){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["group_name"]        = php_xmlrpc_encode($group_name);
        $response = $this->mcapi_rpc_call->executeMethod('listInterestGroupAdd', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listInterestGroupDel($id, $group_name){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["group_name"]        = php_xmlrpc_encode($group_name);
        $response = $this->mcapi_rpc_call->executeMethod('listInterestGroupDel', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listInterestGroupUpdate($id, $old_name, $new_name){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["old_name"]          = php_xmlrpc_encode($old_name);
        $params["new_name"]          = php_xmlrpc_encode($new_name);
        $response = $this->mcapi_rpc_call->executeMethod('listInterestGroupUpdate', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listWebhooks($id){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $response = $this->mcapi_rpc_call->executeMethod('listWebhooks', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listWebhookAdd($id, $url, $actions=array(), $sources=array()){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["url"]               = php_xmlrpc_encode($url);
        $params["actions"]           = php_xmlrpc_encode($actions);
        $params["sources"]           = php_xmlrpc_encode($sources);
        $response = $this->mcapi_rpc_call->executeMethod('listWebhookAdd', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listWebhookDel($id, $url){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["url"]               = php_xmlrpc_encode($url);
        $response = $this->mcapi_rpc_call->executeMethod('listWebhookDel', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listSubscribe($id, $email_address, $merge_vars, $email_type='html', $double_optin=true, $update_existing=false, $replace_interests=true, $send_welcome=false){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["email_address"]     = php_xmlrpc_encode($email_address);
        $params["merge_vars"]        = php_xmlrpc_encode($merge_vars);
        $params["email_type"]        = php_xmlrpc_encode($email_type);
        $params["double_optin"]      = php_xmlrpc_encode($double_optin);
        $params["update_existing"]   = php_xmlrpc_encode($update_existing);
        $params["replace_interests"] = php_xmlrpc_encode($replace_interests);
        $params["send_welcome"]      = php_xmlrpc_encode($send_welcome);
        $response = $this->mcapi_rpc_call->executeMethod('listSubscribe', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listUnsubscribe($id, $email_address, $delete_member=false, $send_goodbye=true, $send_notify=true){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["email_address"]     = php_xmlrpc_encode($email_address);
        $params["delete_member"]     = php_xmlrpc_encode($delete_member);
        $params["send_goodbye"]      = php_xmlrpc_encode($send_goodbye);
        $params["send_notify"]       = php_xmlrpc_encode($send_notify);
        $response = $this->mcapi_rpc_call->executeMethod('listUnsubscribe', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listUpdateMember($id, $email_address, $merge_vars, $email_type='', $replace_interests=true){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["email_address"]     = php_xmlrpc_encode($email_address);
        $params["merge_vars"]        = php_xmlrpc_encode($merge_vars);
        $params["email_type"]        = php_xmlrpc_encode($email_type);
        $params["replace_interests"] = php_xmlrpc_encode($replace_interests);
        $response = $this->mcapi_rpc_call->executeMethod('listUpdateMember', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listBatchSubscribe($id, $batch, $double_optin=true, $update_existing=false, $replace_interests=true){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["batch"]             = php_xmlrpc_encode($batch);
        $params["double_optin"]      = php_xmlrpc_encode($double_optin);
        $params["update_existing"]   = php_xmlrpc_encode($update_existing);
        $params["replace_interests"] = php_xmlrpc_encode($replace_interests);
        $response = $this->mcapi_rpc_call->executeMethod('listBatchSubscribe', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listBatchUnsubscribe($id, $emails, $delete_member=false, $send_goodbye=true, $send_notify=false){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["email_address"]     = php_xmlrpc_encode($email_address);
        $params["delete_member"]     = php_xmlrpc_encode($delete_member);
        $params["send_goodbye"]      = php_xmlrpc_encode($send_goodbye);
        $params["send_notify"]       = php_xmlrpc_encode($send_notify);
        $response = $this->mcapi_rpc_call->executeMethod('listBatchUnsubscribe', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listMembers($id, $status='subscribed', $since=NULL, $start=0, $limit=100){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["status"]            = php_xmlrpc_encode($status);
        $params["since"]             = php_xmlrpc_encode($since);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $response = $this->mcapi_rpc_call->executeMethod('listMembers', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listMemberInfo($id, $email_address){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["email_address"]     = php_xmlrpc_encode($email_address);
        $response = $this->mcapi_rpc_call->executeMethod('listMemberInfo', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listAbuseReports($id, $start=0, $limit=500, $since=NULL){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $params["start"]             = php_xmlrpc_encode($start);
        $params["limit"]             = php_xmlrpc_encode($limit);
        $params["since"]             = php_xmlrpc_encode($since);
        $response = $this->mcapi_rpc_call->executeMethod('listAbuseReports', $params);
        return mcapi_rpc::getResult($response);
    }

    public function listGrowthHistory($id){
        $params                      = array();
        $params["id"]                = php_xmlrpc_encode($id);
        $response = $this->mcapi_rpc_call->executeMethod('listGrowthHistory', $params);
        return mcapi_rpc::getResult($response);
    }

    public function getAffiliateInfo(){
        $params = array();
        $response = $this->mcapi_rpc_call->executeMethod('getAffiliateInfo', $params);
        return mcapi_rpc::getResult($response);
    }

    public function getAccountDetails(){
        $params = array();
        $response = $this->mcapi_rpc_call->executeMethod('getAccountDetails', $params);
        return mcapi_rpc::getResult($response);
    }

    public function generateText($type, $content){
        $params                      = array();
        $params["type"]              = php_xmlrpc_encode($type);
        $params["content"]           = php_xmlrpc_encode($content);
        $response = $this->mcapi_rpc_call->executeMethod('generateText', $params);
        return mcapi_rpc::getResult($response);
    }

    public function inlineCss($html, $strip_css=false){
        $params                      = array();
        $params["html"]              = php_xmlrpc_encode($html);
        $params["strip_css"]         = php_xmlrpc_encode($strip_css);
        $response = $this->mcapi_rpc_call->executeMethod('inlineCss', $params);
        return mcapi_rpc::getResult($response);
    }

    public function createFolder($name){
        $params                      = array();
        $params["name"]              = php_xmlrpc_encode($name);
        $response = $this->mcapi_rpc_call->executeMethod('createFolder', $params);
        return mcapi_rpc::getResult($response);
    }

    public function apikeys($username, $password, $expired=false){
        $params                      = array();
        $params["username"]          = php_xmlrpc_encode($username);
        $params["password"]          = php_xmlrpc_encode($password);
        $params["expired"]           = php_xmlrpc_encode($expired);
        $response = $this->mcapi_rpc_call->executeMethod('apikeys', $params);
        return mcapi_rpc::getResult($response);
    }

    public function apikeyAdd($username, $password){
        $params                      = array();
        $params["username"]          = php_xmlrpc_encode($username);
        $params["password"]          = php_xmlrpc_encode($password);
        $response = $this->mcapi_rpc_call->executeMethod('apikeyAdd', $params);
        return mcapi_rpc::getResult($response);
    }

    public function apikeyExpire($username, $password){
        $params                      = array();
        $params["username"]          = php_xmlrpc_encode($username);
        $params["password"]          = php_xmlrpc_encode($password);
        $response = $this->mcapi_rpc_call->executeMethod('apikeyExpire', $params);
        return mcapi_rpc::getResult($response);
    }

    public function ping(){
        $params                      = array();
        $response = $this->mcapi_rpc_call->executeMethod('ping', $params);
        return mcapi_rpc::getResult($response);
    }

    public function callMethod(){
        $params                      = array();
        $response = $this->mcapi_rpc_call->executeMethod('callMethod', $params);
        return mcapi_rpc::getResult($response);
    }

    static function getResult($response){
        if(!mcapi_rpc_error::isError($response)){
            return php_xmlrpc_decode($response);
        }else{
            return $response;
        }
    }
}

?>
