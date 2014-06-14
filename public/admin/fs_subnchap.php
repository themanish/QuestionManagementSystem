<?php
    require_once '../../includes/session.php';
    require_once '../../includes/database.php';
    require_once '../../includes/functions.php';
    if(!($session->is_logged_in() && $session->user_id == 1)) redirect('../index.php');
?>

<?php include '../template/admin-dash-header.php' ?>

<div class="row">
	<div class="span3"><?php include '../template/admin-vertical-menu.html' ?></div>
	<div class="span9">
    <?php if(isset($_GET['cmd'])){ ?>
    
        <!-- ##### ADD & EDIT SUBJECTS #### -->
        <?php if($_GET['cmd'] == 'addsub' || $_GET['cmd'] == 'editsub'){ ?>
        <?php
            if($_GET['cmd'] == 'editsub'){
            	$edit = TRUE;
            	$db->tbl_name = 'subjects';
            	$result = $db->fetch_row($db->selectById('id', $_GET['sub_id']));
            } else {
            	$edit = FALSE; $result = FALSE;
            }
        ?>
        
        <h2><?php if($edit) echo 'Edit'; else echo 'Add New';?> Subject</h2> 
        <form action="execute.php" method="post">
            <?php if($edit) { ?>
            <input type="hidden" name="sub_edit">
            <input type="hidden" name="sub_id" value="<?php echo $_GET['sub_id']?>">
            <?php } else { ?>
            <input type="hidden" name="sub_add">
            <?php } ?>
            
            <label>Subject Code</label><input type="text" name="subCode" value="<?php echo $result[2]?>" required>
            <label>Subject Name</label><input type="text" name="subName" value="<?php echo $result[3]?>" required>
            <label>Faculty</label>
            <select name="faculty" onchange="showSems(this.value)" required>
                <option value="">Select Faculty</option>
                <?php
                    $db->tbl_name = 'faculties';
                    $result_set = $db->selectAll();
                    while($result1 = $db->fetch_array($result_set)){
                        echo "<option value='$result1[0]'>$result1[1]</option>";
                    }  
                ?>
            </select>
            <label>Semester</label><div id="semester"></div>
            <br>
            <input type="submit" class="btn" value="<?php if($edit) echo 'Save Changes'; else echo 'Add Subject';?>">
            <input type="button" class="btn" value="Cancel" onclick="window.location.href='fs_subnchap.php'">
        </form>
            
        <!-- #### END OF ADD & EDIT SUBJECTS #### -->
        <?php } else if($_GET['cmd'] == 'addchap'){ ?>

        <!-- #### ADD & EDIT CHAPTERS #### -->       
        <h2>ADD & EDIT CHAPTERS <a href="fs_subnchap.php" class="btn">Go Back</a></h2>
        <div id="add-edit-chapters">
            <form action="execute.php" method="post">
                <input type="hidden" name="chap_add">
                <input type="hidden" name="sub_id" value="<?php echo $_GET['sub_id'] ?>">

                <label>Unit</label><input type="text" name="unit" required>
                <label>Title</label><input type="text" name="title" required>
                <input type="submit" value="Add" class="btn">
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Title</th>
                    <th>Tools</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $db->tbl_name = 'chapters';
            $result_set = $db->selectById('sub_id', $_GET['sub_id']);
            while($result = $db->fetch_assoc($result_set)){                                
            ?>
                <tr>
                    <td><?php echo $result['unit'] ?></td>
                    <td><?php echo $result['title'] ?></td>
                    <td class="center">
                        <a href="#" title="Edit" onclick="editChap(<?php echo $result['id'].",".$_GET['sub_id'] ?>)">
                            <i class="icon-pencil"></i>
                        </a>
                    </td>
                </tr>
                
            <?php } ?>
            </tbody>
        </table>
                
        <?php } else {redirect('fs_subnchap.php');} ?>       
        <!-- #### END ADD & EDIT CHAPTERS #### -->
        
    <?php } else { ?>
        
        <!-- #### SUBJECTS & CHAPTERS LIST #### -->            
        <h2>Subject <a href="fs_subnchap.php?cmd=addsub" class="btn">Add New</a> & Chapters</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sub Code</th>
                        <th>Subject</th>
                        <th>Chapters</th>
                        <th>Faculty</th>
                        <th>Semester</th>
                        <th>Tools</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $db->tbl_name = 'subjects';
                $result_set = $db->selectAll();
                while($result = $db->fetch_assoc($result_set)){
                ?>                               
                <tr>
                    <td><?php echo $result['sub_code'] ?></td>
                    <td><?php echo $result['sub_name'] ?></td>
                    <td class="center"><a href="fs_subnchap.php?cmd=addchap&sub_id=<?php print($result['id'])?>">View/Edit Chapters</a></td>
                    <td><?php
                        $result1 = $db->fetch_row($db->getFacNameByFacSemId($result['fac_sem_id']));
                        echo $result1[0];
                    ?></td>
                    <td><?php
                        $db->tbl_name = 'fac_sem';
                        $result2 = $db->fetch_row($db->selectById('id', $result['fac_sem_id']));
                        echo $result2[2];
                    ?>    
                    </td>
                    <td class="center">
                        <a href="fs_subnchap.php?cmd=editsub&sub_id=<?php echo $result['id']?>" title="Edit">
                            <i class="icon-pencil"></i>
                        </a>
                    </td>
                </tr>
            
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>        
    </div>
</div>

<?php include '../template/admin-footer.php' ?>