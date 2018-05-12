<?php require_once('../Connections/killjoy.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_answers_list = 1;
$pageNum_rs_answers_list = 0;
if (isset($_GET['pageNum_rs_answers_list'])) {
  $pageNum_rs_answers_list = $_GET['pageNum_rs_answers_list'];
}
$startRow_rs_answers_list = $pageNum_rs_answers_list * $maxRows_rs_answers_list;

$colname_rs_answers_list = "-1";
if (isset($_GET['q'])) {
  $colname_rs_answers_list = $_GET['q'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_answers_list = "SELECT *, tbl_faq.id as faqId, g_image AS contributorImage FROM tbl_faq LEFT JOIN social_users ON social_users.g_email = tbl_faq.contributor WHERE (CONVERT(title USING utf8) LIKE '%$colname_rs_answers_list%' OR CONVERT(instructions USING utf8) LIKE '% $colname_rs_answers_list%' ) AND (CONVERT(title USING utf8) LIKE '%$colname_rs_answers_list%' OR CONVERT(instructions USING utf8) LIKE '% $colname_rs_answers_list%')";
$query_limit_rs_answers_list = sprintf("%s LIMIT %d, %d", $query_rs_answers_list, $startRow_rs_answers_list, $maxRows_rs_answers_list);
$rs_answers_list = mysqli_query( $killjoy, $query_limit_rs_answers_list) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_answers_list = mysqli_fetch_assoc($rs_answers_list);

if (isset($_GET['totalRows_rs_answers_list'])) {
  $totalRows_rs_answers_list = $_GET['totalRows_rs_answers_list'];
} else {
  $all_rs_answers_list = mysqli_query($GLOBALS["___mysqli_ston"], $query_rs_answers_list);
  $totalRows_rs_answers_list = mysqli_num_rows($all_rs_answers_list);
}
$totalPages_rs_answers_list = ceil($totalRows_rs_answers_list/$maxRows_rs_answers_list)-1;


$queryString_rs_answers_list = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_answers_list") == false && 
        stristr($param, "totalRows_rs_answers_list") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_answers_list = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_answers_list = sprintf("&totalRows_rs_answers_list=%d%s", $totalRows_rs_answers_list, $queryString_rs_answers_list);
$faqid = $row_rs_answers_list['faqId'];

$words=-1;
if (isset($_GET['q'])) {
$words = $_GET['q'];
}

$newdate = date("d-M-Y", strtotime($row_rs_answers_list['date_modified']));  // the data for the structured markup

$instructions = utf8_encode($row_rs_answers_list['instructions']);
$instructions = preg_replace("/\w*?$words\w*/i", "<b>$0</b>", $instructions);

$instructions = explode(";",$instructions);




$total = count($instructions)+1;
$i= 0;
$i++;


$colname_rs_vote_count = "-1";
if (isset($faqid )) {
  $colname_rs_vote_count = $faqid;
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_vote_count = sprintf("SELECT COUNT(CASE WHEN tbl_faq_votes.vote = 'yes' then tbl_faq_votes.faq_id=1 END) AS upVote, COUNT(CASE WHEN tbl_faq_votes.vote = 'no' then tbl_faq_votes.faq_id=1 END) AS downVote, COUNT(CASE WHEN tbl_faq_votes.vote = 'undecided' then tbl_faq_votes.faq_id=1 END) AS neutral, COUNT(tbl_faq_votes.vote) AS totalVotes, (SELECT TRUNCATE(COUNT(CASE WHEN tbl_faq_votes.vote = 'yes' then tbl_faq_votes.faq_id=1 END)/COUNT(tbl_faq_votes.vote),2) AS totalVotes)*100 AS yespercentage,  (SELECT TRUNCATE(COUNT(CASE WHEN tbl_faq_votes.vote = 'no' then tbl_faq_votes.faq_id=1 END)/COUNT(tbl_faq_votes.vote),2) AS totalVotes)*100 AS nopercentage, (SELECT TRUNCATE(COUNT(CASE WHEN tbl_faq_votes.vote = 'undecided' then tbl_faq_votes.faq_id=1 END)/COUNT(tbl_faq_votes.vote),2) AS totalVotes)*100 AS undecidedcentage  FROM tbl_faq_votes WHERE tbl_faq_votes.faq_id = %s", GetSQLValueString($colname_rs_vote_count, "int"));
$rs_vote_count = mysqli_query( $killjoy, $query_rs_vote_count) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_vote_count = mysqli_fetch_assoc($rs_vote_count);
$totalRows_rs_vote_count = mysqli_num_rows($rs_vote_count);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>killjoy.co.za - help and support - faq</title>

<link rel="alternate" href="https://www.killjoy.co.za/" hreflang="en" />
<link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-icon-60x60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-icon-180x180.png" />
<link rel="icon" type="image/png" sizes="192x192"  href="favicons/android-icon-192x192.png" />
<link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96x96.png" />
<link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="css/support.css" rel="stylesheet" type="text/css">
<script src="../fancybox/libs/jquery-3.3.1.min.js" ></script>
<script type="text/javascript" src="../kj-autocomplete/lib/jQuery-1.4.4.min.js"></script>
<script type="text/javascript" src="../kj-autocomplete/jquery.autocomplete.js"></script>
<link href="../kj-autocomplete/jquery.answerfinder.css" rel="stylesheet" type="text/css" />
<link href="css/radios.css" rel="stylesheet" type="text/css">
<style>
	
		/*styling for upvote including inverted text--by http://www.iwanross.co.za*/
	
	.vote-summary-upvote {
	position: absolute;
	height:35px;
	width:20%;
	left:10%;	
	line-height: 35px;
	border:solid thin #2dc937;
	text-align: left;
	color:ghostwhite;
	font-family: Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", "serif";
	font-weight: 700;
	border-radius: 5px;
	
	
	
}

.vote-summary-upvote:before,
.vote-summary-upvote:after {
     text-indent: 10px;
    position: absolute;
    white-space: nowrap;
    overflow: hidden;
    content: attr(data-content);
	}

.vote-summary-upvote:before {
   
	background:white;
    color: #2dc937;
	text-shadow: 1px 1px 3px #2dc937;
    width: 100%;
	}

.vote-summary-upvote:after {
     background:#2dc937;
    color: white;
	text-shadow: 1px 1px 3px white;
	 width: <?php echo $row_rs_vote_count['yespercentage']; ?>%;
}

	
	/*styling for downvote including inverted text--by http://www.iwanross.co.za*/
	
	.vote-summary-downvote {	
	
	position: absolute;
	height:35px;
	width:20%;
	left:30%;		
	line-height: 35px;
	border:solid thin #cc3232;
	text-align: left;
	color:ghostwhite;
	font-family: Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", "serif";
	font-weight: 700;
	border-radius: 5px;
	
	
}
.vote-summary-downvote:before,
.vote-summary-downvote:after {
     text-indent: 10px;
    position: absolute;
    white-space: nowrap;
    overflow: hidden;
    content: attr(data-content);
	}

.vote-summary-downvote:before {
   
	background:white;
    color: #cc3232;
	text-shadow: 1px 1px 3px #cc3232;
    width: 100%;
	}

.vote-summary-downvote:after {
     background:#cc3232;
    color: white;
	text-shadow: 1px 1px 3px white;
	 width: <?php echo $row_rs_vote_count['undecidedcentage']; ?>%;
}
	
/*styling for novote including inverted text--by http://www.iwanross.co.za*/	
.vote-summary-novote {
   position: absolute;	
	height:35px;
	width:20%;
	left:50%;			
	line-height: 35px;
	border:solid thin #e7b416;
	text-align: left;
	color:ghostwhite;
	font-family: Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", "serif";
	font-weight: 700;
	border-radius: 5px;
}

.vote-summary-novote:before,
.vote-summary-novote:after {
     text-indent: 10px;
    position: absolute;
    white-space: nowrap;
    overflow: hidden;
    content: attr(data-content);
	}

.vote-summary-novote:before {
   
	background:white;
    color: #e7b416;
	text-shadow: 1px 1px 3px #e7b416;
    width: 100%;
	}

.vote-summary-novote:after {
     background:#e7b416;
    color: white;
	text-shadow: 1px 1px 3px white;
	 width: <?php echo $row_rs_vote_count['undecidedcentage']; ?>%;
}
	</style>
	 <script type="application/ld+json">
/*structerd data markup compiled by http://www.midnightowl.co.za */
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "https://www.killjoy.co.za/index.php",
      "name": "Home",
      "image": "https://www.killjoy.co.za/images/icons/home-icon.png"
    }
  },{
    "@type": "ListItem",
    "position": 2,
    "item": {
      "@id": "https://www.killjoy.co.za/help-and-support/index.php",
      "name": "Find answer to your questions",
      "image": "https://www.killjoy.co.za/images/icons/killjoy-app-icon.png"
    }
    }]
}
</script>
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "Question",
    "text": "How do I <?php echo $row_rs_answers_list['title']; ?>?",
    "upvoteCount": "<?php echo $row_rs_vote_count['totalVotes']; ?>",
      "dateCreated": "<?php echo $row_rs_answers_list['date_modified']; ?>",
    "author": {
        "@type": "Person",
        "name": "Killjoy User"
    },
    "answerCount": "<?php echo $totalRows_rs_answers_list ?>",
    "acceptedAnswer": {
        "@type": "Answer",
        "upvoteCount": "<?php echo $row_rs_vote_count['upVote']; ?>",
        "text": "(<?php echo $instructions ?>).",
        "dateCreated": "<?php echo $row_rs_answers_list['date_modified']; ?>",
        "author": {
            "@type": "Person",
            "name": "<?php echo $row_rs_answers_list['contributor']; ?>"
        }
    }
}
</script>
</head>

