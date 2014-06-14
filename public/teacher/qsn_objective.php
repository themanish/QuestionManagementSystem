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
		
		<?php if($_GET['cmd'] == 'add' || $_GET['cmd'] == 'edit'){ ?>
		
		  <?php 
		  if($_GET['cmd'] == 'edit'){ 
		      $edit = TRUE;
		      $db->tbl_name = 'questions';
		      $question = $db->fetch_row($db->selectById('id', $_GET['qsn_id']));
		      $db->tbl_name = 'question_type';
		      $type = $db->fetch_row($db->selectById('id', $question[2]));
		  } else {
		      $edit = FALSE;
		      $question = FALSE;
		      $answer = FALSE;
		  } 
		  ?> 
		<!-- #### ADD NEW OBJECTIVE QSN -->
		
		<div class="span9" id="add-obj-qsn">
			<h2><?php if($edit) print('Edit'); else print('Add New'); ?> Objective Question</h2>
			
			<form method="post" action="execute.php">
			<input type="hidden" name="<?php if($edit) print('edit_objective'); else print('add_objective'); ?>">
			<?php if($edit){ ?>
			<input type="hidden" name="qsn_id" value="<?php print($_GET['qsn_id']);?>">
			<input type="hidden" name="qsnType" value="<?php print($type[0]); ?>">
			<?php } ?>
			<p>
			<?php if($edit){?>
			<select id="qsnType" disabled><option><?php print($type[1]); ?></option></select> 
			<?php } else { ?>
			<select id="qsnType" name="qsnType" required autofocus onchange="getQsn(this.value)">
				<option value="">- Select Type -</option>
				<?php
				    $sql = "SELECT * FROM `question_type` WHERE `id` > '1' ";
				    $result_set = $db->query($sql);
				    while($result = $db->fetch_assoc($result_set)){ 
				?>
				<option value="<?php print($result['id'])?>"><?php print($result['type'])?></option>
				<?php } ?>
			</select>
			<?php } ?>
			<select id="marks" name="marks" required>
			    <option value="">- Marks -</option>
				<option value="1" <?php if($question[7] == 1)print('selected')?>>1 Marks</option>
				<option value="2" <?php if($question[7] == 2)print('selected')?>>2 Marks</option>
			</select> 
			</p>
			<hr>
			<?php if($edit){ ?>
			
			<div id="obj-qsn-box">
			     <table>
    	            <tr>
        	            <td>
        	            <textarea name='question' class='mainStmt' required placeholder='Main Statement of Question Here...'><?php print($question[4])?></textarea>
        	            </td>
    	            </tr>
    	            <?php if($question[2] == 2 || $question[2] == 5 || $question[2] == 6){
    	                $db->tbl_name = 'question_statements';
    	                $stmts = $db->fetch_row($db->selectById('qsn_id', $_GET['qsn_id']));
    	                foreach($stmts as $k => $v){
    	                	if($v == '' || $k == 0) continue;
    	                	$new_stmts[] = $v;
    	                }
    	                
    	                for ($i=0; $i<=(count($new_stmts)-1); $i++){ 
    	            ?>
    	            <tr id='Stmt<?php print($i+1);?>'>
	                   <td>
			     		  <span class='add-on'><?php print($i+1);?>.</span>
	                      <textarea name='stmt1' class='miniStmt' required><?php print($new_stmts[$i]); ?></textarea>    
					   </td>
	                </tr>
    	            <?php }} else if ($question[2] == 4) { 
    	                $db->tbl_name = 'question_statements';
    	                $stmts = $db->fetch_row($db->selectById('qsn_id', $_GET['qsn_id']));
    	                foreach($stmts as $k => $v){
    	                	if($v == '' || $k == 0) continue;
    	                	$new_stmts[] = $v;
    	                }
    	                $a = 0; $b = 1;
    	                for ($i=0; $i<=(count($new_stmts)-1)/2; $i++){
    	            ?>
    	            <tr><td><table>
    	                <tr id='Row<?php print($i+1);?>'>
    	                   <td>
    			     		  <span class='add-on'><?php print($i+1);?>.</span>
    	                      <textarea name='colA<?php print($i+1);?>' class='miniStmt' required placeholder=''><?php print($new_stmts[$a]);?></textarea>
    					   </td>
    	                   <td>
    			     		  <span class='add-on'>i.</span>
    	                      <textarea name='colB1' class='miniStmt' required placeholder=''><?php print($new_stmts[$b]);?></textarea>
    					   </td>
    	                </tr>
	                </table></td></tr>
    	            <?php $a += 2; $b += 2;}} else if ($question[2] == 7) {
    	                $db->tbl_name = 'question_statements';
    	                $stmt = $db->fetch_row($db->selectById('qsn_id', $_GET['qsn_id']));
    	            ?>
    	            <tr>
    	                <td>
    	                   <textarea name='stmt1' id='block-text' required placeholder=''><?php print($stmt[1]);?></textarea>
    	                </td>
	                </tr>   
    	            <?php } ?>
    	         </table>
			</div>
			
			<?php } else { ?>
			
			<div id="obj-qsn-box"></div>
			
			<?php } ?>
			<hr>
			<?php
			     if($edit){
			     	$db->tbl_name = 'question_answer';
			     	$answer = $db->fetch_row($db->selectById('qsn_id', $_GET['qsn_id']));
			     } 
			?>
			<div id="obj-ans-box">
    			<table>
    			    <tr>
    					<td>
        					<span class="add-on">a.</span>
        					<input type="text" name="ansA" required placeholder="Answer (a)" value="<?php print($answer[1]) ?>">
        				</td>
    					<td>
    					   <span class="add-on">b.</span>
    					   <input type="text" name="ansB" required placeholder="Answer (b)" value="<?php print($answer[2]) ?>">
    					</td>
    				</tr>
    				<tr>
    					<td>
    					   <span class="add-on">c.</span>
    					   <input type="text" name="ansC" required placeholder="Answer (c)" value="<?php print($answer[3]) ?>">
    					</td>
    					<td>
    					   <span class="add-on">d.</span>
    					   <input type="text" name="ansD" required placeholder="Answer (d)" value="<?php print($answer[4]) ?>">
    				    </td>
    				</tr>
    		    </table>
			</div>
			<hr>
			<div id="obj-correct-ans">
			<table>
			<tr>
			     <td><input type="radio" name="answer" value='a' required <?php if($question[5] == 'a')print('checked')?>>&nbsp;a</td>
			     <td><input type="radio" name="answer" value='b' required <?php if($question[5] == 'b')print('checked')?>>&nbsp;b</td>
			     <td><input type="radio" name="answer" value='c' required <?php if($question[5] == 'c')print('checked')?>>&nbsp;c</td>
			     <td><input type="radio" name="answer" value='d' required <?php if($question[5] == 'd')print('checked')?>>&nbsp;d</td>
			</tr>
			</table>
			</div>
			<hr>
			<div id="add-qsn-final-req">
            	<select name="subject" id="subject" required onchange="showUnits(this.value)" id="subject">
                    <option value="">- Select Subject -</option>
                    <?php
                        $sql = "SELECT `id`, `sub_name`, `sub_code` FROM `subjects` WHERE id IN (SELECT `sub_id` FROM `sub_teacher` WHERE `user_id` = '{$session->user_id}')";
                        $result_set = $db->query($sql);
                        while($result = $db->fetch_assoc($result_set)){
                            $db->tbl_name = 'chapters';
                            $chapter = $db->fetch_row($db->selectById('id', $question[1]));
                    ?>
                    <option value="<?php print($result['id']) ?>" <?php if($chapter[1] == $result['id']){print('selected');}?>><?php print($result['sub_code']." - ".$result['sub_name']) ?></option>
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
            	<input type="button" value="Cancel" class="btn" onclick="window.location.href='qsn_objective.php'">
            </div>
			
	        </form>
	    </div>
		
		<!-- #### END ADD NEW OBJECTIVE QSN -->
		
		<?php } else if ($_GET['cmd'] == 'edit' && isset($_GET['qsn_id'])){ ?>
		<!-- #### EDIT OBJECTIVE QSN -->
		
		<div class="span9">
		  <h2>Edit Objective Question</h2>
		  
		</div>
		
		<!-- #### END ADD NEW OBJECTIVE QSN -->
		
		
		<?php } else {redirect('qsn_subjective.php');} ?>
		<?php } else { 
		    $sql = "SELECT * FROM `questions` WHERE `type_id` <> 1 AND `user_id` = '$session->user_id'";
		    $result_set = $db->query($sql);
		?>
		<!-- #### VIEW OBJECTIVE QSN LIST -->
		
		<div class="span9">
			<h2>Objective Questions <a href="qsn_objective.php?cmd=add" class="btn">Add New</a> <span style="font-size: 16px;">[ Total: <?php echo $db->num_rows($result_set);?> QSNs ]</span></h2>
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
              	
              	if($db->num_rows($result_set) < 1){
                    echo "<tr><td class='center' colspan=3>No entries Yet.</td></tr>"; 
                }
              	while($result = $db->fetch_assoc($result_set)){ 
              ?>
                <tr>
                  <td><input type="checkbox"></td>
                  <td><?php print($result['question']); ?></td>
                  <td align="center">
                    <a href="qsn_objective.php?cmd=edit&qsn_id=<?php print($result['id'])?>" title="Edit"><i class="icon-pencil"></i></a>
                    &nbsp;
                    <a href="execute.php?del_single_obj=<?php print($result['id']) ?>" title="Delete" onclick="return confirm('Confirm Delete')"><i class="icon-trash"></i></a>
                  </td>
                </tr>
              <?php } ?>
              </tbody>
            </table>		
		</div>
		
		<?php } ?>
		<!-- #### END VIEW OBJECTIVE QSN LIST -->
		
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
