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
    
    <!-- ##### ADD & EDIT FACULTY & SEMESTER #### -->         
    <?php if($_GET['cmd'] == 'add' || ($_GET['cmd'] == 'edit' && isset($_GET['fac_id']))){ ?>    
    
    <?php 
    if($_GET['cmd'] == 'edit') {
        $edit = TRUE;
        $db->tbl_name = 'faculties';
        $result = $db->fetch_row($db->selectById('id', $_GET['fac_id']));
        $db->tbl_name = 'fac_sem';
        $query = $db->selectById('fac_id', $_GET['fac_id']);
        while($result1 = $db->fetch_array($query)){
            $sems[] = $result1['semester'];
        }
    } else {
        $edit = FALSE; $result = FALSE; $sems = FALSE;
    } 
    ?>
    
        <h2><?php if($edit) echo 'Edit'; else echo 'Add New';?> Faculty and Semesters</h2>                        
        <form action="execute.php" method="post">
            <?php if($edit){ ?>
            <input type="hidden" name="fac_id" value="<?php echo $_GET['fac_id']?>">
            <input type="hidden" name="facnsem_edit">
            <?php } else { ?>
            <input type="hidden" name="facnsem_add">
            <?php } ?>
            
            <label>Faculty Name</label><input type="text" name="facultyName" value="<?php echo $result[1]?>" required>

            <label>Semesters</label>
            <?php for($s=1; $s<=8; $s++){ ?>
                <input type="checkbox" name="semester<?php echo $s?>>" value="<?php echo $s?>" 
                <?php if($edit) foreach($sems as $key => $value)if($value == $s){echo 'disabled';}?>>
                &nbsp; <?php echo $s?> <br>
            <?php } ?>
            <br>
            <input type="submit" name="submit" value="<?php if($edit) echo 'Save Changes'; else echo 'Add';?>" class="btn">
            <input type="button" class="btn" value="Cancel" onclick="window.location.href='fs_facnsem.php'">
        </form>
    
    <?php } else {redirect('fs_facnsem.php');} ?>
    <!-- #### END ADD FACULTY & SEMESTERS #### -->    
                  
    <?php } else { ?>            
    <!-- #### VIEW FACULTY AND SEMESTERS #### -->
        
    <h2>Faculty and Semesters <a href="fs_facnsem.php?cmd=add" class="btn">Add New</a></h2>                        
    <table>
        <thead>
            <tr>
                <th>Faculty Name</th>
                <th width="45">Tools</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $db->tbl_name = 'faculties';
        $result_set = $db->selectAll();
        while($result = $db->fetch_array($result_set)){
        ?>
            <tr>
                <td><?php echo $result['name'] ?></td>
                <td class="center">
                    <a href="fs_facnsem.php?cmd=edit&fac_id=<?php echo $result['id'] ?>" title="Edit">
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