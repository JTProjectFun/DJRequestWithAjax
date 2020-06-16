<?php
include_once '../configuration.php';
include_once 'adminconfig.php';
include_once '../functions/functions.php';
start_session();
$user = 0;
if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}

if(isset($_GET['user'])) {
    $user = makeSafe($_GET['user']);
}
    $_SESSION['listuser'] = $user;
?>

<?php include 'adminheadertop.php'; ?>
<div class="row">
    <div class="container-fluid">
        <h1 class="h1"><a href="">Administer Users</a></h1>
        <a href="gridder_addnew" class="gridder_addnew">Add New User</a>
    </div>
</div>
<div class="row">
    <div class="container-fluid">
        <div id="adminuser">
            <!-- ajax content -->
        </div>
    </div>
</div>

<?php include 'javaincludes.php'; ?>
<script type="text/javascript">

	// Function for loading the grid
	function LoadGrid() {
		var gridder = $('#adminuser');
		var UrlToPass = 'action=load';
		gridder.html('loading..');
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function(responseText) {
				gridder.html(responseText);
			}
		});
	}

	LoadGrid(); // Load the grid on page loads

        // Show the text box on click
        $('body').delegate('.editable', 'click', function(){
                var ThisElement = $(this);
                ThisElement.find('span').hide();
                ThisElement.find('.gridder_input').show().focus();
        });

        // Pass and save the textbox values on blur function
        $('body').delegate('.gridder_input', 'blur', function(){
                var ThisElement = $(this);
                ThisElement.hide();
                ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
                var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
                if(ThisElement.hasClass('datepicker')) {
                        return false;
                }
                $.ajax({
                        url : 'userajax.php',
                        type : 'POST',
                        data : UrlToPass
                });
        });

        // Same as the above blur() when user hits the 'Enter' key
        $('body').delegate('.gridder_input', 'keypress', function(e){
                if(e.keyCode == '13') {
                        var ThisElement = $(this);
                        ThisElement.hide();
                        ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
                        var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
                        if(ThisElement.hasClass('datepicker')) {
                                return false;
                        }
                        $.ajax({
                                url : 'userajax.php',
                                type : 'POST',
                                data : UrlToPass
                        });
                }
        });

	// Function for deleting all the user's requests
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm("Are you sure want to delete this user & all their requests?");
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=delete&value='+ThisElement.attr('href');
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
	});
	// Function for banning the user
	$('body').delegate('.gridder_ban', 'click', function(){
		var conf = confirm('Are you sure want to ban this user?');
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=ban&value='+ThisElement.attr('href');
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
	});
    
    	// Add new record

        // Add new record when the table is empty
        $('body').delegate('.gridder_insert', 'click', function(){
                $('#norecords').hide();
                $('#addnew').slideDown();
                return false;
        });

        // Add new record when the table in non-empty
        $('body').delegate('.gridder_addnew', 'click', function(){
                $('html, body').animate({ scrollTop: $('.as_gridder').offset().top}, 250); // Scroll to top gridder table
                $('#addnew').slideDown();
                return false;
        });

	// Cancel the insertion
	$('body').delegate('.gridder_cancel', 'click', function(){
		LoadGrid()
		return false;
	});
    // Pass the values to ajax page to add the values
	$('body').delegate('#gridder_addrecord', 'click', function(){
		// Do insert validation here
		
		// Pass the form data to the ajax page
		var data = $('#gridder_addform').serialize();
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : data,
			success: function(data) {
                                                     if(data.status.indexOf("sqlerror") >=0) {
                                                                                                if (data.status.indexOf("Duplicate entry") >=0) {
                                                                                                alert ("Whoops. That user was not unique. Try again with different data.");
                                                                                                return false;
                                                                                              }
                                                }
			LoadGrid();
			}
		});
		return false;
	});
</script>
</body>
</html>
