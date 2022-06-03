<?php
header('Content-type: application/json');
require_once('config.php');


if (isset($data['action']))
{
    switch ($data['action']) {
        /* Auth uUsers */
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
        /* Profiles */
        case 'profile_add':
            $name = $data['name'];
            $middlename = $data['middlename'];
            $surname = $data['surname'];
            $img = $_FILES['img'];
            $job_title_id = $data['job_title'];
            $specialization_id = $data['job_specialization'];
            profile_add($name, $middlename, $surname, $img, $job_title_id, $specialization_id);
            break;
        case 'profiles_list':
            profiles_list();
            break;
        case 'profile_get':
            $id = $data['id'];
            profile_get($id);
            break;
        case 'profile_edit':
            $id = $data['id'];
            $name = $data['name'];
            $middlename = $data['middlename'];
            $surname = $data['surname'];
            $img = $data['img'];
            $job_title_id = $data['job_title'];
            $specialization_id = $data['specialization'];
            profile_edit($id, $name, $middlename, $surname, $img, $job_title_id, $specialization_id);
            break;
        case 'profile_delete':
            $id = $data['id'];
            profile_delete($id);
            break;
        /* Job titles */
        case 'job_titles_add':
            $name = $data['name'];
            job_titles_add($name);
            break;
        case 'job_titles_list':
            job_titles_list();
            break;
        case 'job_titles_edit':
            $id = $data['id'];
            job_titles_edit($id);
            break;
        case 'job_titles_delete':
            $id = $data['id'];
            job_titles_delete($id);
            break;
        /* Specializations */
        case 'specializations_add':
            $name = $data['name'];
            specializations_add($name);
            break;
        case 'specializations_list':
            specializations_list();
            break;
        case 'specializations_edit':
            $id = $data['id'];
            specializations_edit($id);
            break;
        case 'specializations_delete':
            $id = $data['id'];
            specializations_delete($id);
            break;
        /* Profiles sort */
        case 'profiles_by_job_title':
            $id = $data['id'];
            profiles_by_job_title($id);
            break;
        case 'profiles_by_specialization':
            $id = $data['id'];
            profiles_by_specialization($id);
            break;
        /* Navigation */
        case 'nav_list':
            if (isset($data['isAdmin'])) $isAdmin = $data['isAdmin'];
            else $isAdmin = 0;
            nav_list($isAdmin);
            break;
        case 'nav_add':
            $title = $data['title'];
            $url = $data['url'];
            $order = $data['order'];
            $isAdmin = $data['isAdmin'];
            nav_add($title, $url, $order, $isAdmin);
            break;
        case 'nav_delete':
            $id = $data['id'];
            nav_delete($id);
            break;
        case 'nav_edit':
            $id = $data['id'];
            $title = $data['title'];
            $url = $data['url'];
            if (isset($data['order'])) $order = $data['isAdmin'];
            else $order = 999;
            if (isset($data['isAdmin'])) $isAdmin = $data['isAdmin'];
            else $isAdmin = 0;
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
        $response = md5(md5($password));
    } else {
        $response = false;
    }
    echo json_encode($response);
}
function list_users(){
    global $db;
    $stmt = $db->prepare("SELECT id, user, creation_date FROM `auth-users`");
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($list !=true ) echo 'No users ;-/';
    else echo json_encode($list);
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
function profile_add($name, $middlename, $surname, $img, $job_title_id, $specialization_id){
    global $db;
    $query = "INSERT INTO `profiles` (name, middlename, surname, job_title_id, specialization_id, date_added) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $insert = $db->prepare($query);
    $insert->execute(array(
        $name, $middlename, $surname, $job_title_id, $specialization_id
    ));
    $id = $db->lastInsertId();
    $filename = explode('.', $img['name']);
    $filename = $id . '-' . $surname . '-' . $name . '-' . $middlename . '.' . $filename[1];
    $file = IMGPATH . $filename;
    if ( file_put_contents($file, file_get_contents($img['tmp_name'])) )
    {
        $query = "UPDATE `profiles` SET img=:img WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->execute(['img' => $filename,  'id' => $id]);
    }
    echo 'profile added, id #' . $id;

}
function profiles_list(){
    global $db;
    $stmt = $db->prepare(
        "SELECT profiles.id, profiles.name, middlename, surname, img, date_added, 
        index_job_titles.name as job_title_name, profiles.job_title_id, 
        index_specializations.name as specialization_name, profiles.specialization_id 
        FROM `profiles` 
        LEFT JOIN `index_job_titles` ON profiles.job_title_id = index_job_titles.id 
        LEFT JOIN `index_specializations` ON profiles.specialization_id = index_specializations.id"
    );
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ( $list != true ) echo 'No profiles ;-/';
    else 
    {
        echo json_encode(profiles_prepare($list));
    }
}
function profiles_prepare($array){
    if ( !isset($array[0]['img']) ) $output[] = $array;
    else $output = $array;
    foreach ($output as &$v)
    {
        $v['img'] = IMGPATH . $v['img'];
    }
    return $output;
}
function profile_get($id){
    global $db;
    $stmt = $db->prepare(
        "SELECT profiles.id, profiles.name, middlename, surname, img, date_added, 
        index_job_titles.name as job_title_name, 
        index_specializations.name as specialization_name 
        FROM `profiles` 
        LEFT JOIN `index_job_titles` ON profiles.job_title_id = index_job_titles.id 
        LEFT JOIN `index_specializations` ON profiles.specialization_id = index_specializations.id
        WHERE profiles.id=?"
    );
    $stmt->execute([$id]);
    $list = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($list !=true ) echo 'No profiles ;-/';
    else echo json_encode(profiles_prepare($list));
}
function profile_edit($id, $name, $middlename, $surname, $img, $job_title_id, $specialization_id){
    global $db;
    $stmt = "UPDATE `profiles` SET name=?, middlename=?, surname=?, img=?, job_title_id=?, specialization_id=? WHERE id=?";
    $stmt = $db->prepare($stmt); 
    $stmt->execute([$name, $middlename, $surname, $img,  $job_title_id, $specialization_id, $id]);
    if ($stmt->rowCount()) echo "Updated profile id #$id";
    else echo "No profile updated";
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
/* Job titles */
function job_titles_add($name){
    global $db;
    $query = "INSERT INTO `index_job_titles` (name) VALUES (?)";
    $insert = $db->prepare($query);
    $insert->execute([$name]);
    echo 'job title added';

}
function job_titles_list(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `index_job_titles`");
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($list !=true ) echo 'No job titles ;-/';
    else echo json_encode($list);
}
function job_titles_edit($id){
    
}
function job_titles_delete($id){
    global $db;
    $query = "DELETE FROM `index_job_titles` WHERE  id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if( ! $stmt->rowCount() ) echo "Error: Deletion failed";
    else echo 'job title removed';
}

/* Specializations */
function specializations_add($name){
    global $db;
    $query = "INSERT INTO `index_specializations` (name) VALUES (?)";
    $insert = $db->prepare($query);
    $insert->execute([$name]);
    echo 'specialization added';

}
function specializations_list(){
    global $db;
    $stmt = $db->prepare("SELECT * FROM `index_specializations`");
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($list !=true ) echo 'No specializations ;-/';
    else echo json_encode($list);
}
function specializations_edit($id){
    
}
function specializations_delete($id){
    global $db;
    $stmt = $db->prepare( "DELETE FROM `index_specializations` WHERE  id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if( ! $stmt->rowCount() ) echo "Error: Deletion failed";
    else echo 'specialization removed';
}
/* Profiles sort */
function profiles_by_job_title($id){
    global $db;
    $stmt = $db->prepare(
        "SELECT profiles.id, profiles.name, middlename, surname, img, date_added, 
        index_job_titles.name as job_title_name, 
        index_specializations.name as specialization_name 
        FROM `profiles`
        LEFT JOIN `index_job_titles` ON profiles.job_title_id = index_job_titles.id 
        LEFT JOIN `index_specializations` ON profiles.specialization_id = index_specializations.id
        WHERE profiles.job_title_id = :id"
    );
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($list !=true ) echo 'No profiles ;-/';
    else echo json_encode($list);
}
function profiles_by_specialization($id){
    global $db;
    $stmt = $db->prepare(
        "SELECT profiles.id, profiles.name, middlename, surname, img, date_added, 
        index_job_titles.name as job_title_name, 
        index_specializations.name as specialization_name 
        FROM `profiles`
        LEFT JOIN `index_job_titles` ON profiles.job_title_id = index_job_titles.id 
        LEFT JOIN `index_specializations` ON profiles.specialization_id = index_specializations.id
        WHERE profiles.specialization_id = :id"
    );
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($list !=true ) echo 'No profiles ;-/';
    else echo json_encode($list);
}
/* Profiles sort */
function sort_by_job_titles(){}
function sort_by_specializations(){}
/* Navigation */
function nav_list($isAdmin=0){
    global $db;
    $stmt = $db->prepare('SELECT * FROM `menu` WHERE isAdmin=? ORDER BY list_order');
    $stmt->execute([$isAdmin]);
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($list !=true ) echo 'No profiles ;-/';
    else echo json_encode($list);
}
function nav_add($title, $url, $order, $isAdmin=0){
    global $db;
    $query = "INSERT INTO `menu` (title, url, isAdmin, list_order) VALUES (?, ?, ?, ?)";
    $insert = $db->prepare($query);
    $insert->execute([$title, $url, $isAdmin, $order]);
    echo "menu '$title' added";
}
function nav_delete($id){
    global $db;
    $stmt = $db->prepare( "DELETE FROM `menu` WHERE  id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if( ! $stmt->rowCount() ) echo "Error: Deletion failed";
    else echo 'menu item removed';
}
function nav_edit($id, $title, $url, $order, $isAdmin=0){
    global $db;
    $stmt = $db->prepare("UPDATE `menu` SET title=?, url=?, isAdmin=?, list_order=? WHERE id=?"); 
    $stmt->execute([$title, $url, $isAdmin, $order, $id]);
    if ($stmt->rowCount()) echo "Updated profile id #$id";
    else echo "No profile updated";
}