<body>

<div class="header"><div class="header-text"><h1><span style="padding-right: 25px; vertical-align: middle;" class="icon-life-bouy"></span>Help and Support</h1></div><div class="search-container"><div class="search-text">Find answers to your questions</div><div class="search-box"><form action="index.php" name="findanswers" id="findanswers"><input placeholder="type a question to find an answer" autofocus class="searchfield" type="search" data-type="search" name="q" id="q"><input type="submit" style="position: absolute; left: -9999px"/></form></div></div></div>
<?php if ($totalRows_rs_answers_list > 0) { // Show if recordset not empty ?>
  <div class="search-results-container"><div class="search-results-header">
    <h2><span style="color: #56B2D7;"><?php echo $totalRows_rs_answers_list ?></span> <?php if($totalRows_rs_answers_list < 2) { ?>Result<?php } ?><?php if($totalRows_rs_answers_list > 1) { ?>Results<?php } ?> for <?php echo $_GET['q'] ?></h2>
  </div>
    <div class="contributor-imagebox"><img class="contributor-image" src="../<?php echo $row_rs_answers_list['contributorImage']; ?>" alt="help and support contributor image"/></div>
    <div class="contributor-name">This contribution was made by <span style="color: #56B2D7; font-weight: 600"><?php echo $row_rs_answers_list['g_name']; ?></span> on <?php echo $newdate ?></div>
    <div class="search-results-title"><span style="vertical-align: 0px; padding-right: 15px;" class="icon-question-circle-o"></span><?php echo $row_rs_answers_list['title'] ?> -- <span class="icon-tags"></span> <?php echo $row_rs_answers_list['ralation']; ?></div>
    <div class="search-results-instructions"><?php foreach ($instructions as $name => $value) {
		$name = $i++;		
    print ("<div class='numbers'>$name</div>  $value. ");    
} 	?></div>
    <div class="search-results-vote-title">Did you find this answer useful? </div>
    <div class="search-results-vote-buttons">
      <div class="vote-selector">
        <fieldset onChange="voting_count('<?php echo($faqid); ?>')"class="fieldset">
          <input title="yes" id="happy" type="radio" name="vote-selector" value="yes" />
          <label class="votebutton-is happy" for="happy"></label>    
          <input id="sad" type="radio" name="vote-selector" value="no" />
          <label class="votebutton-is sad" for="sad"></label>
          <input id="average" type="radio" name="vote-selector" value="undecided" />
          <label class="votebutton-is average" for="average"></label>   
        </fieldset>
      </div>
    </div>
    <?php if ($totalRows_rs_vote_count > 0) { // Show if recordset not empty ?>
      <div id="votesummary" class="vote-summary-container">
        <div class="vote-summary-upvote" data-content="<?php echo round($row_rs_vote_count['yespercentage'],0); ?>%"></div>
        <div class="vote-summary-downvote" data-content="<?php echo round($row_rs_vote_count['nopercentage'],0); ?>%"></div>
        <div class="vote-summary-novote" data-content="<?php echo round($row_rs_vote_count['undecidedcentage'],0); ?>%" ></div>
      </div>
      <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_rs_answers_list > 0) { // Show if recordset not empty ?>
      <div class="navbar"><div class="first-answer"><?php if ($pageNum_rs_answers_list > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_answers_list=%d%s", $currentPage, min($totalPages_rs_answers_list, $pageNum_rs_answers_list - 1), $queryString_rs_answers_list); ?>">Previous answer</a>
        <?php } // Show if not first page ?></div><div class="next-answer"><?php if ($pageNum_rs_answers_list < $totalPages_rs_answers_list) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rs_answers_list=%d%s", $currentPage, min($totalPages_rs_answers_list, $pageNum_rs_answers_list + 1), $queryString_rs_answers_list); ?>">Next Answer</a>
          <?php } // Show if not last page ?></div></div>
      <?php } // Show if recordset not empty ?>
  </div>
  <?php } // Show if recordset not empty ?>
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function(){
$j("#q").autocomplete("../kj-autocomplete/findanswers.php", {
			 minLength: 10, 
			 delay: 500,
	         selectFirst: false
	        

});
 $j("#q").result(function() {
$j("#findanswers").submit();
$j("#q").val('');	 
});
 });

 
</script>

<script type="text/javascript">
 function voting_count ( faqid ) 
{ $.ajax( { type    : "POST",
data: {"txt_faqid" : faqid, "txt_feeling" :$("input[name=vote-selector]:checked").val()},
url     : "../functions/faqvotingselector.php",
success : function ( faqid )
		  {     
		 window.location.reload(true);
		   		  },
		error   : function ( xhr )
		  { alert( "error" );
		  }
		  
} );
 return false;	
}
</script>

</body>
</html>

