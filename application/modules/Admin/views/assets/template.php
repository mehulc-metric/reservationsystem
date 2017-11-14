<?php
/*
  Author : Niral Patel
  Desc   : Header Part
  Input  : Bunch of Array
  Output : All CSS and JS
  Date   : 12-6-2017
 */
if (empty($head)) {
    $head = array();
}
echo Modules::run (ADMIN_SITE . '/Layout/header', $head);
?>


    <?php
    /*
      Author : Niral Patel
      Desc   : Call Side bar Header
      Input  :
      Output :
      Date   : 12-6-2017
     */
    echo Modules::run (ADMIN_SITE . '/Layout/sidebar');
    ?>

    <?php
    /*
      Author : Niral Patel
      Desc   : Call Page Content Area
      Input  : View Page Name and Bunch of array
      Output : View Page
      Date   : 12-6-2017
     */
    if (!empty($main_content)) {
        $this->load->view ($main_content);
    }
    ?>


<!--</div>  This div not started at top any reason need to discuss-->

<?php
/*
  Author : Niral Patel
  Desc   : Call Footer Area
  Input  :
  Output : Footer Area( Menu, Content)
  Date   : 12-6-2017
 */
echo Modules::run (ADMIN_SITE . '/Layout/footer');
?>
