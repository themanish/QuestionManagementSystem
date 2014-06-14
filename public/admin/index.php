<?php
    require_once '../../includes/session.php';
    require_once '../../includes/functions.php';
    if(!($session->is_logged_in() && $session->user_id == 1)) redirect('../index.php');
?>
	
<?php include '../template/admin-dash-header.php' ?>

	<div class="row">
		<div class="span3">
			<?php include '../template/admin-vertical-menu.html' ?>
		</div>
		<div class="span9">
			<h2>Welcome to Admin Section of QMS</h2>
                        <p>
                            In this section, you'll be able to manage (Add, Edit, Delete) faculty & semesters
                            and subjects with unit titles. Then, manage (Add, Edit, Delete) Teachers and assign 
                            subjects they will be able to add questions later on for QMS.
                        </p>
                        
                        <h3>Quick Links</h3>
                        <div id="quick-links">
                            <div class="box">
                                <img src='../img/teachers-ico.png'>
                                <ul>
                                    <li><a href="teachers.php?cmd=add">Add New</a></li>
                                    <li><a href="teachers.php">View List</a></li>
                                </ul>
                            </div>
                            <div class="box">
                                <img src='../img/faculty-icos.png'>
                                <ul>
                                    <li><a href="fs_facnsem.php?cmd=add">Add New</a></li>
                                    <li><a href="fs_facnsem.php">View List</a></li>
                                </ul>
                            </div>
                            <div class="box">
                                <img src='../img/subjects-ico.png'>
                                <ul>
                                    <li><a href="fs_subnchap.php?cmd=addsub">Add New</a></li>
                                    <li><a href="fs_subnchap.php">View List</a></li>
                                </ul>
                            </div>
                        </div>
                        
		</div>
	</div>

<?php include '../template/admin-footer.php' ?>