<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();

$eventid = 0;
$user = 0;

if(!isset($_SESSION['client_id']) || $_SESSION['client_id'] == "") {
     header('Location: index.php');
}
else {
     $client_id = $_SESSION['client_id'];
}

if (isset($_GET['eventkey'])) {
  $eventid = makeSafe($_GET['eventkey']);
}

$_SESSION['eventid'] = $eventid;
include_once 'adminheadertop.php';

getRequestStuff(0);
error_log("guest limit:".$maxUserRequests);

// check how many events this user has.  If only one, send them to requests.php with their eventid
$conn = mysqli_connect($host, $username, $password, $db);
$query = mysqli_query($conn, "select id from events WHERE userid='$userid'");
		$count  = mysqli_num_rows($query);
		if( $count > "1" ) {
            include 'menuadmin.php';
       	}
?>

<div class="row">
    <div class="col-md-12">
        <table class="bordered table table-sm">
            <tr>
                <td>Request Limit Per Guest:</td>
                <td>
                    <div class="event_input">
                        <span><?php if ($maxUserRequests == "0") echo "No Limit"; else echo $maxUserRequests; ?></span>
                        <input type="text" class="eventedit" name="<?php echo "maxUserRequests|".$eventid; ?>" value="<?php echo $maxUserRequests; ?>" />
                    </div>
                </td>
                <td>Show Guests' Requests</td>
                <td>
                    <div class="eventtick">
                        <span></span>
                        <input type="checkbox" class="eventtoggle" name="<?php echo "showRequests|".$eventid; ?>"
                        <?php if ($showRequests == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
                <td>Show Guests' Messages</td>
                <td>
                    <div class="eventtick">
                        <span></span>
                        <input type="checkbox" class="eventtoggle" name="<?php echo "showMessages|".$eventid; ?>"
                        <?php if ($showMessages == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
                <td>Show Your Requests to Guests</td>
                <td>
                    <div class="eventtick">
                        <span></span>
                        <input type="checkbox" class="eventtoggle" name="<?php echo "showClientRequests|".$eventid; ?>"
                        <?php if ($showClientRequests == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs request-tabs">
            <li class="nav-item">
                <a href="#addrequest" aria-controls="addrequest" class="nav-link active" data-toggle="tab">
                    <i class="fa fa-plus-circle"></i> New Request
                </a>
            </li>
            <li class="nav-item">
                <a href="#currentrequests" aria-controls="currentrequests" class="nav-link" data-toggle="tab">
                    <i class="fa fa-music"></i> Current Requests <span id="requests-badge" class="badge"></span> <span id="requests-length" class="badge"></span>
                </a>
            </li>
        </ul>
    </div>
</div>
    <!-- Errors & Messages -->
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger collapse" role="alert" id="alreadyrequested">
            <p>This track has already been requested. Please choose a different track</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success collapse" role="alert" id="goodpopup">
            <p>Your request has been entered successfully</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger collapse" role="alert" id="databaseerror">
            <p>ERROR: Whoops there was a problem with the database</p>
        </div>
    </div>
 </div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger collapse" role="alert" id="toomanyuser">
            <p>Sorry! You have reached your limit of requests</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger collapse" role="alert" id="toomany">
            <p>Sorry! The maximum number of requests has been reached</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
            <div class="alert alert-danger collapse" role="alert" id="banned">
                <p>Unknown Error</p>
            </div>
    </div>
</div>

                <!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" role="tabpanel" id="addrequest">
        <div class="search-box">Search for a track here
            <input type="text" id="searchText" autocomplete="off" placeholder="Search..." />
            <button id="clear">Clear</button>
        </div>
        <div id="result" class="result"></div>                               
        <div class="as_grid_container">
            <div class="as_gridder" id="as_gridder"></div> <!-- GRID LOADER -->
        </div>
    </div>
    <div class="tab-pane request-pane fade" role="tabpanel" id="currentrequests">
        <div id="requests-placeholder">Loading...</div>
    </div>
</div>
     <?php include_once 'footer.php'; ?>
</div>
			</div>

     </div>
 
</div>
<?php 

include 'javaincludes.php'; ?>
<script type="text/javascript">

$(document).ready(function(){

 $('.requestinfo').click(function(e){
     e.stopPropagation();
 });
 
 $(document).click(function(){
    $(".requestinfo").hide();
});
 
 
$(document).on("click", "button#clear", function() {
document.getElementById('searchText').value = "";
$('.result').hide();
});
    
     $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        /* var resultDropdown = $(this).siblings(".result"); */
        var resultDropdown = $("div").siblings(".result");
        if(inputVal.length){ 
            $('.result').show();
            $.get("requestajax.php?action=search", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
   
      // Do stuff on click of result item
    $(document).on("click", ".result button#addthis", function(){
    	var data = $(this).attr('value');
        var datasplit = data.split(';');
        var eventid = datasplit[1];
        var trackid = datasplit[0];
        var name = datasplit[2];
        var category = document.getElementById("categorysel"+trackid).value;
        var comment = document.getElementById("comment"+trackid).value;

        data = "&eventid=" + eventid + "&trackid=" + trackid + "&name=" + name + "&category=" + category + "&comment=" + comment;
        $.ajax({
            url : 'requestajax.php?action=addnewfromsearch',
            type : 'POST',
            data : data,
            success: function(data) {
                if (data.status == "alreadyrequested") {
                     setTimeout(function(){ $('#alreadyrequested').show(); }, 100);
                     setTimeout(function(){ $('#alreadyrequested').fadeOut('fast'); }, 8000);
                      $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                }

                if (data.status == "toomany") {
                     setTimeout(function(){ $('#toomany').show(); }, 100);
                     setTimeout(function(){ $('#toomany').fadeOut('fast'); }, 8000);
                      $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                }
                if (data.status == "toomanyuser") {
                     setTimeout(function(){ $('#toomanyuser').show(); }, 100);
                     setTimeout(function(){ $('#toomanyuser').fadeOut('fast'); }, 8000);
                      $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                }
                if (data.status == "banned") {
                     setTimeout(function(){ $('#banned').show(); }, 100);
                     setTimeout(function(){ $('#banned').fadeOut('fast'); }, 8000);
                      $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                }
                if (data.status == "flood") {
                     setTimeout(function(){ $('#floodalert').show(); }, 100);
                     setTimeout(function(){ $('#floodalert').fadeOut('fast'); }, 8000);
                      $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                }
                if (data.status == "success") {
                     setTimeout(function(){ $('#goodpopup').show(); }, 100);
                     setTimeout(function(){ $('#goodpopup').fadeOut('fast'); }, 5000);
                     // update the requests tab badge / list
                     $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                     LoadRequests();
                     // clear the form
                    // $('#gridder_addform').trigger("reset");
                     
                }
                // LoadGrid();
            }
        });
    });
});  
	// BEGIN EVENTEDIT STUFF
    
    // On click, do the toggle thing
        $('body').delegate('.eventtoggle', 'click', function(){
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
			data : UrlToPass,
            success: function() {
				LoadRequests();
			}
		});
	});

    
    // Show the eventedit text box on click
	$('body').delegate('.event_input', 'click', function(){
		var ThisElement = $(this);
		ThisElement.find('span').hide();
		ThisElement.find('.eventedit').show().focus();
	});

	// Pass and save the textbox values on blur function
	$('body').delegate('.eventedit', 'blur', function(){
		var ThisElement = $(this);
		ThisElement.hide();
		ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
		var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
		
		$.ajax({
			url : 'adminajax.php',
			type : 'POST',
			data : UrlToPass,
                success: function() { 
                    LoadRequests();
                    }
		});
	});

	// Same as the above blur() when user hits the 'Enter' key
	$('body').delegate('.eventedit', 'keypress', function(e){
		if(e.keyCode == '13') {
			var ThisElement = $(this);
			ThisElement.hide();
			ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
			var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
			$.ajax({
				url : 'adminajax.php',
				type : 'POST',
				data : UrlToPass,
                success: function() { 
                    LoadRequests();
                    }
			});
		}
	});
    
    // END EVENTEDIT STUFF
	
    $('body').delegate('#add', 'click', function (e) {
        var ThisElement = $(this);
        
       ThisElement.parent().parent().next().show();
       e.stopPropagation();

    });
    
	// Show the tickable thing on click
        $('body').delegate('.editcategory', 'click', function(){
            var ThisElement = $(this);
            ThisElement.find('span').hide();
            ThisElement.find('.cat_input').show().focus();
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
			data : UrlToPass,
                success: function() { 
                    LoadRequests();
                    }
		});
	});

	// Pass and save the textbox values on blur function
	$('body').delegate('.cat_input', 'blur', function(){
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
			data : UrlToPass,
                     success: function() {
                                LoadRequests();
                        }
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
			data : UrlToPass,
                success: function() { 
                    LoadRequests();
                    }
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
				data : UrlToPass,
                success: function() { 
                    LoadRequests();
                    }
			});
		}
	});
	
	// Function for deleting a request
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm('Are you sure want to delete this request?');
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
				LoadRequests();
			}
		});
		return false;
	});
	
        		// Function to hide all errors
    function HideErrors() {
        $('.error').hide();
    }

    // Function for loading the grid
    function LoadGrid() {
        var gridder = $('#as_gridder');
        var UrlToPass = 'action=load';
        gridder.html('<i class="fa fa-spinner fa-spin" style="font-size:120px;color:purple"></i>Loading...');
        $.ajax({
            url : 'requestajax.php',
            type : 'POST',
            data : UrlToPass,
            success: function(responseText) {
                gridder.html(responseText);
            }
        });
    }

