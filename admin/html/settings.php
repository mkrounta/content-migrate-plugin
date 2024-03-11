<?php
if (isset($_POST['submit']))
{
    $msg = ContentMigration_Admin::insertingDatabaseDetail($_POST);
    if ($msg['status'] === "Success")
    {
        ContentMigration_Admin::showSccess("Inserted Successfully.");
    }
    elseif ($msg['status'] === "Error")
    {
        ContentMigration_Admin::showError("Content Already Exists.");
    }
    else
    {
        ContentMigration_Admin::showError("No Data Selected For Insertion.");
    }
}
?>
  <?php
if (isset($_POST['update']))
{ 
    $response = ContentMigration_Admin::UpdateDatabaseDetail($_POST);
    if ($response['status'] === "Success")
    {
        ContentMigration_Admin::showSccess($response['Message']);
    }
    else
    {
        ContentMigration_Admin::showError($response['Message']);
    }

}
?>
    <div class="container">
<div class="col-md-12">
  <div class="row">
      <div class="col-lg-12 mt-4 mb-1">
         <h3>Settings</h3>
      </div>    
  </div>
</div>
<?php
$records = ContentMigration_Admin::getingDatabaseDetail(); //getting updated pages
if (!$records || $records == null )
{
?>
<form name="validateForm" class="form-horizontal content-migration" method="POST">
  <fieldset>
    <legend>
Accessibility</legend>
 <div class="form-group col-lg-12">
   <label for="email">Access Email (<small>Please enter comma separated emails to assign access.</small>):</label>
   <input type="email" required="required" class="form-control" id="email" name="ëmail" placeholder="">
 </div>
  </fieldset>
  <fieldset>
    <legend>Target Database Configuration</legend>
  <div class="form-group col-lg-12">
    <label class="control-label" for="host">DB Host</label>
       <input type="text" required="required" class="form-control" id="host" name="host" placeholder=""> 
  </div>
  <div class="form-group col-lg-12">
  <label class="control-label" for="uname">DB Username</label>
      <input type="text" required="required" class="form-control" id="uname" name="üname" placeholder="">
  </div>
  <div class="form-group col-lg-12">
  <label class="control-label" for="pwd">DB Password</label>
      <input type="password" class="form-control" id="pwd" name="pwd" placeholder="">
  </div>
  <div class="form-group col-lg-12">
  <label class="control-label" for="pwd">Database Name</label>
      <input type="text" required="required" class="form-control" id="dbname" name="dbname" placeholder="">
  </div>

  <div class="form-group col-lg-12">
  <label class="control-label" for="pwd">Site Url</label>
      <input type="text" class="form-control" id="site_url" name="site_url" placeholder="">
      <label>Note:- Please don't use '/' at end of url</label>
  </div>

  <div class="form-group col-lg-12">
    <div class="col-sm-offset-2">
    <input type="submit" name="submit" value="Save" class="btn btn-primary" value="Submit">  
    </div>
  </div>
  </fieldset>
</form>
<?php
}
else
{
  $item = $records;
?>

  <form name="validateForm" class="form-horizontal content-migration" method="POST">
<?php $id = $item->id; ?>
<fieldset>
    <legend>
Accessibility</legend>
  <div class="form-group col-lg-12">
    <label for="email">Access Email (<small>Please enter comma separated emails to assign access.</small>):</label>
      <input type="email" class="form-control" id="email" name="ëmail" value="<?php echo $item->auth_email; ?>">
  </div>
</fieldset>
<fieldset>
    <legend>Target Database Configuration</legend>
  <div class="form-group col-lg-12">
    <label for="host">DB Host</label>
      <input type="text" required="required" class="form-control" id="host" name="host" value="<?php echo $item->db_host; ?>">
  </div>
  <div class="form-group col-lg-12">
  <label for="uname">DB Username</label>
      <input type="text" required="required" class="form-control" id="uname" name="üname" value="<?php echo $item->db_username; ?>">
  </div>
  <div class="form-group col-lg-12">
  <label for="pwd">DB Password</label>
      <input type="password"  class="form-control" id="pwd" name="pwd" value="<?php echo $item->db_passworsd; ?>">
  </div>
  <input type="hidden" name="id" value="<?php echo $item->id; ?>">
  <div class="form-group col-lg-12">
  <label for="pwd">Database Name</label>
      <input type="text" required="required" class="form-control" id="dbname" name="dbname" value="<?php echo $item->db_name; ?>">
  </div>

  <div class="form-group col-lg-12">
  <label for="pwd">Site Url</label>
      <input type="text" class="form-control" id="site_url" name="site_url" value="<?php echo $item->site_url; ?>">
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="submit" name="update" class="btn btn-primary" value="Update">  
    </div>
  </div>
</fieldset>
</form>
<?php
    // }
}
?>
</div>
