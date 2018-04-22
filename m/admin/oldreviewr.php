<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

<div class="imagebox" id="imagebox"><label title="upload a photo for this property" for="files">
  <?php if ($totalRows_rs_property_image == 0) { // Show if recordset not empty ?>
    <img title="click me to add a photo for this property" class="addhouse" src="../media/image-add-512.png" />
    <?php } // Show if recordset empty ?>
    <div id="wrapper" class="wrapper">
    <?php if ($totalRows_rs_property_image > 0) { // Show if recordset not empty ?>
    <img src="<?php echo $row_rs_property_image['../image_url']; ?>" alt="killjoy.co.za rental property image" class="propertyphoto"/> 
    <span title="remove this rental property photo" onClick="unlink_thumb('<?php echo $id;?>')" class="propclose"></span>
      <?php } // Show if recordset empty ?>
    </label>     
    </div>
</body>
<input onChange="return acceptimage()"  id="files" name="files[]" type="file" accept="image/x-png,image/gif,image/jpeg" /></div>
<div id="uploader" class="uploader"><img src="../images/loading24x24.gif" width="24" height="24" alt="killjoy.co.za member profile image upload status indicator" class="indicator" />Uploading</div>
<div class="logoloaderrors" id="logoloaderror"><?php if ($totalRows_show_error > 0) { // Show if recordset empty ?><ol>
<?php do { ?><li><?php echo $row_show_error['error_message']; ?><?php } while ($row_show_error = mysql_fetch_assoc($show_error)); ?></li>
</ol>
<?php } ?>
</div>

</html>