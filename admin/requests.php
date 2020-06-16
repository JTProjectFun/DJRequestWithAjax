<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}

$key = 0;
$user = 0;

if (isset($_GET['eventkey'])) {
  $key = makeSafe($_GET['eventkey']);
}
$_SESSION['requestuser'] = 0;
if (isset($_GET['requestuser'])) {
    $requestuser = makeSafe($_GET['requestuser']);
    $_SESSION['requestuser'] = $requestuser;
}

$_SESSION['key'] = $key;

?>
<?php include 'adminheadertop.php'; ?>
<div class="row">
    <div class="container-fluid">
        <h1 class="h1"><a href="">Administer Requests</a></h1>
        <a href="gridder_addnew" class="gridder_addnew">Add New Request</a>
    </div>
</div>
<div class="row">
<div class="container-fluid">
<a href="m3u.php?eventid=<?php echo $key; ?>" class="createm3u">Download M3U file</a>
</div>
</div>

<div class="row">
    <div class="container-fluid">
        <div id="adminrequests">
            <!-- ajax content -->
        </div>
    </div>
</div>
<?php include 'javaincludes.php'; ?>
<script type="text/javascript">

        // Function to hide all errors
        function HideErrors() {
            $('.error').hide();
        }
	// Function for loading the grid
	function LoadGrid() {
		var gridder = $('#adminrequests');
		var UrlToPass = 'action=load';
		gridder.html('loading..');
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function(responseText) {
				gridder.html(responseText);
			}
		});
	}
	
	// Seperate Function for datepiker() to save the value
	function ForDatePiker(ThisElement) {
		ThisElement.prev('span').html(ThisElement.val()).prop('title', ThisElement.val());
		var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : UrlToPass
		});
	}
	
	LoadGrid(); // Load the grid on page loads
	
	// Execute datepiker() for date fields
	$("body").delegate("input[type=text].datepicker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datetimepicker({
	   		dateFormat: 'yy/mm/dd',
			onSelect: function() {
				setTimeout(ForDatePiker(ThisElement), 500);
			}
	   });
	});
	
        // Show the tickable thing on click
        $('body').delegate('.tickable', 'click', function(){
            var ThisElement = $(this);
            ThisElement.find('span').hide();
            ThisElement.find('.gridder_input').show().focus();
        });
            
	// Show the text box on click
	$('body').delegate('.editable', 'click', function(){
		var ThisElement = $(this);
		ThisElement.find('span').hide();
		ThisElement.find('.gridder_input').show().focus();
	});
	
        // On click, do the toggle thing
        $('body').delegate('.toggle', 'click', function(){
		var ThisElement = $(this);
                var value = 0;
                if ($(ThisElement).prop("checked")){
                    value = 1;
                }
                else {
                    value = 0;
                }
		var UrlToPass = 'action=update&value='+value+'&crypto='+ThisElement.prop('name');
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : UrlToPass
		});
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
			url : 'requestajax.php',
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
				url : 'requestajax.php',
				type : 'POST',
				data : UrlToPass
			});
		}
	});
	
	// Function for deleting all of one user's requests
	$('body').delegate('.gridder_deleteuserreq', 'click', function(){
		var conf = confirm("Are you sure want to delete all of this user's requests?");
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=deleteuserreq&value='+ThisElement.attr('href');
		$.ajax({
			url : 'requestajax.php',
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
			url : 'requestajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
	});
	// Function for delete the record
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm('Are you sure want to delete this record?');
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=delete&value='+ThisElement.attr('href');
		$.ajax({
			url : 'requestajax.php',
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
	
	// Add new record when the table is non-empty
	$('body').delegate('.gridder_addnew', 'click', function(){
$('.gridder_addnew').hide();
//		$('html, body').animate({ scrollTop: $('.as_gridder').offset().top}, 250);
		$('#addnew').slideDown();
		return false;
	});
	
	// Cancel the insertion
	$('body').delegate('.gridder_cancel', 'click', function(){
$('.gridder_addnew').show();
		LoadGrid();
		return false;
	});
	
	// For datepiker
	$("body").delegate(".gridder_add.datepiker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datepicker({
	   		dateFormat: '@'
	   });
	});
	
	// Pass the values to ajax page to add the values
	$('body').delegate('#gridder_addrecord', 'click', function(){
		// Do insert validation here
		if($('#name').val() == '') {
			$('#name').focus();
                             alert('The "Name" field cannot be left blank. Please enter a name.');
		return false;
		}
		if($('#artist').val() == '') {
			$('#artist').focus();
                        alert('The "Artist" field cannot be left blank. Please enter an artist.');
			return false;
		}
		if($('#title').val() == '') {
			$('#title').focus();
                        alert('The "Title" field cannot be left blank. Please enter a title.');

			return false;
		}
		if($('#message').val().length > 140)	 {
                        $('#message').focus();
                             alert('The "Message" field cannot contain more than 140 characters. Consider using a shorter message.');
                        return false;
                }
		// Pass the form data to the ajax page
		var data = $('#gridder_addform').serialize();
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : data,
			success: function() {
                        $('.gridder_addnew').show();
				LoadGrid();
			}
		});
		return false;
	});
</script>
</body>
</html>
