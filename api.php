<?php
header('Content-type: application/json');
require_once('config.php');

// $data = json_decode( file_get_contents('php://input'), true );

/* TEST action profile_add, list_users & profiles_list */
$data['action'] = 'profile_add';
$data['action'] = 'list_users';
$data['action'] = 'profiles_list';
$data['name'] = 'Petro';
$data['middlename'] = 'Mikhailovich';
$data['surname'] = 'Petrenko';
$data['img'] = 'img-profiles/temp.png';
$data['job_title'] = 'CEO';
$data['specialization'] = 'Pharmacology';
/* END TEST */

if ($data['action'])
{
    switch ($data['action']) {
        case 'validate_user':
            $login = $data['user'];
            $password = $data['password'];
            validate_user($login, $password);
            break;
        case 'add_user':
            $login = $data['user'];
            $password = $data['password'];
            add_user($login, $password);
            break;
        case 'list_users':
            list_users();
            break;
        case 'remove_user':
            $login = $data['user'];
            remove_user($login);
            break;
        case 'profile_add':
            $name = $data['name'];
            $middlename = $data['middlename'];
            $surname = $data['surname'];
            $img = $data['img'];
            $job_title = $data['job_title'];
            $specialization = $data['specialization'];
            profile_add($name, $middlename, $surname, $img, $job_title, $specialization);
            break;
        case 'profiles_list':
            profiles_list();
            break;
        case 'profile_edit':
            $id = $data['id'];
            profile_edit($id);
            break;
        case 'profile_delete':
            $id = $data['id'];
            profile_delete($id);
            break;
        default:
            echo 'Nothing to see here';
    }
}
else
{
    echo 'ERROR: Missing required \'action\'.';
}

/* Authentication */
function add_user($login, $password){
    global $db;
    $query = "INSERT INTO `auth-users` (user, hash, creation_date) VALUES (?, ?, NOW())";
    $insert = $db->prepare($query);
    $insert->execute(array(
        $login, md5($password)
    ));
    echo 'user added';
}
function validate_user($login, $password){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `auth-users` WHERE user = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && ( md5($password) == $user['hash'] ) )
    {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
    echo json_encode($response);
}
function list_users(){
    global $db;
    $stmt = $db->prepare("SELECT id, user, creation_date FROM `auth-users`");
    $stmt->execute();
    $profiles = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($profiles);
}
function remove_user($login){
    global $db;
    $query = "DELETE FROM `auth-users` WHERE  user=:user";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user', $login);
    $stmt->execute();
    if( ! $stmt->rowCount() ) echo "Error: Deletion failed";
    else echo 'User removed';
}
/* Profiles */
function profile_add($name, $middlename, $surname, $img, $job_title, $specialization){
    global $db;
    $query = "INSERT INTO `profiles` (name, middlename, surname, img, job_title, specialization, date_added) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $insert = $db->prepare($query);
    $insert->execute(array(
        $name, $middlename, $surname, $img, $job_title, $surname
    ));
    echo 'user added';

}
function profiles_list(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `profiles`");
    $stmt->execute();
    $profiles = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($profiles);
}
function profile_edit($id){
    
}
function profile_delete($id){
    global $db;
    $query = "DELETE FROM `profiles` WHERE  id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if( ! $stmt->rowCount() ) echo "Error: Deletion failed";
    else echo 'Profile removed';
}


