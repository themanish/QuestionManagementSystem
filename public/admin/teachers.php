<?php
    require_once '../../includes/session.php';
    require_once '../../includes/database.php';
    require_once '../../includes/functions.php';
    if(!($session->is_logged_in() && $session->user_id == 1)) redirect('../index.php');
?>

<?php include '../template/admin-dash-header.php' ?>

<div class="row">
	<div class="span3"><?php include '../template/admin-vertical-menu.html' ?></div>
        
    <?php if(isset($_GET['cmd'])){ ?>
    <!-- #### Add New Teacher #### -->
    <?php if($_GET['cmd'] == 'add' || ($_GET['cmd'] == 'edit' && isset($_GET['user_id']))){ ?>
    <?php
        if($_GET['cmd'] == 'edit') {
        	$edit = TRUE;
        	$db->tbl_name = 'users';
        	if($_GET['user_id'] != 1)
        	    $result = $db->fetch_row($db->selectById('user_id', $_GET['user_id']));
        	else 
        	    redirect('index.php');
        } else {
        	$edit = FALSE; $result = FALSE;
        }
    ?>
    
    <div class="span9">
        <h2><?php if($edit) echo 'Edit'; else echo 'Add New'; ?> Teacher</h2>
        <form action="execute.php" method="post">
            <?php if($edit) {?>
            <input type="hidden" name="edit_teacher">
            <input type="hidden" name="user_id" value="<?php echo $result[0]?>">
            <?php } else { ?>
            <input type="hidden" name="add_teacher">
            <?php } ?>
            
            <label>Full Name</label><input type="text" name="name" value="<?php echo $result[1]?>" required>
            <label>Username</label><input type="text" name="username" value="<?php echo $result[2]?>" required>
            <label>Email</label><input type="email" name="email" value="<?php echo $result[3]?>" required>
            
            <label for="status">Status</label>
                <p><input type="radio" name="status" value="1" <?php if($result[6] == 1)echo 'checked';?> required>&nbsp; Enable<p>
                <p><input type="radio" name="status" value="0" <?php if($result[6] == 0)echo 'checked';?> required>&nbsp; Disable</p>    
            <br>
            <input type="submit" value="<?php if($edit) echo 'Save Changes'; else echo 'Add'; ?>" class="btn">
            <input type="button" value="Cancel" class="btn" onclick="window.location.href='teachers.php'">
            
        </form>
    </div>
    
    <?php } else if($_GET['cmd'] == 'assign' && isset($_GET['user_id'])) { ?>

    <!-- #### Assign Subjects Teacher #### -->
    
    <div class="span9">
        <h2>Assign Subjects</h2>
        <form action="execute.php" method="post">
            <input type="hidden" name="assign">
            <input type="hidden" name="user_id" value="<?php print($_GET['user_id']) ?>">
            <select name="faculty" id="faculty" required onchange="showSems(this.value)">
                <option value="">Select Faculty</option>
            <?php
                $db->tbl_name = 'faculties';
                $result_set = $db->selectAll();
                while($result = $db->fetch_assoc($result_set)){
            ?>
                <option value="<?php print($result['id']) ?>"><?php print($result['name']) ?></option>
            <?php } ?>
            </select>
            <span id="semester">
                <select name="semester" class="center" style="width: 120px" required>
                    <option value="">--</option>
                </select>
            </span>
            <span id="subjects">
                <select name="subject" class="center" required>
                    <option value="">--</option>
                </select>
            </span>
            <input type="submit" value="Assign" class="btn">
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Faculty</th>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Sub Code</th>
                    <th>Tools</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $result_set = $db->getSubjectsByUserId($_GET['user_id']);
                while($result = $db->fetch_assoc($result_set)){
            ?>
                <tr>
                    <td><?php                     
                        $result1 = $db->fetch_row($db->getFacultyNameBySubId($result['id']));
                        print($result1[0]);
                    ?></td>
                    <td>
                        <?php 
                        $result2 = $db->fetch_row($db->getSemesterBySubId($result['id']));
                        print($result2[0]);
                    ?></td>
                    <td><?php print($result['sub_name'])?></td>
                    <td><?php print($result['sub_code'])?></td>
                    <td><a href="#" title="Delete" onclick="return confirm('Service Not Available!')"><i class="icon-trash"></i></a></td>
                </tr>
                
            <?php } ?>
            </tbody>
        </table>
    </div>
    
    <?php } else { redirect('teachers.php'); }?>
            
    <?php } else { ?>
    <!-- #### View Teacher's List #### -->
            
	<div class="span9">
        <h2>Teachers <a href="teachers.php?cmd=add" class="btn">Add New</a></h2>
        <table>
           <thead>
             <tr>
               <th>Name</th>
               <th>Username</th>
               <th>Assigned Subjects</th>
               <th>Status</th>
               <th>Tools</th>
             </tr>
           </thead>
           <tbody>
           <?php
           $db->tbl_name = 'users';
           $result_set = $db->selectById('type', 'teacher');
           while($result = $db->fetch_assoc($result_set)){
           ?>
             <tr>
               <td><?php echo $result['name'] ?></td>
               <td><?php echo $result['username'] ?></td>
               <td class="center"><a href="teachers.php?cmd=assign&user_id=<?php print($result['user_id'])?>">View/Edit</a></td>
               <td class="center">
                   <?php 
                   if($result['status'] == 1) print('<span class="green-text">Enabled</span>');
                   else print('<span class="red-text">Disabled</span>');
                   ?>
               </td>
               <td class="center">
                    <a href="teachers.php?cmd=edit&user_id=<?php print($result['user_id'])?>" title="Edit"><i class="icon-pencil"></i></a>
                    &nbsp;
                    <a href="execute.php?del_teacher=&user_id=<?php print($result['user_id'])?>" title="Delete" onclick="return confirm('Warning! This will delete all questions updated by this teacher.')">
                        <i class="icon-trash"></i>
                    </a>
               </td>
             </tr>
           <?php } ?>
           </tbody>
         </table>
	</div>
            
    <?php } ?>
</div>

<?php include '../template/admin-footer.php' ?>