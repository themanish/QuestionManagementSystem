<?php

require_once '../../includes/session.php';
require_once '../../includes/database.php';
require_once '../../includes/functions.php';

if(isset($_POST['add_subjective']) || isset($_POST['edit_subjective'])){
	$_POST = $db->escape_value_array($_POST);
	$db->tbl_name = 'questions';
	$db->data = array('chapter_id' => $_POST['unit'], 'type_id' => 1, 'user_id' => $session->user_id, 'question' => $_POST['question'], 'answer' => $_POST['answer'], 'difficulty_level' => $_POST['level'], 'marks' => 10);
	if(isset($_POST['add_subjective'])){
	   $db->insert();
	} else {
	    $db->condition = array('id' => $qsn_id);
	    $db->update();
	}
	redirect('qsn_subjective.php');
}

if(isset($_GET['del_single_sub']) || isset($_GET['del_single_obj'])){
    if(isset($_GET['del_single_sub'])) $qsn_id = $_GET['del_single_sub'];
    if(isset($_GET['del_single_obj'])) $qsn_id = $_GET['del_single_obj'];
    $db->tbl_name = 'questions';
    $db->condition = array('id' => $qsn_id);
    $db->delete();
    if(isset($_GET['del_single_sub'])) redirect('qsn_subjective.php');
    if(isset($_GET['del_single_obj'])) redirect('qsn_objective.php');
}

if(isset($_POST['add_objective']) || isset($_POST['edit_objective']) ){
    
    $_POST = $db->escape_value_array($_POST);
    $db->tbl_name = 'questions';
    $db->data = array('chapter_id' => $_POST['unit'], 'type_id' => $_POST['qsnType'], 'user_id' => $session->user_id, 'question' => $_POST['question'], 'answer' => $_POST['answer'], 'difficulty_level' => $_POST['level'], 'marks' => $_POST['marks']);
    if(isset($_POST['add_objective'])){
        $db->insert();
        $qsn_id = $db->last_insert_id;
    }
    else {
        $db->condition = array('id' => $_POST['qsn_id']);
        $db->update();
    }
        
    if($_POST['qsnType'] == 2 || $_POST['qsnType'] == 5 || $_POST['qsnType'] == 6){ // True or False, Arrange in Order, Question with 2/3 choices
        
        for($i=1; $i<=8; $i++)
        if(isset($_POST['stmt'.$i])) $stmt.$i = $_POST['stmt'.$i]; else $stmt.$i = '';

        $db->tbl_name = 'question_statements';
    	if(isset($_POST['add_objective'])){
    	    $db->data = array('qsn_id' => $qsn_id, 'stmt1' => $stmt1, 'stmt2' => $stmt2, 'stmt3' => $stmt3, 'stmt4' => $stmt4, 'stmt5' => $stmt5, 'stmt6' => $stmt6, 'stmt7' => $stmt7, 'stmt8' => $stmt8);
    	    $db->insert();
    	}
    	else {
    	    $db->data = array('stmt1' => $stmt1, 'stmt2' => $stmt2, 'stmt3' => $stmt3, 'stmt4' => $stmt4, 'stmt5' => $stmt5, 'stmt6' => $stmt6, 'stmt7' => $stmt7, 'stmt8' => $stmt8);
    	    $db->condition = array('qsn_id' => $_POST['qsn_id']);
    	    $db->update();
    	}
    	
    } else if ($_POST['qsnType'] == 3) { // Fill in the blanks
        
        // No conditions required for this type
    
    } else if ($_POST['qsnType'] == 4) { // Match the following
        
        if(isset($_POST['colA1'])) $colA1 = $_POST['colA1']; else $colA1 = '';
        if(isset($_POST['colA2'])) $colA2 = $_POST['colA2']; else $colA2 = '';
        if(isset($_POST['colA3'])) $colA3 = $_POST['colA3']; else $colA3 = '';
        if(isset($_POST['colA4'])) $colA4 = $_POST['colA4']; else $colA4 = '';
        if(isset($_POST['colB1'])) $colB1 = $_POST['colB1']; else $colB1 = '';
        if(isset($_POST['colB2'])) $colB2 = $_POST['colB2']; else $colB2 = '';
        if(isset($_POST['colB3'])) $colB3 = $_POST['colB3']; else $colB3 = '';
        if(isset($_POST['colB4'])) $colB4 = $_POST['colB4']; else $colB4 = '';
        
        $db->tbl_name = 'question_statements';
        if(isset($_POST['add_objective'])){
            $db->data = array('qsn_id' => $qsn_id, 'stmt1' => $colA1, 'stmt2' => $colB1, 'stmt3' => $colA2, 'stmt4' => $colB2, 'stmt5' => $colA3, 'stmt6' => $colB3, 'stmt7' => $colA4, 'stmt8' => $colB4);
            $db->insert();
        } else {
            $db->data = array('stmt1' => $colA1, 'stmt2' => $colB1, 'stmt3' => $colA2, 'stmt4' => $colB2, 'stmt5' => $colA3, 'stmt6' => $colB3, 'stmt7' => $colA4, 'stmt8' => $colB4);
            $db->condition = array('qsn_id' => $_POST['qsn_id']);
    	    $db->update();
        }
    
    } else if ($_POST['qsnType'] == 7) { // Question with block text
        
        $db->tbl_name = 'question_statements';
        if(isset($_POST['add_objective'])){
            $db->data = array('qsn_id' => $qsn_id, 'stmt1' => $_POST['stmt1']);
            $db->insert();
        } else {
            $db->data = array('stmt1' => $_POST['stmt1']);
            $db->condition = array('qsn_id' => $_POST['qsn_id']);
            $db->update();
        }
        
    }
    
    $db->tbl_name = 'question_answer';
    if(isset($_POST['add_objective'])){
        $db->data = array('qsn_id' => $qsn_id, 'a' => $_POST['ansA'], 'b' => $_POST['ansB'], 'c' => $_POST['ansC'], 'd' => $_POST['ansD']);
        $db->insert();
    } else {
        $db->data = array('a' => $_POST['ansA'], 'b' => $_POST['ansB'], 'c' => $_POST['ansC'], 'd' => $_POST['ansD']);
        //print_r($db->data);exit;
        $db->condition = array('qsn_id' => $_POST['qsn_id']);
        $db->update();
    }
    
    redirect('qsn_objective.php');
   
}

