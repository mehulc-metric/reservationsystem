<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    /*
      Author : Mehul Patel
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 06/06/2016
     */
    if (empty($head)) {
        $head = array();
    }
    echo Modules::run('Sidebar/head', $head);
    ?>
    <link rel="stylesheet" href="<?= base_url() ?>uploads/assets/frontend/css/bootstrap-formwizard.css" typet="text/css">
    <!-- Full calendar style -->
    <link rel="stylesheet" href="<?= base_url() ?>uploads/custom/css/fullcalendar/fullcalendar.min.css" typet="text/css">
    <script type="text/javascript" src="<?= base_url() ?>uploads/custom/js/fullcalendar/moment.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>uploads/custom/js/fullcalendar/fullcalendar.min.js"></script>
    <script type="text/javascript">
      var base_url = "<?= base_url() ?>";
    </script>
</head>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <?php
            /*
              Author : Mehul Patel	
              Desc   : Call Header area
              Input  : Bunch of Array
              Output : Top Side Header(Logo, Menu, Language)
              Date   : 06/06/2017
             */
            if (empty($header)) {
                $header = array();
            }
            echo Modules::run('Sidebar/header', $header);
            ?>
        </div>
    </div>
</div>
    <?php /*<div class="container-fluid-full">
        <div class="row-fluid">
        <div id="sidebar-left" class="span2">
            <div class="nav-collapse sidebar-nav">
        <?php
        if (empty($leftmenu)) {
            $leftmenu = array();
        }
        echo Modules::run('Sidebar/leftmenu', $leftmenu);
        ?>
    </div>
        </div> */ ?>
<!-- /.navbar-collapse -->
    <div id="content" class="">
    <!-- Example row of columns -->
    <?php
    /*
      Author : Mehul Patel
      Desc   : Call Page Content Area
      Input  : View Page Name and Bunch of array
      Output : View Page
      Date   : 06/06/2017
     */
    $this->load->view($main_content);
    ?>
</div>
</div>
</div>
<?php
/*
  Author : Mehul Patel
  Desc   : Call Footer Area
  Input  :
  Output : Footer Area( Menu, Content)
  Date   : 06/06/2017
 */
echo Modules::run('Sidebar/footer');
?>
<?php
/*
  @Author : Mehul Patel
  @Desc   : Used for the custom CSS initilization just pass array of the scripts with links
  @Input  :
  @Output :
  @Date   : 06/06/2017
 */
if (isset($footerJs) && count($footerJs) > 0) {
    foreach ($footerJs as $js) {
        ?>
        <script src="<?php echo $js; ?>" ></script>
        <?php
    }
}
?>
</body>
</html>
