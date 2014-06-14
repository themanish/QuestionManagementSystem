<?php
require_once '../../includes/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/session.php';

if($_POST!=NULL || $_GET!=NULL){
    
if(isset($_POST['facnsem_add']) || isset($_POST['facnsem_edit'])){
    $_POST = $db->escape_value_array($_POST);    
    if(isset($_POST['facnsem_edit'])) $edit = TRUE; else $edit = FALSE;
    
    $db->tbl_name = 'faculties';
    $db->data = array('name' => $_POST['facultyName']);
    if($edit){ 
        $db->condition = array('id' => $_POST['fac_id']);
        $db->update();
        $fac_id = $_POST['fac_id'];
    } else {
        $db->insert();
        $fac_id = mysql_insert_id();
    }

    foreach($_POST as $key => $value){
        if(strpos($key, 'semester') !== FALSE){$sems[] = $value;}
    }
    $i = 0;
    while($sems){
        $db->tbl_name = 'fac_sem';
        $db->data = array('fac_id' => $fac_id, 'semester' => $sems[$i]);
        $db->insert();
        unset($sems[$i]); $i++;
    }
    redirect('fs_facnsem.php');
}


if(isset($_POST['sub_add']) || isset($_POST['sub_edit'])){
    $_POST = $db->escape_value_array($_POST);
    if(isset($_POST['sub_edit'])) $edit = TRUE; else $edit = FALSE;

    $result = $db->fetch_row($db->getFacSemIdByFacIdnSem($_POST['faculty'], $_POST['semester']));
    $db->tbl_name = 'subjects';
    $db->data = array('fac_sem_id' => $result[0], 'sub_code' => $_POST['subCode'], 'sub_name' => $_POST['subName']);
    if($edit){
        $db->condition = array('id' => $_POST['sub_id']);
        $db->update();
    } else $db->insert();
    redirect('fs_subnchap.php');
}

if(isset($_POST['chap_add'])){
    $_POST = $db->escape_value_array($_POST);
    $db->tbl_name = 'chapters';
    $db->data = array('sub_id' => $_POST['sub_id'], 'unit' => $_POST['unit'], 'title' => $_POST['title']);
    $db->insert();
    header("location: fs_subnchap.php?cmd=addchap&sub_id={$_POST['sub_id']}");
}

if(isset($_GET['chap_ajax_edit'])){
    $_POST = $db->escape_value_array($_POST);    
    $db->tbl_name = 'chapters';
    $result = $db->fetch_row($db->selectById('id', $_GET['chap_id']));
    echo "
        <form action='execute.php' method='post'>
            <input type='hidden' name='chap_edit'>
            <input type='hidden' name='chap_id' value='{$_GET['chap_id']}'>
            <input type='hidden' name='sub_id' value='{$_GET['sub_id']}'>
            <label>Unit</label><input type='text' name='unit' value='{$result[2]}' required>
            <label>Title</label><input type='text' name='title' value='{$result[3]}' required>
            <input type='submit' value='Save' class='btn'>
        </form>
    ";
}

if(isset($_POST['chap_edit'])){
    $_POST = $db->escape_value_array($_POST);
    $db->tbl_name = 'chapters';
    $db->data = array('unit' => $_POST['unit'], 'title' => $_POST['title']);
    $db->condition = array('id' => $_POST['chap_id']);
    $db->update();
    header("location: fs_subnchap.php?cmd=addchap&sub_id={$_POST['sub_id']}");
}

if(isset($_GET['ajax'])){
    $_GET = $db->escape_value_array($_GET);
    if(isset($_GET['semester'])){
        $result = $db->fetch_row($db->getFacSemIdByFacIdnSem($_GET['fac_id'], $_GET['semester']));
        $db->tbl_name = 'subjects';
        $result_set = $db->selectById('fac_sem_id', $result[0]);
        echo '<select name="subject">';
        echo '<option value="">Select Subject</option>';
        while ($result = $db->fetch_assoc($result_set)){
            echo "<option value={$result['id']}>{$result['sub_name']}</option>";
        }
        echo '</select>';
        
    } else if (isset($_GET['fac_id'])){
        $db->tbl_name = 'fac_sem';
        $result_set = $db->selectById('fac_id', $_GET['fac_id']);
        echo '<select name="semester" id="sem" style="width: 120px" required onchange="showSubs(this.value)">';
        echo '<option value="">Select Sem</option>';
            while($result = $db->fetch_array($result_set)){
                echo "<option value={$result['semester']}>{$result['semester']}</option>";
            }
        echo '</select>';
    }
}

if(isset($_POST['add_teacher']) || isset($_POST['edit_teacher'])){
    if(isset($_POST['edit_teacher'])) $edit = TRUE; else $edit = FALSE;
    $_POST = $db->escape_value_array($_POST);
    $db->tbl_name = 'users';
    $db->data = array('name' => $_POST['name'], 'username' => $_POST['username'], 'password' => sha1($_POST['username']), 'email' => $_POST['email'], 'type' => 'Teacher', 'status' => $_POST['status']);
    if($edit){
        $db->condition = array('user_id' => $_POST['user_id']);
        $db->update();
    } else $db->insert();
    redirect('teachers.php');
}

if(isset($_GET['del_teacher'])){
    $db->tbl_name = 'users';
    $db->condition = array('user_id'=>$_GET['user_id']);
    $db->delete();
    redirect('teachers.php');
}

if(isset($_POST['assign'])){
    $db->tbl_name = 'sub_teacher';
    $db->data = array('sub_id' => $_POST['subject'], 'user_id' => $_POST['user_id']);
    $db->insert();   
    redirect("teachers.php?cmd=assign&user_id={$_POST['user_id']}");
}

if(isset($_POST['changePassword'])){
    $db->tbl_name = 'users';
    $db->data = array('password' => sha1($_POST['password']));
    $db->condition = array('user_id' => $session->user_id);
    $db->update();
    redirect('../index.php?logout=1&message=Login using New Password');
}

} else {redirect('Location: index.php');}


