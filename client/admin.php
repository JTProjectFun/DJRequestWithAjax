<?php
include_once '../configuration.php';
include_once 'adminconfig.php';
include_once '../functions/functions.php';
start_session();

if(!isset($_SESSION['client_id']) || $_SESSION['client_id'] == "") {
     header('Location: index.php');
}
else {
     $userid = $_SESSION['client_id'];
}

// check how many events this user has.  If only one, send them to requests.php with their eventid
$conn = mysqli_connect($host, $username, $password, $db);
$query = mysqli_query($conn, "select id from events WHERE userid='$userid'");
		$count  = mysqli_num_rows($query);
		if( $count == "1" ) {
			$record = mysqli_fetch_row($query);
			$eventid = $record[0];
            header('Location: requests.php?eventkey='.$eventid);       
		}
?>
<?php include 'adminheadertop.php'; ?>
<div class="row">
    <div class="container-fluid">
        <h1 class="h1"><a href="">Your Events</a></h1>
    </div>
</div>
<div class="row">
    <div class="container-fluid">
        <div id="adminlist">
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
		var gridder = $('#adminlist');
		var UrlToPass = 'action=load';
		gridder.html('<i class="fa fa-spinner fa-spin" style="font-size:120px;color:purple"></i>Loading...');
		$.ajax({
			url : 'adminajax.php',
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
		if(ThisElement.hasClass('datepiker')) {
			return false;
		}
		$.ajax({
			url : 'adminajax.php',
			type : 'POST',
			data : UrlToPass,
                        success : function(data) {
                            if(data.status.indexOf("sqlerror") >=0) {
                                if (data.status.indexOf("Duplicate entry") >=0) {
                                    alert ("Whoops. That event key was not unique. Try again with a different key.");
                                    LoadGrid();
                                }
                            }
                       }
		});
                                LoadGrid();
	});

	// Same as the above blur() when user hits the 'Enter' key
	$('body').delegate('.gridder_input', 'keypress', function(e){
		if(e.keyCode == '13') {
			var ThisElement = $(this);
			ThisElement.hide();
			ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
			var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
			if(ThisElement.hasClass('datepiker')) {
				return false;
			}
			$.ajax({
				url : 'adminajax.php',
				type : 'POST',
				data : UrlToPass,
                                success : function(data) {
                                    if(data.status.indexOf("sqlerror") >=0) {
                                        if (data.status.indexOf("Duplicate entry") >=0) {
                                            alert ("Whoops. That event key was not unique. Try again with a different key.");
                                            LoadGrid();
                                        }
                                    }
                                LoadGrid();
                        }
			});
		}
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
                        url : 'adminajax.php',
                        type : 'POST',
                        data : UrlToPass
                });
        });

	// Function to delete the record
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm('Are you sure want to delete all requests associated with this event?');
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=delete&value='+ThisElement.attr('href');
		$.ajax({
			url : 'adminajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
	});

	// Pass the values to ajax page to add the values
	$('body').delegate('#gridder_addrecord', 'click', function(){
		// Do insert validation here
		if($('#date').val() == '') {
			$('#date').focus();
			alert('Enter the Date');
			return false;
		}
		if($('#thekey').val() == '') {
			$('#thekey').focus();
			alert('Enter the Key');
			return false;
		}

		// Pass the form data to the ajax page
		var data = $('#gridder_addform').serialize();
		$.ajax({
			url : 'adminajax.php',
			type : 'POST',
			data : data,
			success: function(data) {
                                                     if(data.status.indexOf("sqlerror") >=0) {
                                                                                                if (data.status.indexOf("Duplicate entry") >=0) {
                                                                                                alert ("Whoops. That event key was not unique. Try again with a different key.");
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
