<?php
    require_once '../../includes/session.php';
    require_once '../../includes/database.php';
    if(!($session->is_logged_in() && $session->user_id != 1)){
        header('Location: ../index.php');
    }
?>

<?php include '../template/teacher-dash-header.php' ?>

	<div class="row">
		<div class="span3">
			<?php include '../template/teacher-vertical-menu.html' ?>
		</div>
		<div class="span9">
			<h2>Teacher's Profile</h2>
			<p><span class="tHead">Name</span>: <?php print($session->name); ?></p>
			<p><span class="tHead">Username</span>: <?php print($session->username); ?></p>
			<p><span class="tHead">Email</span>: <?php print($session->email); ?></p>
			<h3>Subjects Assigned</h3>
            <table>
                <thead>
                    <tr>
                        <th>Faculty</th>
                        <th>Semester</th>
                        <th>Subject</th>
                        <th>Sub Code</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT `id`, `sub_name`, `sub_code` FROM `subjects` WHERE id IN (SELECT `sub_id` FROM `sub_teacher` WHERE `user_id` = '$session->user_id')";
                    $result_set = $db->query($sql);
                    if($db->num_rows($result_set)<1){print('<tr><td colspan=4 class="center">No Subjects Assigned yet.</td></tr>');}
                    while($result = $db->fetch_assoc($result_set)){
                ?>
                    <tr>
                        <td><?php 
                            $sql = "SELECT name FROM faculties WHERE id = (SELECT fac_id FROM fac_sem WHERE id = (SELECT fac_sem_id FROM subjects WHERE id = '{$result['id']}'))";
                            $result1 = $db->fetch_row($db->query($sql));
                            print($result1[0]);
                        ?></td>
                        <td>
                            <?php 
                            $sql = "SELECT semester FROM fac_sem WHERE id = (SELECT fac_sem_id FROM subjects WHERE id = {$result['id']})";
                            $result2 = $db->fetch_row($db->query($sql));
                            print($result2[0]);
                        ?></td>
                        <td><?php print($result['sub_name'])?></td>
                        <td><?php print($result['sub_code'])?></td>
                    </tr>
                    
                <?php } ?>
                </tbody>
            </table>
		</div>
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
