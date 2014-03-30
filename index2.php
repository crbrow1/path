<?php 
session_start(); 
include_once("config.php");

if(isset($_GET["logout"]) && $_GET["logout"]==1)
{
//User clicked logout button, destroy all session variables.
session_destroy();
header('Location: '.$return_url);
}

//Pull Thought IDs for URLs
mysql_connect($hostname, $db_username, $db_password)or die('Can\'t establish a connection to the server: ' . mysql_error()); 

mysql_query("USE $db_name")or die('Can\'t establish a connection to the database: ' . mysql_error());

$id_query="SELECT create_thought.thought_id FROM create_thought";
$thought_id=mysql_query($id_query);

?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en-gb" lang="en-gb" >
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>Path</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css"/>

<!-- Call javascript -->	
<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="js/shim.js" type="text/javascript"></script>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="js/kinetic-v4.5.0.min.js" type="text/javascript"></script>
<script src="js/jquery.nyroModal.custom.js" type="text/javascript"></script>
<!--[if IE 6]>
	<script type="text/javascript" src="js/jquery.nyroModal-ie6.min.js"></script>
<![endif]-->

<!-- Nyro Modal Lightbox Window -->
<script type="text/javascript">
$(function() {
  $('.nyroModal').nyroModal();
});
</script>

<!-- Facebook Login - post to our database -->
<script>
function AjaxResponse()
 {
	 var myData = 'connect=1'; //We will pass a post variable, check process_facebook.php
	 jQuery.ajax({
	 type: "POST",
	 url: "process_facebook.php",
	 dataType:"html",
	 data:myData,
	 success:function(response){
	 $("#results").html('<div>'+response+'</div>'); //Result
 },
	 error:function (xhr, ajaxOptions, thrownError){
	 $("#results").html('<div class="loading">'+thrownError+'</div>'); //Error
 	}
 });
 }
 
function LodingAnimate() //Show loading Image
{
	$("#LoginButton").hide(); //hide login button once user authorize the application
	$("#results").html('<div class="loading"><img src="images/ajax-loader.gif" /> Please Wait Connecting...</div>'); //show loading image while we process user
}

function ResetAnimate() //Reset User button
{
	$("#LoginButton").show(); //Show login button 
	$("#results").html(''); //reset element html
}

 </script>
</head>
<body>

<div id="navbg">
<div id="container">
    
<?php

