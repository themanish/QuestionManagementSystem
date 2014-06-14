<?php
    require_once '../../includes/session.php';
    require_once '../../includes/database.php';
    require_once '../../includes/functions.php';
    
    if(!($session->is_logged_in() && $session->user_id != 1)){
        header('Location: ../index.php');
    }
?>

<?php include '../template/teacher-dash-header.php' ?>

    <div class="row">
		<div class="span3">
			<?php include '../template/teacher-vertical-menu.html' ?>
		</div>
		
		<?php if(isset($_GET['cmd'])){ ?>
		
		<?php if($_GET['cmd'] == 'add'){ ?>
		<!-- #### ADD NEW SUBJECTIVE QSN -->
		
		<div class="span9">
			<h2>Add New Subjective Question</h2>
			<script type="text/javascript">
            tinymce.init({
                selector: ".tinymce"
			});
            </script>
            
            <form method="post" action="execute.php">
            	<input type="hidden" name="add_subjective">
            	
            	<label>Question</label>
            	<textarea name="question" id="sub-qsn" required></textarea>
            	
            	<label>Answer</label>
                <textarea name="answer" style="width:100%" class="tinymce" id="sub-ans"></textarea>
                
				<div id="add-qsn-final-req">
					<select name="subject" id="subject" required onchange="showUnits(this.value)" id="subject">
                            <option value="">- Select Subject -</option>
                        <?php
                            $sql = "SELECT `id`, `sub_name`, `sub_code` FROM `subjects` WHERE id IN (SELECT `sub_id` FROM `sub_teacher` WHERE `user_id` = '{$session->user_id}')";
                            $result_set = $db->query($sql);
                            while($result = $db->fetch_assoc($result_set)){
                        ?>
                            <option value="<?php print($result['id']) ?>"><?php print($result['sub_code']." - ".$result['sub_name']) ?></option>
                        <?php } ?>
                        </select>
					<select name="unit" id="units" required></select>
					<select name="level" id="difficultyLevel" required>
						<option value="">- Level -</option>
						<option value="Easy">Easy</option>
						<option value="Medium">Medium</option>
						<option value="Hard">Hard</option>
					</select>
					<input type="submit" value="Add" class="btn">
					<input type="button" value="Cancel" class="btn" onclick="window.location.href='qsn_subjective.php'">
				</div>
			</form>	
		</div>
		
		<!-- #### END ADD NEW SUBJECTIVE QSN -->		
		<?php } else if ($_GET['cmd'] == 'edit' && isset($_GET['qsn_id'])){ ?>
		<!-- #### EDIT SUBJECTIVE QSN -->
		
		<div class="span9">
			<h2>Edit Subjective Question</h2>
			<script type="text/javascript">
            tinymce.init({
                selector: ".tinymce"
			});
            </script>
            <?php
            	$db->tbl_name = 'questions';
            	$result = $db->fetch_row($db->selectById('id', $_GET['qsn_id'])); 
            ?>
            <form method="post" action="execute.php">
            	<input type="hidden" name="edit_subjective">
            	<input type="hidden" name="qsn_id" value="<?php echo $_GET['qsn_id']; ?>">
            	<label>Question</label>
            	<textarea name="question" id="sub-qsn" required><?php print($result[4]);?></textarea>
            	
            	<label>Answer</label>
                <textarea name="answer" style="width:100%" class="tinymce" id="sub-ans"><?php print($result[5]);?></textarea>
                
				<div id="add-qsn-final-req">
					<select name="subject" id="subject" required onchange="showUnits(this.value)" id="subject">
                            <option value="">- Select Subject -</option>
                        <?php
                            $sql = "SELECT `id`, `sub_name`, `sub_code` FROM `subjects` WHERE id IN (SELECT `sub_id` FROM `sub_teacher` WHERE `user_id` = '{$session->user_id}')";
                            $result_set = $db->query($sql);
                            while($result1 = $db->fetch_assoc($result_set)){
                                 $db->tbl_name = 'chapters';
                                 $chapter = $db->fetch_row($db->selectById('id', $result[1]));
                        ?>
                            <option value="<?php print($result1['id']) ?>" <?php if($chapter[1] == $result1['id']){print('selected');}?>><?php print($result1['sub_code']." - ".$result1['sub_name']) ?></option>
                        <?php } ?>
                    </select>
					<select name="unit" id="units" required></select>
					<select name="level" id="difficultyLevel" required>
						<option value="">- Level -</option>
						<option value="Easy">Easy</option>
						<option value="Medium">Medium</option>
						<option value="Hard">Hard</option>
					</select>
					<input type="submit" value="Save" class="btn">
					<input type="button" value="Cancel" class="btn" onclick="window.location.href='qsn_subjective.php'">
				</div>
			</form>
		</div>
		
		<!-- #### END EDIT SUBJECTIVE QSN -->
		<?php } else {redirect('qsn_subjective.php');} ?>
		<?php } else { ?>
		<!-- #### VIEW SUBJECTIVE QSN LIST -->
		
		<div class="span9">
			<h2>Subjective Questions <a href="qsn_subjective.php?cmd=add" class="btn">Add New</a></h2>
            <table>
              <thead>
                <tr>
                  <th width="15"><input type="checkbox"></th>
                  <th>Question</th>
                  <th width="45">Tools</th>
                </tr>
              </thead>
              <tbody>
              <?php
              	$sql = "SELECT * FROM `questions` WHERE `type_id` = '1' AND `user_id` = '$session->user_id'";
              	$result_set = $db->query($sql);
              	if($db->num_rows($result_set) < 1){
                    echo "<tr><td class='center' colspan=3>No entries Yet.</td></tr>"; 
                }
              	while($result = $db->fetch_assoc($result_set)){ 
              ?>
                <tr>
                  <td><input type="checkbox"></td>
                  <td><?php print($result['question']) ?></td>
                  <td class="center">
                  	<a href="qsn_subjective.php?cmd=edit&qsn_id=<?php print($result['id'])?>" title="Edit"><i class="icon-pencil"></i></a>
                  	&nbsp;
                  	<a href="execute.php?del_single_sub=<?php print($result['id']) ?>" title="Delete" onclick="return confirm('Confirm Delete')"><i class="icon-trash"></i></a>
                  </td>
                </tr>
              <?php } ?>
              </tbody>
            </table>		
		</div>
		<?php } ?>
	</div>
</div>
<div id="modal" style="display:none">
    <form action="../admin/execute.php" method="post" class="center">
        <h2 class="center">Change Password</h2>
        <input type="password" name="password" placeholder="New Password" required>
        <input type="submit" name="changePassword" value="Change Password" class="btn"/>
        <input type="button" value="Cancel" id="close" class="btn">
    </form>
</div>
</body>
</html>
