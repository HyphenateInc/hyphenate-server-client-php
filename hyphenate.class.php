<?php
/**
--------------------------------------------------
Hyphenate PHP REST REST API client library
--------------------------------------------------
Copyright(c) 2017 Hyphenate Inc www.hyphenate.io
--------------------------------------------------
Author: Hyphenate <info@hyphenate.io>
--------------------------------------------------
 */
class Hyphenate {

    private $client_id;
    private $client_secret;
    private $org_name;
    private $app_name;
    private $base_url = 'https://api.hyphenate.io/';
    private $url;

    /**
     * constructor init
     *
     * @param array $options
     * @param $options['client_id']
     * @param $options['client_secret']
     * @param $options['org_name']
     * @param $options['app_name']
     */
    public function __construct($options) {
        $this->client_id = isset ( $options ['client_id'] ) ? $options ['client_id'] : '';
        $this->client_secret = isset ( $options ['client_secret'] ) ? $options ['client_secret'] : '';
        $this->org_name = isset ( $options ['org_name'] ) ? $options ['org_name'] : '';
        $this->app_name = isset ( $options ['app_name'] ) ? $options ['app_name'] : '';
        if (! empty ( $this->org_name ) && ! empty ( $this->app_name )) {
            $this->url = $this->base_url . $this->org_name . '/' . $this->app_name . '/';
        }
    }
    /*
        get authentication token
     */
    function getToken()
    {
        $options=array(
            "grant_type"=>"client_credentials",
            "client_id"=>$this->client_id,
            "client_secret"=>$this->client_secret
        );
        // json_encode() function, it converts PHP array or object into json string
        // json_decode() function, it converts PHP json string to array or object
        $body=json_encode($options);
        // use $GLOBALS to replace global
        $url=$this->url.'token';
        //$url=$base_url.'token';
        $tokenResult = $this->postCurl($url,$body,$header=array());
        //var_dump($tokenResult['expires_in']);
        //return $tokenResult;
        return "Authorization:Bearer ".$tokenResult['access_token'];

    }
    /*
        create a Hyphenate IM account
     */
    function createUser($username,$password){
        $url=$this->url.'users';
        $options=array(
            "username"=>$username,
            "password"=>$password
        );
        $body=json_encode($options);
        $header=array($this->getToken());
        $result=$this->postCurl($url,$body,$header);
        return $result;
    }
    /*
        create multiple Hyphenate IM account
    */
    function createUsers($options){
        $url=$this->url.'users';

        $body=json_encode($options);
        $header=array($this->getToken());
        $result=$this->postCurl($url,$body,$header);
        return $result;
    }
    /*
        reset user password
    */
    function resetPassword($username,$newpassword){
        $url=$this->url.'users/'.$username.'/password';
        $options=array(
            "newpassword"=>$newpassword
        );
        $body=json_encode($options);
        $header=array($this->getToken());
        $result=$this->postCurl($url,$body,$header,"PUT");
        return $result;
    }

