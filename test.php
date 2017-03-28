<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hyphenate API testing</title>
</head>

<body>
<div id="container">
    <div id="content">
        <?php
        include "hyphenate.class.php";

        $options['client_id']='YXA68E7DkM4uEeaPwTPbScypMA';
        $options['client_secret']='YXA63_RZdbtXQB9QZsizSCgMC70_4Rs';
        $options['org_name']='hyphenatedemo';
        $options['app_name']='demo';

        $h=new Hyphenate($options);

        $i=70;
        switch($i){

            case 10:    // get authentication token
                $token=$h->getToken();
                var_dump($token);
                break;
            case 11:    // create a Hyphenate IM account
                var_dump($h->createUser("user001","123456"));
                break;
            case 12:    // create multiple Hyphenate IM accounts
                var_dump($h->createUsers(array(
                    array(
                        "username"=>"user002",
                        "password"=>"123456"
                    ),
                    array(
                        "username"=>"user003",
                        "password"=>"123456"
                    )
                )));
                break;
            case 13:    // reset user password
                var_dump($h->resetPassword("user001","123456"));
                break;
            case 14:    // get a user
                var_dump($h->getUser("user001"));
                break;
            case 15:    // get multiple users without pagination, default 10 users at a time
                var_dump($h->getUsers());
                break;
            case 16:    // get multiple users with pagination, default 10 users at a time
                $cursor=$h->readCursor("userfile.txt");
                var_dump($h->getUsersForPage(10, $cursor));
                break;
            case 17:    // delete a user
                var_dump($h->deleteUser("user003"));
                break;
            case 18:    // delete multiple users
                var_dump($h->deleteUsers(2));
                break;
            case 19:    // update user's nickname
                var_dump($h->editNickname("user001", "userNickname001"));
                break;
            case 20:    // add friend to user
                var_dump($h->addFriend("user001", "user002"));
                break;
            case 21:    // remove friend from user
                var_dump($h->deleteFriend("user001", "user002"));
                break;
            case 22:    // get a list of friends of user
                var_dump($h->showFriends("user001"));
                break;
            case 23:    // get blacklist of user
                var_dump($h->getBlacklist("user001"));
                break;
            case 24:    // block user (add user to blacklist) for user
                $usernames=array(
                    "usernames"=>array("user002", "user003")
                );
                var_dump($h->addUserForBlacklist("user001", $usernames));
                break;
            case 25:    // unblock user (remove user from blacklist) for user
                var_dump($h->deleteUserFromBlacklist("user001","user002"));
                break;
            case 26:    // get user's online status
                var_dump($h->isOnline("user001"));
                break;
            case 27:    // get user's offline messages (undelivered messages)
                var_dump($h->getOfflineMessages("user001"));
                break;
            case 28:    // get offline message (undelivered message) status
                var_dump($h->getOfflineMessageStatus("user001","77225969013752296_pd7J8-20-c3104"));
                break;
            case 29:    // deactivate user account
                var_dump($h->deactiveUser("user001"));
                break;
            case 30:    // activate user account
                var_dump($h->activeUser("user001"));
                break;
            case 31:    // force user logout
                var_dump($h->disconnectUser("user001"));
                break;
            case 32:    // upload file
                var_dump($h->uploadFile("./resources/media/bird.jpg"));
                //var_dump($h->uploadFile("./resources/media/betterman.mp3"));
                //var_dump($h->uploadFile("./resources/media/cow.mp4"));
                break;
            case 33:    // download file
                var_dump($h->downloadFile('01adb440-7be0-11e5-8b3f-e7e11cda33bb','Aa20SnvgEeWul_Mq8KN-Ck-613IMXvJN8i6U9kBKzYo13RL5'));
                break;
            case 34:    // download thumbnail
                var_dump($h->downloadThumbnail('01adb440-7be0-11e5-8b3f-e7e11cda33bb','Aa20SnvgEeWul_Mq8KN-Ck-613IMXvJN8i6U9kBKzYo13RL5'));
                break;
            case 35:    // send text message
                $from='admin';
                $target_type="users";
                //$target_type="chatgroups";
                $target=array("user001", "user002", "user003");
                //$target=array("122633509780062768");
                $content="Hello there!";
                $ext['a']="a";
                $ext['b']="b";
                var_dump($h->sendText($from, $target_type, $target, $content, $ext));
                break;
            case 36:    // send command message
                $from='admin';
                $target_type="users";
                //$target_type="chatgroups";
                $target=array("user001", "user002", "user003");
                //$target=array("122633509780062768");
                $action="Hello there!";
                $ext['a']="a";
                $ext['b']="b";
                var_dump($h->sendCmd($from, $target_type, $target, $action, $ext));
                break;
            case 37:    // send image file
                $filePath="./resources/media/bird.jpg";
                $from='admin';
                $target_type="users";
                $target=array("user001", "user002");
                $filename="bird.jpg";
                $ext['a']="a";
                $ext['b']="b";
                var_dump($h->sendImage($filePath, $from, $target_type, $target, $filename, $ext));
                break;
            case 38:    // send audio file
                $filePath="./resources/media/betterman.mp3";
                $from='admin';
                $target_type="users";
                $target=array("user001", "user002");
                $filename="betterman.mp3";
                $length=10;
                $ext['a']="a";
                $ext['b']="b";
                var_dump($h->sendAudio($filePath, $from="admin", $target_type, $target, $filename, $length, $ext));
                break;
            case 39:    // send video file
                $filePath="./resources/media/cow.mp4";
                $from='admin';
                $target_type="users";
                $target=array("user001", "user002");
                $filename="cow.mp4";
                $length=10; // time duration
                $thumb='https://api.hyphenate.io/hyphenatedemo/demo/chatfiles/c06588c0-7df4-11e5-932c-9f90699e6d72';
                $thumb_secret='wGWIyn30EeW9AD1fA7wz23zI8-dl3PJI0yKyI3Iqk08NBqCJ';
                $ext['a']="a";
                $ext['b']="b";
                var_dump($h->sendVideo($filePath, $from="admin", $target_type, $target, $filename, $length, $thumb, $thumb_secret,$ext));
                break;
            case 40:    // send file

                break;
            case 41:    // get all the groups without pagination, default 10 groups
                var_dump($h->getGroups());
                break;
            case 42:    // get all the groups with pagination
                $cursor=$h->readCursor("groupfile.txt");
                var_dump($h->getGroupsForPage(2, $cursor));
                break;
            case 43:    // get group(s) details
                $group_ids=array("1445830526109","1445833238210");
                var_dump($h->getGroupDetail($group_ids));
                break;
            case 44:    // create a group
                $options ['groupname'] = "group001";
                $options ['desc'] = "this is a love group";
                $options ['public'] = true;
                $options ['owner'] = "user001";
                $options['members']=Array("user003","user002");
                var_dump($h->createGroup($options));
                break;
            case 45:    // update group description
                $group_id="124113058216804760";
                $options['groupname']="group002";
                $options['description']="this is group description";
                $options['maxusers']=300;
                var_dump($h->modifyGroupInfo($group_id,$options));
                break;
            case 46:    // delete a group
                $group_id="124113058216804760";
                var_dump($h->deleteGroup($group_id));
                break;
            case 47:    // get group members
                $group_id="122633509780062768";
                var_dump($h->getGroupUsers($group_id));
                break;
            case 48:    // add a user to group
                $group_id="122633509780062768";
                $username="user002";
                var_dump($h->addGroupMember($group_id,$username));
                break;
            case 49:    // add multiple users to group
                $group_id="122633509780062768";
                $usernames['usernames']=array("user003", "user002");
                var_dump($h->addGroupMembers($group_id, $usernames));
                break;
            case 50:    // delete a group member
                $group_id="122633509780062768";
                $username="test";
                var_dump($h->deleteGroupMember($group_id, $username));
                break;
            case 51:    // delete multiple group members
                $group_id="122633509780062768";
                // $usernames['usernames']=array("user003", "user002");
                $usernames='user003, user002';
                var_dump($h->deleteGroupMembers($group_id, $usernames));
                break;
            case 52:    // get a list of groups user joined
                var_dump($h->getGroupsForUser("user001"));
                break;
            case 53:    // transfer group ownership
                $group_id="122633509780062768";
                $options['newowner']="user002";
                var_dump($h->changeGroupOwner($group_id, $options));
                break;
            case 54:    // get the blacklist of group
                $group_id="122633509780062768";
                var_dump($h->getGroupBlacklist($group_id));
                break;
            case 55:    // block a user (add user to group blacklist)
                $group_id="122633509780062768";
                $username="user002";
                var_dump($h->addGroupMemberToBlacklist($group_id, $username));
                break;
            case 56:    // block multiple users (add user to group blacklist)
                $group_id="122633509780062768";
                $usernames['usernames']=array("user002","user003");
                var_dump($h->addGroupMembersToBlacklist($group_id, $usernames));
                break;
            case 57:    // unblock a user (remove user from group blacklist)
                $group_id="122633509780062768";
                $username="user002";
                var_dump($h->removeGroupMemberFromBlacklist($group_id, $username));
                break;
            case 58:    // unblock multiple users (remove users from group blacklist)
                $group_id="122633509780062768";
                $usernames['usernames']=array("user003", "user002");
                var_dump($h->removeGroupMembersFromBlacklist($group_id, $usernames));
                break;
            case 59:    // create chat room
                $options ['name'] = "chatroom001";
                $options ['description'] = "this is a chat room";
                $options ['maxusers'] = 300;
                $options ['owner'] = "user001";
                $options['members']=Array("man", "user002");
                var_dump($h->createChatRoom($options));
                break;
            case 60:    // update chat room description
                $chatroom_id="124121390293975664";
                $options['name']="chatroom002";
                $options['description']="this is chat room description";
                $options['maxusers']=300;
                var_dump($h->modifyChatRoom($chatroom_id, $options));
                break;
            case 61:    // delete chat room
                $chatroom_id="124121390293975664";
                var_dump($h->deleteChatRoom($chatroom_id));
                break;
            case 62:    // get all the chat rooms
                var_dump($h->getChatRooms());
                break;
            case 63:    // get chat room details
                $chatroom_id="124121939693277716";
                var_dump($h->getChatRoomDetail($chatroom_id));
                break;
            case 64:    // get a list of chat room user joined
                var_dump($h->getChatRoomJoined("user001"));
                break;
            case 65:    // add a member to chat room
                $chatroom_id="124121939693277716";
                $username="user001";
                var_dump($h->addChatRoomMember($chatroom_id, $username));
                break;
            case 66:    // add multiple user to chat room
                $chatroom_id="124121939693277716";
                $usernames['usernames']=array('user003','user002');
                var_dump($h->addChatRoomMembers($chatroom_id, $usernames));
                break;
            case 67:    // remove a user from chat room
                $chatroom_id="124121939693277716";
                $username="user001";
                var_dump($h->deleteChatRoomMember($chatroom_id, $username));
                break;
            case 68:    // remove multiple users from chat room
                $chatroom_id="124121939693277716";
                //$usernames['usernames']=array('user001', 'user002');
                $usernames='user001, user002';
                var_dump($h->deleteChatRoomMembers($chatroom_id, $usernames));
                break;
            case 69:    // get chat history without pagination
                $ql="select+*+where+timestamp>1435536480000";
                var_dump($h->getChatRecord($ql));
                break;
            case 70:    // get chat history with pagination
                $ql="select+*+where+timestamp>1435536480000";
                $cursor=$h->readCursor("chatfile.txt");
                //var_dump($h->$cursor);
                var_dump($h->getChatRecordForPage($ql, 10, $cursor));
                break;
        }
        ?>
    </div>
</div>
</body>
</html>