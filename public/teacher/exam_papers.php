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
		
    		<?php if($_GET['cmd'] == 'generate'){ ?>
    		<!-- #### GENERATE NEW EXAM PAPER -->
    		
    		<div class="span9">
    		      <h2>Generate New Exam Paper</h2>
    		      <form action="execute.php" method="post">
    		      <input type="hidden" name="generate_exam_paper">
    		      <div id="generate-mode-subject">
    		      
    		          <select name="subject" id="subject" required onchange="putChapters(this.value)" id="subject">
                            <option value="">- Select Subject -</option>
                        <?php
                            $result_set = $db->getSubjectsByUserId($session->user_id);
                            while($result = $db->fetch_assoc($result_set)){
                        ?>
                            <option value="<?php print($result['id']) ?>"><?php print($result['sub_code']." - ".$result['sub_name']) ?></option>
                        <?php } ?>
                     </select>
                     <select name="exam-type" required onchange="changeTotal(this.value)">
                            <option value="">- Exam Type -</option>
                            <option value="mid-term">Mid-Term</option>
                            <option value="pre-final">Pre-Final</option>
                     </select>
                     <input type="text" name="year" width=45 placeholder="- Year -" required>
                     <select name="session" required>
                            <option value="">- Session -</option>
                            <option value="fall">Fall</option>
                            <option value="spring">Spring</option>
                     </select>
    		      </div>
    		      <div id="exampaper-table">
    		          <table id="exam-chapters">
    		              <!-- AJAX brings the chapters here... -->        
    		          </table>
    		      </div>
    		      <hr>
    		      <div align="center" style="margin-top: 10px">
    		          <input type="submit" value="Generate Question" class="btn">
    		          <input type="button" value="Cancel" class="btn">
    		      </div>
    		      
                  </form>
                  
    		</div>
    		
    		<!-- #### END GENERATE NEW EXAM PAPER -->		
    		
    		<?php } else {redirect('qsn_subjective.php');} ?>
    		
		<?php } else { ?>
		<!-- #### VIEW EXAM PAPERS LIST -->
		
		<div class="span9">
			<h2>Exam Papers <a href="exam_papers.php?cmd=generate" class="btn">Generate New</a></h2>
            <table>
              <thead>
                <tr>
                  <th width=45 class="center">PDF</th>
                  <th>Subject</th>
                  <th>Exam Type</th>
                  <th>Year</th>
                  <th>Session</th>
                  <th width=45>Tools</th>
                </tr>
              </thead>
              <tbody>
              <?php
                    $db->tbl_name = 'exam_papers';
                    $sql = "SELECT * FROM exam_papers WHERE sub_teacher_id = (SELECT id FROM sub_teacher WHERE user_id = $session->user_id)";
                    $exam_paper_result_set = $db->query($sql);
                    while($exam_paper_result = $db->fetch_array($exam_paper_result_set)){
                        $sql = "SELECT `sub_code`,`sub_name` FROM `subjects` WHERE `id` = (SELECT `sub_id` FROM `sub_teacher` WHERE `id` = {$exam_paper_result[1]})";
                        $sub_result = $db->fetch_row($db->query($sql)); 
              ?>
                <tr>
                  <td align="center">
                    <a href="qsnPapers/<?php echo $exam_paper_result[6] ?>" title="View PDF" target='_blank'><i class="icon-file"></i></a>
                  </td>
                  <td><?php echo $sub_result[0].' - '.$sub_result[1]; ?></td>
                  <td><?php echo $exam_paper_result[2] ?></td>
                  <td><?php echo $exam_paper_result[3] ?></td>
                  <td><?php echo $exam_paper_result[4] ?></td>
                  <td align="center"><a href="execute.php?del_ep_id=<?php echo $exam_paper_result[0] ?>" title="Delete" onclick="return confirm('Confirm Delete!')"><i class="icon-trash"></i></a></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>		
		</div>
	    
	    <!-- #### END VIEW EXAM PAPERS LIST -->
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
