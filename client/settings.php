<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();
$id=0;

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}
?>
<?php include 'adminheadertop.php'; ?>
<div class="row">
    <div class="col-md-12">
	<h1 class="h1"><a href="">Administer Request System Settings</a></h1>
    </div>
</div>
<div class="row">
	<div id="settingslist">
            <!-- ajax content -->
        </div>
</div>
<?php include 'javaincludes.php'; ?>
<script type="text/javascript">

$(function(){

	// Function for loading the grid
	function LoadGrid() {
		var gridder = $('#settingslist');
		var UrlToPass = 'action=load';
		gridder.html('loading..');
		$.ajax({
			url : 'settingsajax.php',
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
		$.ajax({
			url : 'settingsajax.php',
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
			if(ThisElement.hasClass('datepiker')) {
				return false;
			}
			$.ajax({
				url : 'settingsajax.php',
				type : 'POST',
				data : UrlToPass
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
                        url : 'settingsajax.php',
                        type : 'POST',
                        data : UrlToPass
                });
        });

	// Function for delete the record
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm('Are you sure want to delete this key and all requests associated with it?');
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=delete&value='+ThisElement.attr('href');
		$.ajax({
			url : 'settingsajax.php',
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
			url : 'settingsajax.php',
			type : 'POST',
			data : data,
			success: function() {
				LoadGrid();
			}
		});
		return false;
	});
});
</script>

</body>
</html>