    /*
        get a user
    */
    function getUser($username){
        $url=$this->url.'users/'.$username;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,"GET");
        return $result;
    }

    /*
        get multiple users without pagination, default 10 users at a time
    */
    function getUsers($limit=0){
        if(!empty($limit)){
            $url=$this->url.'users?limit='.$limit;
        }else{
            $url=$this->url.'users';
        }
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,"GET");
        return $result;
    }

    /*
        get multiple users with pagination, default 10 users at a time
    */
    function getUsersForPage($limit=0,$cursor=''){
        $url=$this->url.'users?limit='.$limit.'&cursor='.$cursor;

        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,"GET");
        if(!empty($result["cursor"])){
            $cursor=$result["cursor"];
            $this->writeCursor("userfile.txt",$cursor);
        }
        //var_dump($GLOBALS['cursor'].'00000000000000');
        return $result;
    }

    // create directory
    function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if (!mkdirs(dirname($dir), $mode)) return FALSE;
        return @mkdir($dir, $mode);
    }
    // write cursor
    function writeCursor($filename,$content){
        // create file if not exist
        if(!file_exists("resource/txtfile")){
            mkdirs("resource/txtfile");
        }
        $myfile=@fopen("resource/txtfile/".$filename,"w+") or die("Unable to open file!");
        @fwrite($myfile,$content);
        fclose($myfile);
    }
    // read cursor
    function readCursor($filename){
        // create file if not exist
        if(!file_exists("resource/txtfile")){
            mkdirs("resource/txtfile");
        }
        $file="resource/txtfile/".$filename;
        $fp=fopen($file,"a+");  // mode a+
        if($fp){
            while(!feof($fp)){
                // length of reading
                $data=fread($fp,1000);
            }
            fclose($fp);
        }
        return $data;
    }

    /*
        delete a user
    */
    function deleteUser($username){
        $url=$this->url.'users/'.$username;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,'DELETE');
        return $result;
    }
    /*
        delete multiple users
        limit: recommend delete 100-500 user one at a time
        Note: see the return object for deleted users
    */
    function deleteUsers($limit){
        $url=$this->url.'users?limit='.$limit;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,'DELETE');
        return $result;
    }

    /*
        update user's nickname
    */
    function editNickname($username,$nickname){
        $url=$this->url.'users/'.$username;
        $options=array(
            "nickname"=>$nickname
        );
        $body=json_encode($options);
        $header=array($this->getToken());
        $result=$this->postCurl($url,$body,$header,'PUT');
        return $result;
    }

    /*
        add friend to user
    */
    function addFriend($username,$friend_name){
        $url=$this->url.'users/'.$username.'/contacts/users/'.$friend_name;
        $header=array($this->getToken(),'Content-Type:application/json');
        $result=$this->postCurl($url,'',$header,'POST');
        return $result;
    }

    /*
        remove friend from user
    */
    function deleteFriend($username,$friend_name){
        $url=$this->url.'users/'.$username.'/contacts/users/'.$friend_name;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,'DELETE');
        return $result;
    }

    /*
        get a list of friends of user
    */
    function showFriends($username){
        $url=$this->url.'users/'.$username.'/contacts/users';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,'GET');
        return $result;

    }

    /*
        get blacklist of user
    */
    function getBlacklist($username){
        $url=$this->url.'users/'.$username.'/blocks/users';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,'GET');
        return $result;

    }

    /*
        block user (add user to blacklist) for user
    */
    function addUserForBlacklist($username, $usernames){
        $url=$this->url.'users/'.$username.'/blocks/users';
        $body=json_encode($usernames);
        $header=array($this->getToken());
        $result=$this->postCurl($url, $body, $header, 'POST');
        return $result;
    }

    /*
        unblock user (remove user from blacklist) for user
    */
    function deleteUserFromBlacklist($username,$blocked_name){
        $url=$this->url.'users/'.$username.'/blocks/users/'.$blocked_name;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'DELETE');
        return $result;
    }

    /*
       get user's online status
    */
    function isOnline($username){
        $url=$this->url.'users/'.$username.'/status';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'GET');
        return $result;
    }

    /*
        get user's offline messages (undelivered messages)
    */
    function getOfflineMessages($username){
        $url=$this->url.'users/'.$username.'/offline_msg_count';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'GET');
        return $result;
    }

    /*
        get offline message (undelivered message) status
        if status shown as "delivered" means message is delivered
    */
    function getOfflineMessageStatus($username,$msg_id){
        $url=$this->url.'users/'.$username.'/offline_msg_status/'.$msg_id;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'GET');
        return $result;
    }

    /*
        deactivate user account
    */
    function deactiveUser($username){
        $url=$this->url.'users/'.$username.'/deactivate';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header);
        return $result;
    }

    /*
        activate user account
    */
    function activeUser($username){
        $url=$this->url.'users/'.$username.'/activate';
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header);
        return $result;
    }

    /*
        force user logout
    */
    function disconnectUser($username){
        $url=$this->url.'users/'.$username.'/disconnect';
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header, 'GET');
        return $result;
    }


    /**
     *  File Downloading and Uploading
     */

    /*
        upload file
    */
    function uploadFile($filePath){
        $url=$this->url.'chatfiles';
        $file=file_get_contents($filePath);
        $body['file']=$file;
        $header=array('enctype:multipart/form-data',$this->getToken(),"restrict-access:true");
        $result=$this->postCurl($url, $body, $header, 'XXX');
        return $result;
    }

    /*
        download file
        $fileType in string // "png", "jpg", etc
    */
    function downloadFile($uuid, $shareSecret, $fileType){
        $url=$this->url.'chatfiles/'.$uuid;
        $header = array("share-secret:".$shareSecret,"Accept:application/octet-stream", $this->getToken());
        $result=$this->postCurl($url, '', $header, 'GET');
        $filename = md5(time().mt_rand(10, 99)).$fileType; // file name
        if(!file_exists("/downloads")){
            mkdirs("/downloads");
        }

        $file = @fopen("/downloads".$filename,"w+");    // open file to write
        @fwrite($file,$result); // write
        fclose($file);          // close
        return $filename;
    }

    /*
        download thumbnail
        $fileType in string // "png", "jpg", etc
    */
    function downloadThumbnail($uuid, $shareSecret, $fileType){
        $url=$this->url.'chatfiles/'.$uuid;
        $header = array("share-secret:".$shareSecret,"Accept:application/octet-stream", $this->getToken(), "thumbnail:true");
        $result=$this->postCurl($url,'', $header, 'GET');
        $filename = md5(time().mt_rand(10, 99)).$fileType; // file name
        if(!file_exists("/downloads")){
            mkdirs("/downloads");
        }

        $file = @fopen("/downloads".$filename,"w+");    // open file to write
        @fwrite($file,$result); // write
        fclose($file);          // close
        return $filename;
    }


    /**
     *  Messaging Sending
     */

    /*
        send text message
    */
    function sendText($from="admin", $target_type, $target, $content, $ext){
        $url=$this->url.'messages';
        $body['target_type']=$target_type;
        $body['target']=$target;
        $options['type']="txt";
        $options['msg']=$content;
        $body['msg']=$options;
        $body['from']=$from;
        $body['ext']=$ext;
        $encoded_body=json_encode($body);
        $header=array($this->getToken());
        $result=$this->postCurl($url, $encoded_body, $header);
        return $result;
    }

    /*
        send command message
    */
    function sendCmd($from="admin", $target_type, $target, $action, $ext){
        $url=$this->url.'messages';
        $body['target_type']=$target_type;
        $body['target']=$target;
        $options['type']="cmd";
        $options['action']=$action;
        $body['msg']=$options;
        $body['from']=$from;
        $body['ext']=$ext;
        $encoded_body=json_encode($body);
        $header=array($this->getToken());
        //$b=json_encode($body,true);
        $result=$this->postCurl($url, $encoded_body, $header);
        return $result;
    }

    /*
        send image message
    */
    function sendImage($filePath, $from="admin", $target_type, $target, $filename, $ext){
        $result=$this->uploadFile($filePath);
        $uri=$result['uri'];
        $uuid=$result['entities'][0]['uuid'];
        $shareSecret=$result['entities'][0]['share-secret'];
        $url=$this->url.'messages';
        $body['target_type']=$target_type;
        $body['target']=$target;
        $options['type']="img";
        $options['url']=$uri.'/'.$uuid;
        $options['filename']=$filename;
        $options['secret']=$shareSecret;
        $options['size']=array(
            "width"=>480,
            "height"=>720
        );
        $body['msg']=$options;
        $body['from']=$from;
        $body['ext']=$ext;
        $encoded_body=json_encode($body);
        $header=array($this->getToken());
        //$b=json_encode($body, true);
        $result=$this->postCurl($url, $encoded_body, $header);
        return $result;
    }

    /*
        send audio message
    */
    function sendAudio($filePath, $from="admin", $target_type, $target, $filename, $length, $ext){
        $result=$this->uploadFile($filePath);
        $uri=$result['uri'];
        $uuid=$result['entities'][0]['uuid'];
        $shareSecret=$result['entities'][0]['share-secret'];
        $url=$this->url.'messages';
        $body['target_type']=$target_type;
        $body['target']=$target;
        $options['type']="audio";
        $options['url']=$uri.'/'.$uuid;
        $options['filename']=$filename;
        $options['length']=$length;
        $options['secret']=$shareSecret;
        $body['msg']=$options;
        $body['from']=$from;
        $body['ext']=$ext;
        $encoded_body=json_encode($body);
        $header=array($this->getToken());
        //$b=json_encode($body,true);
        $result=$this->postCurl($url, $encoded_body, $header);
        return $result;
    }

    /*
        send video message
    */
    function sendVideo($filePath, $from="admin", $target_type, $target, $filename, $length, $thumb, $thumb_secret, $ext){
        $result=$this->uploadFile($filePath);
        $uri=$result['uri'];
        $uuid=$result['entities'][0]['uuid'];
        $shareSecret=$result['entities'][0]['share-secret'];
        $url=$this->url.'messages';
        $body['target_type']=$target_type;
        $body['target']=$target;
        $options['type']="video";
        $options['url']=$uri.'/'.$uuid;
        $options['filename']=$filename;
        $options['thumb']=$thumb;
        $options['length']=$length;
        $options['secret']=$shareSecret;
        $options['thumb_secret']=$thumb_secret;
        $body['msg']=$options;
        $body['from']=$from;
        $body['ext']=$ext;
        $encoded_body=json_encode($body);
        $header=array($this->getToken());
        //$b=json_encode($body,true);
        $result=$this->postCurl($url, $encoded_body, $header);
        return $result;
    }

    /*
        send file message
    */
    function sendFile($filePath, $from="admin", $target_type, $target, $filename, $length, $ext){
        $result=$this->uploadFile($filePath);
        $uri=$result['uri'];
        $uuid=$result['entities'][0]['uuid'];
        $shareSecret=$result['entities'][0]['share-secret'];
        $url=$GLOBALS['base_url'].'messages';
        $body['target_type']=$target_type;
        $body['target']=$target;
        $options['type']="file";
        $options['url']=$uri.'/'.$uuid;
        $options['filename']=$filename;
        $options['length']=$length;
        $options['secret']=$shareSecret;
        $body['msg']=$options;
        $body['from']=$from;
        $body['ext']=$ext;
        $encoded_body=json_encode($body);
        $header=array(getToken());
        //$b=json_encode($body,true);
        $result=postCurl($url, $encoded_body, $header);
        return $result;
    }


    /**
     *  Group Operation
     */

    /*
        get all the groups without pagination, default 10 groups
    */
    function getGroups($limit=0){
        if(!empty($limit)){
            $url=$this->url.'chatgroups?limit='.$limit;
        }else{
            $url=$this->url.'chatgroups';
        }

        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, "GET");
        return $result;
    }

    /*
        get all the groups with pagination
    */
    function getGroupsForPage($limit=0,$cursor=''){
        $url=$this->url.'chatgroups?limit='.$limit.'&cursor='.$cursor;
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header, "GET");

        if(!empty($result["cursor"])){
            $cursor=$result["cursor"];
            $this->writeCursor("groupfile.txt", $cursor);
        }
        //var_dump($GLOBALS['cursor'].'00000000000000');
        return $result;
    }

    /*
        get group(s) details
    */
    function getGroupDetail($group_ids){
        $g_ids=implode(',',$group_ids);
        $url=$this->url.'chatgroups/'.$g_ids;
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header, 'GET');
        return $result;
    }

    /*
        create a group
    */
    function createGroup($options){
        $url=$this->url.'chatgroups';
        $header=array($this->getToken());
        $body=json_encode($options);
        $result=$this->postCurl($url, $body, $header);
        return $result;
    }
    /*
        update group description
    */
    function modifyGroupInfo($group_id,$options){
        $url=$this->url.'chatgroups/'.$group_id;
        $body=json_encode($options);
        $header=array($this->getToken());
        $result=$this->postCurl($url, $body, $header, 'PUT');
        return $result;
    }

    /*
        delete a group
    */
    function deleteGroup($group_id){
        $url=$this->url.'chatgroups/'.$group_id;
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header, 'DELETE');
        return $result;
    }

    /*
        get group members
    */
    function getGroupUsers($group_id){
        $url=$this->url.'chatgroups/'.$group_id.'/users';
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header, 'GET');
        return $result;
    }

    /*
        add a user to group
    */
    function addGroupMember($group_id,$username){
        $url=$this->url.'chatgroups/'.$group_id.'/users/'.$username;
        $header=array($this->getToken(),'Content-Type:application/json');
        $result=$this->postCurl($url, '', $header);
        return $result;
    }

    /*
        add multiple users to group
    */
    function addGroupMembers($group_id,$usernames){
        $url=$this->url.'chatgroups/'.$group_id.'/users';
        $body=json_encode($usernames);
        $header=array($this->getToken(),'Content-Type:application/json');
        $result=$this->postCurl($url, $body, $header);
        return $result;
    }

    /*
        delete a group member
    */
    function deleteGroupMember($group_id,$username){
        $url=$this->url.'chatgroups/'.$group_id.'/users/'.$username;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'DELETE');
        return $result;
    }

    /*
        delete multiple group members
    */
    function deleteGroupMembers($group_id,$usernames){
        $url=$this->url.'chatgroups/'.$group_id.'/users/'.$usernames;
        //$body=json_encode($usernames);
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'DELETE');
        return $result;
    }

    /*
        get a list of groups user joined
    */
    function getGroupsForUser($username){
        $url=$this->url.'users/'.$username.'/joined_chatgroups';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'GET');
        return $result;
    }

    /*
        transfer group ownership
    */
    function changeGroupOwner($group_id,$options){
        $url=$this->url.'chatgroups/'.$group_id;
        $body=json_encode($options);
        $header=array($this->getToken());
        $result=$this->postCurl($url, $body, $header, 'PUT');
        return $result;
    }

    /*
        get the blacklist of group
    */
    function getGroupBlacklist($group_id){
        $url=$this->url.'chatgroups/'.$group_id.'/blocks/users';
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header, 'GET');
        return $result;
    }

    /*
        block a user (add user to group blacklist)
    */
    function addGroupMemberToBlacklist($group_id, $username){
        $url=$this->url.'chatgroups/'.$group_id.'/blocks/users/'.$username;
        $header=array($this->getToken());
        $result=$this->postCurl($url, '', $header);
        return $result;
    }

    /*
        block multiple users (add user to group blacklist)
    */
    function addGroupMembersToBlacklist($group_id, $usernames){
        $url=$this->url.'chatgroups/'.$group_id.'/blocks/users';
        $body=json_encode($usernames);
        $header=array($this->getToken());
        $result=$this->postCurl($url, $body, $header);
        return $result;
    }

    /*
        unblock a user (remove user from group blacklist)
    */
    function removeGroupMemberFromBlacklist($group_id, $username){
        $url=$this->url.'chatgroups/'.$group_id.'/blocks/users/'.$username;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,'DELETE');
        return $result;
    }

    /*
        unblock multiple users (remove users from group blacklist)
    */
    function removeGroupMembersFromBlacklist($group_id, $usernames){
        $url=$this->url.'chatgroups/'.$group_id.'/blocks/users';
        $body=json_encode($usernames);
        $header=array($this->getToken());
        $result=$this->postCurl($url,$body,$header,'DELETE');
        return $result;
    }


    /**
     *  Group Operation
     */

    /*
        create chat room
    */
    function createChatRoom($options){
        $url=$this->url.'chatrooms';
        $header=array($this->getToken());
        $body=json_encode($options);
        $result=$this->postCurl($url,$body,$header);
        return $result;
    }

    /*
        update chat room description
    */
    function modifyChatRoom($chatroom_id, $options){
        $url=$this->url.'chatrooms/'.$chatroom_id;
        $body=json_encode($options);
        $result=$this->postCurl($url,$body,$header,'PUT');
        return $result;
    }

    /*
        delete chat room
    */
    function deleteChatRoom($chatroom_id){
        $url=$this->url.'chatrooms/'.$chatroom_id;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'',$header,'DELETE');
        return $result;
    }

    /*
        get all the chat rooms
    */
    function getChatRooms(){
        $url=$this->url.'chatrooms';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, "GET");
        return $result;
    }

    /*
        get chat room details
    */
    function getChatRoomDetail($chatroom_id){
        $url=$this->url.'chatrooms/'.$chatroom_id;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'GET');
        return $result;
    }

    /*
        get a list of chat room user joined
    */
    function getChatRoomJoined($username){
        $url=$this->url.'users/'.$username.'/joined_chatrooms';
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'GET');
        return $result;
    }

    /*
        add a member to chat room
    */
    function addChatRoomMember($chatroom_id, $username){
        $url=$this->url.'chatrooms/'.$chatroom_id.'/users/'.$username;
        //$header=array($this->getToken());
        $header=array($this->getToken(),'Content-Type:application/json');
        $result=$this->postCurl($url,'', $header);
        return $result;
    }

    /*
        add multiple user to chat room
    */
    function addChatRoomMembers($chatroom_id, $usernames){
        $url=$this->url.'chatrooms/'.$chatroom_id.'/users';
        $body=json_encode($usernames);
        $header=array($this->getToken());
        $result=$this->postCurl($url, $body, $header);
        return $result;
    }

    /*
        remove a user from chat room
    */
    function deleteChatRoomMember($chatroom_id, $username){
        $url=$this->url.'chatrooms/'.$chatroom_id.'/users/'.$username;
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'DELETE');
        return $result;
    }

    /*
        remove multiple users from chat room
    */
    function deleteChatRoomMembers($chatroom_id, $usernames){
        $url=$this->url.'chatrooms/'.$chatroom_id.'/users/'.$usernames;
        //$body=json_encode($usernames);
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, 'DELETE');
        return $result;
    }

    /**
     *  Message History
     */

    /*
        get chat history without pagination
    */
    function getChatRecord($ql){
        if(!empty($ql)){
            $url=$this->url.'chatmessages?ql='.$ql;
        }else{
            $url=$this->url.'chatmessages';
        }
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, "GET");
        return $result;
    }

    /*
        get chat history with pagination
    */
    function getChatRecordForPage($ql, $limit=0, $cursor){
        if(!empty($ql)){
            $url=$this->url.'chatmessages?ql='.$ql.'&limit='.$limit.'&cursor='.$cursor;
        }
        $header=array($this->getToken());
        $result=$this->postCurl($url,'', $header, "GET");
        $cursor=$result["cursor"];
        $this->writeCursor("chatfile.txt", $cursor);
        //var_dump($GLOBALS['cursor'].'00000000000000');
        return $result;
    }


    /**
     *  $this->postCurl method
     */
    function postCurl($url,$body,$header,$type="POST") {

        // 1. create a curl resource
        $ch = curl_init();

        // 2. set URL and corresponding options
        curl_setopt($ch,CURLOPT_URL,$url);  // set url

        // 1) set header
        // array_push($header, 'Accept:application/json');
        // array_push($header,'Content-Type:application/json');
        // array_push($header, 'http:multipart/form-data');

        // set to false to only get response. (true, to get all including header)
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt ( $ch, CURLOPT_TIMEOUT,5); // set time limit to prevent infinite loop
        // set connection timeout time. value 0 is unlimit waiting
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
        // set curl_exec() to use the method of file streaming as return instead of sending directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 2) set request body
        if (count($body)>0) {
            //$b=json_encode($body,true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);    // HTTPS and POST
        }
        // set header
        if(count($header)>0){
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        }
        // set file upload settings
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    // check certificate source
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);        // verify certificate SSL encryption

        // 3) set request verb
        switch($type){
            case "GET":
                curl_setopt($ch,CURLOPT_HTTPGET,true);
                break;
            case "POST":
                curl_setopt($ch,CURLOPT_POST,true);
                break;
            case "PUT": // user a self-defined request to replace "GET" or "HEAD" for HTTP request. Can be use to operate "DELETE" or subtle action
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
                break;
            case "DELETE":
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
                break;
        }

        // 4) MUST include "User-Agent: " header character string in HTTP request

        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

        curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)' ); // simulate browser


        // 3. get URL and send it to browser
        $res=curl_exec($ch);
        $result=json_decode($res,true);

        // 4. close and release curl resources
        curl_close($ch);
        if(empty($result))
            return $res;
        else
            return $result;

    }
}
?>