if(!isset($_SESSION['logged_in']))
{
?>

    <div id="results"></div>
    <div id="topnav">
        <div id="fb-button">
            <div id="LoginButton">
                <div class="fb-login-button" onlogin="javascript:CallAfterLogin();" size="medium" scope="<?php echo $fbPermissions; ?>">Log In</div>
            </div>
        </div>
    </div>

<?php
}
else
{
	echo '<div id="topnav">
		<ul>
			<li id="username">' . $_SESSION['user_name'] . '</li>
			<li><a href="?logout=1">Log Out</a></li>
			<li><a href="my-thoughts.php" class="nyroModal">My Thoughts</a></li>
			<li><a href="create-thought.php" class="nyroModal">Create a Thought</a></li>
		</ul>
		</div>';
}
//Note there are three instances of this php echo code based on how the user logs in. It needs to be updated in process_facebook.php as well.
?>
    
    <a href="index.php"><img src="images/logo.png" id="logo" alt="Path: Share beliefs. Share life."></a>
    
    <div id="fb-root"></div>
    <script type="text/javascript">
		window.fbAsyncInit = function() {
		FB.init({appId: '<?php echo $appId; ?>',cookie: true,xfbml: true,channelUrl: '<?php echo $return_url; ?>channel.php',oauth: true});};
		(function() {var e = document.createElement('script');
		e.async = true;e.src = document.location.protocol +'//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);}());
		
		function CallAfterLogin(){
				FB.login(function(response) {		
				if (response.status === "connected") 
				{
					LodingAnimate(); //Animate login
					FB.api('/me', function(data) {
					  if(data.email == null)
					  {
							//Facbebook user email is empty, you can check something like this.
							alert("You must allow us to access your email"); 
							ResetAnimate();
		
					  }else{
							AjaxResponse();
					  }
				  });
				 }
			});
		}
    </script>
    
    <nav>
    	<ul>
        	<li><a href="tags.php?tag=afterlife">Afterlife</a>
                <ul>
                    <li><a href="tags.php?tag=enlightenment">Enlightenment</a></li>
                    <li><a href="tags.php?tag=liberation">Liberation</a></li>
                    <li><a href="tags.php?tag=nonbeing">Non-being</a></li>
                    <li><a href="paradise">Paradise/hell</a></li>
                    <li><a href="tags.php?tag=reincarnation">Reincarnation</a></li>
                    <li><a href="tags.php?tag=journey">Spiritual Journey</a></li>
                </ul></li>
        	<li><a href="#">The Divine</a>
                <ul>
                    <li><a href="#">Brahman</a></li>
                    <li><a href="#">One God</a></li>
                    <li><a href="#">Gods</a></li>
                    <li><a href="#">Karma</a></li>
                    <li><a href="#">Pantheism</a></li>
            </ul></li>
        	<li><a href="#">Life's Purpose</a>
                <ul>
                    <li><a href="#">Enlightenment</a></li>
					<li><a href="#">Inner Peace</a></li>
                    <li><a href="#">Liberation from Reincarnation</a></li>
                    <li><a href="#">Serve God</a></li>
            </ul></li>
        	<li><a href="#">Practices</a>
            <ul>
                    <li><a href="#">Baptism</a></li>
                    <li><a href="#">Charity</a></li>
                    <li><a href="#">Fasting</a></li>
                    <li><a href="#">Mantras</a></li>
                    <li><a href="#">Meditation</a></li>
                    <li><a href="#">Offerings</a></li>
                    <li><a href="#">Pilrimage</a></li>
                    <li><a href="#">Prayer</a></li>
                    <li><a href="#">Rights of Passage</a></li>
                    <li><a href="#">Study Sacred Texts</a></li>
                    <li><a href="#">Worship</a></li>
                    <li><a href="#">Yoga</a></li>
            </ul></li>
        	<li><a href="#">Religion</a>
            <ul>
                    <li><a href="#">Bah&aacute;'i</a></li>
                    <li><a href="#">Buddhism</a></li>
                    <li><a href="#">Christianity</a></li>
                    <li><a href="#">Hinduism</a></li>
                    <li><a href="#">Jainism</a></li>
                    <li><a href="#">Judaism</a></li>
                    <li><a href="#">Islam</a></li>
                    <li><a href="#">Shinto</a></li>
                    <li><a href="#">Sikhism</a></li>
                    <li><a href="#">Taoism</a></li>
            </ul></li>
        	<li><a href="#">Sacred Texts</a>
            <ul>
                    <li><a href="#">Adi Granth</a></li>
                    <li><a href="#">Bhagavad Gita</a></li>
                    <li><a href="#">Chuang-Tzu</a></li>
                    <li><a href="#">Hadith</a></li>
                    <li><a href="#">Kitab-i-Aqbas</a></li>
                    <li><a href="#">Kojiki</a></li>
                    <li><a href="#">Mahavira teachings</a></li>
                    <li><a href="#">Mahayana Sutras</a></li>
                    <li><a href="#">New Testament</a></li>
                    <li><a href="#">Nihon-gi</a></li>
                    <li><a href="#">Old Testament</a></li>
                    <li><a href="#">Qu'ran</a></li>
                    <li><a href="#">Ramayana</a></li>
                    <li><a href="#">Talmud</a></li>
                    <li><a href="#">Tao Te Ching</a></li>
                    <li><a href="#">Tripitaka</a></li>
                    <li><a href="#">Upanishads</a></li>
                    <li><a href="#">The Vedas</a></li>
            </ul></li>
        	<li><a href="#">Sacred People</a>
            <ul>
                    <li><a href="#">Bah&aacute;'u'll&aacute;h</a></li>
                    <li><a href="#">Guru Nanak</a></li>
                    <li><a href="#">Jesus Christ</a></li>
                    <li><a href="#">Lao-Tzu</a></li>
                    <li><a href="#">Mahavira</a></li>
                    <li><a href="#">Muhammad</a></li>
                    <li><a href="#">Siddharta Gautama</a></li>
            </ul></li>
        	<li><a href="#">Sacred Spaces</a>
            <ul>
                    <li><a href="#">Church</a></li>
                    <li><a href="#">Gurdwara</a></li>
                    <li><a href="#">House of Worship</a></li>
                    <li><a href="#">Meditation Hall</a></li>
                    <li><a href="#">Mosque</a></li>
                    <li><a href="#">Synagogue</a></li>
                    <li><a href="#">Temple</a></li>
            </ul></li>
        </ul>
     </nav>
    
<!-- Node display -->
<script type="text/javascript">

<?php

function viewThought() {
  if ( !isset($_GET["thought_id"]) || !$_GET["thought_id"] ) {
    homepage();
    return;
  }

  $result = array();
  $result['thought_title'] = Article::getById( (int)$_GET["thought_id"] );
  require( TEMPLATE_PATH . "/view-thought.php" );
}
?>

var w = window.innerWidth - 5,
    h = window.innerHeight - 5;

    if (window.innerWidth < 980){
        w = window.innerWidth - 5;
    }

var vis = d3.select("body").append("svg:svg")
    .attr("width", w)
    .attr("height", h);

d3.json("nodes.php", function(json) {
	
    var force = d3.layout.force()
        .nodes(json.nodes)
        .links(json.links)
        //.gravity(.05)
        .distance(100)
        .charge(-120)
        .size([w, h])
        .start();

    var link = vis.selectAll("line.link")
        .data(json.links)
        .enter().append("svg:line")
        .attr("class", "link");

    var node = vis.selectAll("g.node")
        .data(json.nodes)
        .enter().append("svg:g")
        .attr("class", "node")
        .call(force.drag);

    force.on("tick", function() {
        link.attr("x1", function(d) {return d.source.x;})
            .attr("y1", function(d) {return d.source.y;})
            .attr("x2", function(d) {return d.target.x;})
            .attr("y2", function(d) {return d.target.y;});

        node.attr("transform", function(d) {
            return "translate(" + d.x + "," + d.y + ")";
        });
    });
		
    node.append("a")
        .append("rect")
        .attr("id", "node-textbg")
        .attr("x", 5)
        .attr("y", 5)
        .attr("width", 200)
        .attr("height", 50)
        .style("fill", "#f1f1ee")
        .style("fill-opacity", .8)
        .style("stroke-opacity", 0);
        
    node.append("svg:text")
        .attr("class", "node-text")
        .attr("dx", 15)
        .attr("dy", 25)
        .text(function(d) {return d.name});

    node.append("a")
        .attr("xlink:href", function(d) {return ".?action=viewThought&amp;thoughtId=" + d.id;})
		.attr("class", "nyroModal")
        .append("circle")
        .attr("cx", 0)
        .attr("cy", 0)
        .attr("r", 5)
        .style("fill", "#9d9994")
	    .style("stroke", "#fff");

});

//Window Resize
window.onresize = time;

function time() {
  setTimeout(resize, 400);
};

function resize() {
  location.reload();
};

</script>

</div><!--container-->
</div><!--navbg-->


</body>
</html>