// Function for loading the search
    function LoadSearch() {
        var search = $('#livesearchbox');
        var UrlToPass = 'action=search';
        search.html('<i class="fa fa-spinner fa-spin" style="font-size:120px;color:purple"></i>Loading...');
        $.ajax({
            url : 'requestajax.php',
            type : 'POST',
            data : UrlToPass,
            success: function(responseText) {
                search.html(responseText);
            }
        });
    }

    // Function to populate requests
    function LoadRequests() {
        var therequests = $('#requests-placeholder');
        var UrlToPass = 'action=populaterequests';
        therequests.html('<i class="fa fa-spinner fa-spin" style="font-size:120px;color:purple"></i>Loading...');
        $.ajax({
            url : 'requestajax.php?rnd=',
            type : 'POST',
            data : UrlToPass,
            success: function(responseText) {
                therequests.html(responseText);
            }
        });
    }

    // update current requests on tab click
    $('a[data-toggle="tab"][aria-controls="currentrequests"]').on('shown.bs.tab', function (e) {
        LoadRequests();
    })


    $(function(){

        LoadSearch();
        LoadGrid(); // Load the grid on page loads

        LoadRequests(); // Load the requests
        // disable form default submit
        $("#cpa-form").submit(function(e){
            e.preventDefault();
        });

        // Pass the values to ajax page to add the values
        $('body').delegate('#gridder_addrecord', 'click', function(){
            //clear any existing error messages
            HideErrors();
            var suberrors = 0;
            // Do insert validation here
            if($('#name').val() == "") {
                $('#name').focus();
                $('#nameerror').show(); 
                ++suberrors;
            }

            if($('#name').val().length > 64) {
                $('#name').focus();
                $('#nameerror_tl').show(); 
                ++suberrors;
            }

            if($('#artist').val() == '') {
                $('#artist').focus();
                $('#artisterror').show(); 
                ++suberrors;
            }

            if($('#artist').val().length > 64) {
                $('#artist').focus();
                $('#artisterror_tl').show(); 
                ++suberrors;
            }

            if($('#title').val() == '') {
                $('#title').focus();
                $('#titleerror').show(); 
                ++suberrors;
            }

            if($('#title').val().length > 64) {
                $('#title').focus();
                $('#titleerror_tl').show(); 
                ++suberrors;
            }

            if($('#message').val().length > 140) {
                $('#message').focus();
                $('#messageerror_tl').show(); 
                ++suberrors;
            }
            if(suberrors > 0) {
                alert("There was a problem with your request.");
            }
            if(suberrors == 0) {
                // Pass the form data to the ajax page
                var data = $('#gridder_addform').serialize();
                $.ajax({
                    url : 'requestajax.php',
                    type : 'POST',
                    data : data,
                    success: function(data) {
                        if (data.status == "toomany") {
                             setTimeout(function(){ $('#toomany').show(); }, 100);
                             setTimeout(function(){ $('#toomany').fadeOut('fast'); }, 8000);
                              $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                        }
                        if (data.status == "toomanyuser") {
                             setTimeout(function(){ $('#toomanyuser').show(); }, 100);
                             setTimeout(function(){ $('#toomanyuser').fadeOut('fast'); }, 8000);
                              $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                        }
                        if (data.status == "banned") {
                             setTimeout(function(){ $('#banned').show(); }, 100);
                             setTimeout(function(){ $('#banned').fadeOut('fast'); }, 8000);
                              $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                        }
                        if (data.status == "flood") {
                             setTimeout(function(){ $('#floodalert').show(); }, 100);
                             setTimeout(function(){ $('#floodalert').fadeOut('fast'); }, 8000);
                              $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                        }
                        if (data.status == "success") {
                             setTimeout(function(){ $('#goodpopup').show(); }, 100);
                             setTimeout(function(){ $('#goodpopup').fadeOut('fast'); }, 5000);
                             $('html, body').animate({ scrollTop: $('.nav').offset().top}, 250);
                             // update the requests tab badge / list
                             LoadRequests();
                             // clear the form
                             $('#gridder_addform').trigger("reset");
                        }
                        LoadRequests();
                    }
                });
            }
            return false;
        });
    });
</script>
</body>
</html>