if(isset($_GET['ajax'])) {
	
	if($_GET['ajax'] == 'showUnits' && isset($_GET['sub_id'])) {
		
		$db->tbl_name = 'chapters';
		$result_set = $db->selectById('sub_id', $_GET['sub_id']);
		$i = 1;
		if(isset($_GET['exampaper'])){
		    echo "
		            <tr>
    		                  <th width='250'>Chapters</th>
    		                  <th><table>
    		                      <tr><th colspan=3>1 Marks</th></tr>
    		                      <tr>
    		                          <th>Easy</th><th>Medium</th><th>Hard</th>
    		                      </tr>
    		                  </table></th>
    		                  <th>Max Total<br><span id='total1'></span></th>
    		                  <th><table>
    		                      <tr><th colspan=3>2 Marks</th></tr>
    		                      <tr>
    		                          <th>Easy</th><th>Medium</th><th>Hard</th>
    		                      </tr>
    		                  </table></th>
    		                  <th>Max Total<br><span id='total2'></span></th>
    		              </tr>
		    ";
		}
		while($result = $db->fetch_assoc($result_set)){
		    if(isset($_GET['exampaper'])){
		        echo "
    		        <tr><td width=250>{$result['unit']} - {$result['title']}</td>
		            <td><table>
        		        <tr>
        		        <td><input type='text' id='chap{$i}e1' name='chap{$i}e1' value=0 onkeyup='chapTotal({$i}, 1, {$db->num_rows($result_set)})'></td>
        		        <td><input type='text' id='chap{$i}m1' name='chap{$i}m1' value=0 onkeyup='chapTotal({$i}, 1, {$db->num_rows($result_set)})'></td>
        		        <td><input type='text' id='chap{$i}h1' name='chap{$i}h1' value=0 onkeyup='chapTotal({$i}, 1, {$db->num_rows($result_set)})'></td>
        		        </tr>
        		        </table></td>
        		        <td><span id='chap{$i}T1'>0</span></td>
        		        <td><table>
        		        <tr>
        		        <td><input type='text' id='chap{$i}e2' name='chap{$i}e2' value=0 onkeyup='chapTotal({$i}, 2, {$db->num_rows($result_set)})'></td>
        		        <td><input type='text' id='chap{$i}m2' name='chap{$i}m2' value=0 onkeyup='chapTotal({$i}, 2, {$db->num_rows($result_set)})'></td>
        		        <td><input type='text' id='chap{$i}h2' name='chap{$i}h2' value=0 onkeyup='chapTotal({$i}, 2, {$db->num_rows($result_set)})'></td>
        		        </tr>
    		        </table></td>
    		        <td><span id='chap{$i}T2'>0</span></td>
    		        </tr>
		        ";
		        $i++;
		    } else {
			    echo "<option value={$result['id']}> {$result['unit']} - {$result['title']} </option>";
		    }
		}
		if(isset($_GET['exampaper'])){
			echo "
			     <tr>
			        <th>Total</th>
			        <th>1 Marks</th>
			        <th><span id='qsnsT1'></span></th>
			        <th>2 Marks</th>
			        <th><span id='qsnsT2'></span></th>
			     </tr>
			";
		}
		
	}
	
	if($_GET['ajax'] == 'getQsn' && isset($_GET['type_id'])) {
	    print("
	            <table>
    	            <tr>
        	            <td>
        	            <textarea name='question' class='mainStmt' required placeholder='Main Statement of Question Here...'></textarea>
        	            </td>
    	            </tr>
	    ");
	    
	    if($_GET['type_id'] == 2 || $_GET['type_id'] == 5 || $_GET['type_id'] == 6){ // i.e. True or False, Arrange in Order, Question with 2/3 choices
	       
	        if($_GET['type_id'] != 2) {$MaxStmt = 8;} else {$MaxStmt = 4;}
	        print("    
	               <tr id='Stmt1'>
	                   <td>
			     		  <span class='add-on'>1.</span>
	                      <textarea name='stmt1' class='miniStmt' required placeholder=''></textarea>
	                      <a href='#add-qsn-box' class='btn addStmt' onclick='addStmt($MaxStmt)'>+</a>    
					   </td>
	               </tr>
	               <tr id='Stmt2'></tr>
	               <tr id='Stmt3'></tr>
	               <tr id='Stmt4'></tr>
	               <tr id='Stmt5'></tr>
	               <tr id='Stmt6'></tr>
	               <tr id='Stmt7'></tr>
	               <tr id='Stmt8'></tr>
	        ");
	    }
	    
	    if($_GET['type_id'] == 3){ // i.e. Fill in the blanks, Single Answer Qsn
	        
	    }
	    
	    if($_GET['type_id'] == 4){ // i.e. Match the followings
	        print("
	        <tr><td><table>
	               <tr id='Row1'>
	                   <td>
			     		  <span class='add-on'>1.</span>
	                      <textarea name='colA1' class='miniStmt' required placeholder=''></textarea>
					   </td>
	                   <td>
			     		  <span class='add-on'>i.</span>
	                      <textarea name='colB1' class='miniStmt' required placeholder=''></textarea>
	                      <a href='#' class='btn addStmt' onclick='addRow()'>+</a>
					   </td>
	               </tr>
	               <tr id='Row2'></tr>
	               <tr id='Row3'></tr>
	               <tr id='Row4'></tr>
	        </table></td></tr>
	        ");
	    }
	    
	    if($_GET['type_id'] == 7){
	        print("
	              <tr>
	                <td>
	                   <textarea name='stmt1' id='block-text' required placeholder=''></textarea>
	                </td>
	              </tr>
            ");
	    }
	    
	    print("</table>");
	}
	
	if($_GET['ajax'] == 'getQsnListByType' && isset($_GET['type_id'])) {
	   $sql = "SELECT * FROM `questions`";    
	}
}

// GENERATE EXAM PAPER
if(isset($_POST['generate_exam_paper'])){
    ob_start();
	$sql = "SELECT ";
    $sql .= "id, chapter_id, type_id, question, answer, stmt1, stmt2, stmt3, stmt4, stmt5, stmt6, stmt7, stmt8, a, b, c, d, marks ";
    $sql .= "FROM `questions` ";
    $sql .= "LEFT JOIN `question_statements` AS qs ON `id` = qs.`qsn_id` ";
    $sql .= "JOIN `question_answer` AS qa ON `id` = qa.`qsn_id` ";
            
	// This loop will print all 1 Mark questions
    $i=1; $qsn_list = '';
	foreach($_POST as $k => $v){
        
	        if (preg_match('#^[a-z]{4}(\d+)(\D)(\d)#i', $k, $matches)) {
	            if($v != 0){
	               if($matches[2]=='e') $level = 'easy'; else if($matches[2]=='m') $level = 'medium'; else if($matches[2]=='h') $level = 'hard'; 
	               $sql1 = $sql." WHERE `marks` = {$matches[3]} AND difficulty_level = '{$level}' AND `chapter_id` = (SELECT `id` FROM `chapters` WHERE `unit` = {$matches[1]} AND `sub_id` = {$_POST['subject']}) ORDER BY RAND() LIMIT {$v}";
	               
	               $result_set = $db->query($sql1);
	               while($result = $db->fetch_assoc($result_set)){
	                   $qsn_list = $qsn_list.$result['id'].',';
	               	   echo "<table>";
	               	   echo "<tr><td>($i) {$result['question']} [{$matches[3]} Marks]</td></tr>";
	               	   
	               	   echo "</table>";
	               	   
	               	   if($result['type_id'] == 2 || $result['type_id'] == 5 || $result['type_id'] == 6){ // i.e. True or False, Arrange in Order, Question with 2/3 choices
	               	       echo "<table>";
	               	       for($n=1; $n<=8; $n++){
                                if($result['stmt'.$n] != '')
	               	            echo "<tr><td>$n. {$result['stmt'.$n]}</td></tr>";
	               	       }
	               	       echo "</table>";
	               	   } 
	               	   
	               	   else if($result['type_id'] == 4){
	               	   	   echo "<table>";
	               	   	   $RowNo = 1; $ColB = 'i'; $a = 1; $b = 2;
	               	   	   for($n=1; $n<=4; $n++){
	               	   	       if($result['stmt'.$a] != '' && $result['stmt'.$b] != ''){
    	               	   	       if($RowNo == 2){ $ColB = 'ii'; } else if($RowNo == 3) { $ColB = 'iii'; } else if ($RowNo == 4) { $ColB = 'iv'; }
    	               	   	           echo "<tr><td>$RowNo. {$result['stmt'.$a]}</td><td>$ColB. {$result['stmt'.$b]}</td></tr>";
    	               	   	       }
	               	   	       $RowNo++; $a+=2; $b+=2;
	               	   	   }
	               	   	   echo "</table>";
	               	   }   
	               	   
	               	   else if($result['type_id'] == 4){
	               	       echo "<table><tr><td>{$result['stmt1']}</td></tr></table>";    
	               	   }
	               	   
	               	   echo "<table>";
	               	   echo "<tr><td>a.|{$result['a']}</td><td>b.|{$result['b']}</td></tr>";
	               	   echo "<tr><td>c.|{$result['c']}</td><td>d.|{$result['d']}</td></tr>";
	               	   echo "</table>";
	               	   echo "<br><br>";
	               	   $i++;
	               }
	            }
	        }
	}
	$db->tbl_name = 'subjects';
	$result_set = $db->selectById('id', $_POST['subject']);
	$result = $db->fetch_row($result_set);
	$now = time();
	$filename = "{$result[2]}-{$_POST['year']}-{$_POST['exam-type']}-{$_POST['session']}-$now.html";
	file_put_contents('qsnPapers/'.$filename, ob_get_contents());
	ob_end_flush();
	
	$qsn_list = substr($qsn_list, 0, -1);
	$sql = "SELECT `id` FROM `sub_teacher` WHERE `sub_id` = {$_POST['subject']} AND `user_id` = {$session->user_id}";
	$sub_teacher_result = $db->fetch_row($db->query($sql));
	
	$db->tbl_name = 'exam_papers';
	$db->data = array('sub_teacher_id' => $sub_teacher_result[0], 'exam_type' => $_POST['exam-type'], 'year' => $_POST['year'], 'session' => $_POST['session'], 'qsn_list' => $qsn_list, 'qsn_name' => $filename);
	$db->insert();
	
	redirect('exam_papers.php');
}

if(isset($_GET['del_ep_id'])){
	$db->tbl_name = 'exam_papers';
	$epaper = $db->fetch_row($db->selectById('id', $_GET['del_ep_id']));
	unlink('qsnPapers/'.$epaper[6]);
	
	$db->condition = array('id' => $_GET['del_ep_id']);
	$db->delete();
	redirect('exam_papers.php');
}
