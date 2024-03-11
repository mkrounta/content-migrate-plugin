<?php
require_once dirname(dirname(__FILE__)) . '/class-ContentMigration-admin.php';
if (isset($_POST['submit']))
{
    if (isset($_POST['id']) && count($_POST['id']) > 0)
    {
      
        $response = ContentMigration_Admin::insertingRecords($_POST);
       // $responses = ContentMigration_Admin::updateExistingPageContent($_POST);
        if(@$response['status'] === "Error")
        {
            ContentMigration_Admin::showError("Page already migrated.");
        }
        if(@$responses['status'] === "Update")
        {
          ContentMigration_Admin::updateSccess("Migrated Page Updated.");
        }
        else
        {
            ContentMigration_Admin::showSccess("Content Moved For Pages SuccessFully.");
        }
    }
    else
    {
        ContentMigration_Admin::showError("No Pages Selected For Migration.");
    }
}
?>
<div class="col-md-12">
  <div class="row layout">
  <div class="col-lg-2 col-md-3 col-sm-12 pl-0 left-hdg">
    <h5>Page Listing</h5>
  </div>
<div class="col-lg-10 col-md-9 right-inpt ">
<div class="form-group"> <!-- Date input -->
<form method="get" action="" class="float-right">
<ul class="row">
  <li class="mb-2 mr-2">
  <input type="hidden"  name ="page" value="content-migration">
    <input class="form-control" required="required" id="startdate" name="startdate" value="<?php echo $_GET['startdate']; ?>" placeholder="MM/DD/YYY" type="date">
  </li>
  <li class=" mb-2 mr-2">
    <input class="form-control" required="required" id="enddate" name="enddate" value="<?php echo $_GET['enddate']; ?>" placeholder="MM/DD/YYY" type="date"/>
  </li>

  <li class=" mb-2 p-0 text-center">
    <input type="submit" name="apply" value="Apply Filter" class="btn btn-primary btn-sm">
  </li>
</ul>
</form>
</div>
</div>
<?php
if (isset($_GET['apply']))
{
    if (isset($_GET['startdate']) && isset($_GET['enddate']))
    {
        $data = ContentMigration_Admin::getingRecords($_GET); //getting updated pages
       // ContentMigration_Admin::updateExistingPageContent($_GET);
    }
    else
    {
        ContentMigration_Admin::showError("Select start and end date to filter records.");
        $data = ContentMigration_Admin::getingRecords(); //getting updated pages
        
    }

}
else
{
    $data = ContentMigration_Admin::getingRecords(); //getting updated pages
    
}
$records = $data;
if (!$records['results'] && count($records['results']) <= 0)
{
    ContentMigration_Admin::showError("There is no recently updated pages please update first.");
}
else
{
?>
  <form name="formSend" method="POST" class="w-100">
  <div class="table-responsive">
<table class="table table-striped">
  <tr>
    <th></th>
    <th>Title</th>
    <th>Author</th>
    <th>Modified Date</th>
    <th>Status</th>
    <th>Preview</th>
  </tr>
  <?php
	//echo "<pre>"; print_r($records['results']);
    foreach ($records['results'] as $key => $item)
    {
?>
     <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $item->ID; ?>"></td>
        <td><?php echo $item->post_title; ?></td>
        <td><?php echo get_the_author_meta('display_name', $item->post_author) ?></td>
        <td>
          <?php $orgDate = $item->post_modified;
            $newDate = date("d/m/Y", strtotime($orgDate));
            echo $newDate; 
          ?>
        </td>
        <td><?php echo $item->post_status; ?></td>
        <td><a target="blank" href="<?php echo $item->guid; ?>"><input type="button" class="btn btn-primary btn-sm" value="Preview"></a></td>
    </tr>
    <?php
    }
?>
</table>
</div>
<input type="submit" name="submit" value="Migrate" class="btn btn-primary">
</form>
  <?php
}
?>
 <?php
$totalPage = $records['totalPage'];
$page = $records['page'];
if ($totalPage > 1)
{
    $customPagHTML = '<div class="Pageinate"><nav>
    <ul class="pagination"><li>' . paginate_links(array(
        'base' => add_query_arg('cpage', '%#%') ,
        'format' => '',
        'prev_text' => __('&laquo;') ,
        'next_text' => __('&raquo;') ,
        'total' => $totalPage,
        'current' => $page,
    )) . '</li>
    </ul>
    </nav></div>';
    echo $customPagHTML;
}
?>
