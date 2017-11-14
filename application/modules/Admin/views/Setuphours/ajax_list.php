<?php
/*
  @Description:Customers list
  @Author: Mehul patel
  @Date: 12-5-2017
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
$admin_session = $this->session->userdata('reservation_admin_session');
$master_role_id = $this->config->item('super_admin_role_id');
$master_user_id = $this->config->item('master_user_id');
// pr($datalist); exit();
// echo "DATE : ".date("d-m-Y",strtotime($datalist[0]['date']))." ".date('H:i a',strtotime($datalist[0]['start_time']))."</br>"; exit();
?>
<?php
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="row">                                
    <div class="col-sm-12">
        <button id="prev" class="pull-left btn btn-primary" date-range="<?=!empty($daterange[0])?$daterange[0]:''?>" onclick="changeWeek('<?=!empty($daterange[0])?$daterange[0]:''?>','prev')"><?php echo lang('prev'); ?></button> 
        <button id="next" class="pull-right btn btn-primary" date-range="<?=!empty($daterange[1])?$daterange[1]:''?>" onclick="changeWeek('<?=!empty($daterange[1])?$daterange[1]:''?>','next')"><?php echo lang('next'); ?></button>
        <input type="hidden" id="currentweek" value="<?=!empty($daterange[0])?date('Y-m-d',strtotime("-1 day", strtotime($daterange[0]))):''?>">

    </div>
</div>
<div class="table-responsive">
<?php
$dates= dateRangeArray($daterange[0],$daterange[1]);
    $day1=array();
    $day2=array();
    $day3=array();
    $day4=array();
    $day5=array();
    $day6=array();
    $day7=array();
    if (!empty($openWeeklyData)) {
        foreach ($openWeeklyData as $row) {
    
       if($dates[0] == $row['date'])
       {
            $day1[]= $row;
       }
       if($dates[1] == $row['date'])
       {
            $day2[]= $row;
       }
       if($dates[2] == $row['date'])
       {
            $day3[]= $row;
       }
       if($dates[3] == $row['date'])
       {
            $day4[]= $row;
       }
       if($dates[4] == $row['date'])
       {
            $day5[]= $row;
       }
       if($dates[5] == $row['date'])
       {
            $day6[]= $row;
       }
       if($dates[6] == $row['date'])
       {
            $day7[]= $row;
       }
       }}
       $maxOpen = max(array(count($day1),count($day2),count($day3),count($day4),count($day5),count($day6),count($day7)));
    ?>
    <?php
                $closeday1=array();
                $closeday2=array();
                $closeday3=array();
                $closeday4=array();
                $closeday5=array();
                $closeday6=array();
                $closeday7=array();
                if (!empty($closeWeeklyData)) {
                    foreach ($closeWeeklyData as $row) {
                
                   if($dates[0] == $row['date'])
                   {
                        $closeday1[]= $row;
                   }
                   if($dates[1] == $row['date'])
                   {
                        $closeday2[]= $row;
                   }
                   if($dates[2] == $row['date'])
                   {
                        $closeday3[]= $row;
                   }
                   if($dates[3] == $row['date'])
                   {
                        $closeday4[]= $row;
                   }
                   if($dates[4] == $row['date'])
                   {
                        $closeday5[]= $row;
                   }
                   if($dates[5] == $row['date'])
                   {
                        $closeday6[]= $row;
                   }
                   if($dates[6] == $row['date'])
                   {
                        $closeday7[]= $row;
                   }
                   }}
                   $maxClose = max(array(count($closeday1),count($closeday2),count($closeday3),count($closeday4),count($closeday5),count($closeday6),count($closeday7)));
                ?>
    <table class="table table-bordered table-striped dataTable" id="example1" customer="grid" aria-describedby="example1_info">
        <thead>            
            <tr customer="row">
               <th>open/close</th>
                <?php if(!empty($daterange))
                {
                    
                    foreach ($dates as $date) {
                       ?>
                       <th><?=date('D',strtotime($date)).'('.$date.')'?></th>
                       <?php
                    }
                }

                ?>
                
            </tr>
        </thead>
        <tbody>
        <?php  if(empty($openWeeklyData) && empty($closeWeeklyData)){ ?>
        <tr>
                <td colspan="8"><?php echo lang('no_hours_found'); ?></td>
        </tr>
        <?php } ?>
        <?php for($i=0;$i<$maxOpen;$i++) {?>
        <tr>   
                <?php if($i==0){ ?>
                <td rowspan="<?=$maxOpen?>" valign="middle" style="vertical-align: middle;" ><strong><?php echo lang('open'); ?></strong></td>  
                <?php } ?> 
                
                <td>
                <?php
                    if(isset($day1[$i]['start_time']))
                    {
                        echo empty($day1[$i]['totaluser'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$day1[$i]['weekly_ts_id']).'">'.$day1[$i]['start_time'].'</a>':$day1[$i]['start_time'];
                    }
                 ?>
                </td>
                <td>
                <?php
                    if(isset($day2[$i]['start_time']))
                    {
                        echo empty($day2[$i]['totaluser'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$day2[$i]['weekly_ts_id']).'">'.$day2[$i]['start_time'].'</a>':$day2[$i]['start_time'];
                    }
                 ?>
                </td>
                <td>
                <?php
                    if(isset($day3[$i]['start_time']))
                    {
                        echo empty($day3[$i]['totaluser'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$day3[$i]['weekly_ts_id']).'">'.$day3[$i]['start_time'].'</a>':$day3[$i]['start_time'];
                    }
                 ?>
                </td>
                <td>
                <?php
                    if(isset($day4[$i]['start_time']))
                    {
                       echo empty($day4[$i]['totaluser'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$day4[$i]['weekly_ts_id']).'">'.$day4[$i]['start_time'].'</a>':$day4[$i]['start_time'];
                    }
                 ?>
                </td>
                <td>
                <?php
                    if(isset($day5[$i]['start_time']))
                    {
                        echo empty($day5[$i]['totaluser'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$day5[$i]['weekly_ts_id']).'">'.$day5[$i]['start_time'].'</a>':$day5[$i]['start_time'];
                    }
                 ?>
                </td>
                <td>
                <?php
                    if(isset($day6[$i]['start_time']))
                    {
                        echo empty($day6[$i]['totaluser'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$day6[$i]['weekly_ts_id']).'">'.$day6[$i]['start_time'].'</a>':$day6[$i]['start_time'];
                    }
                 ?>
                </td>
                <td>
                <?php
                    if(isset($day7[$i]['start_time']))
                    {
                        echo empty($day7[$i]['totaluser'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$day7[$i]['weekly_ts_id']).'">'.$day7[$i]['start_time'].'</a>':$day7[$i]['start_time'];
                    }
                 ?>
                </td>
                
        </tr>
        <?php } ?>
        <tr><td colspan="8" style="background-color: #cccccc; "></td></tr>
        <?php for($j=0;$j<$maxClose;$j++) {?>
        <tr>   
                <?php if($j==0){ ?>
                <td rowspan="<?=$maxClose?>" valign="middle" style="vertical-align: middle;" ><strong><?php echo lang('close'); ?></strong></td>  
                <?php } ?>
                <td><?=!empty($closeday1[$j]['start_time'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$closeday1[$j]['weekly_ts_id']).'">'.$closeday1[$j]['start_time'].'</a>':''?></td>
                <td><?=!empty($closeday2[$j]['start_time'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$closeday2[$j]['weekly_ts_id']).'">'.$closeday2[$j]['start_time'].'</a>':''?></td>
                <td><?=!empty($closeday3[$j]['start_time'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$closeday3[$j]['weekly_ts_id']).'">'.$closeday3[$j]['start_time'].'</a>':''?></td>
                <td><?=!empty($closeday4[$j]['start_time'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$closeday4[$j]['weekly_ts_id']).'">'.$closeday4[$j]['start_time'].'</a>':''?></td>
                <td><?=!empty($closeday5[$j]['start_time'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$closeday5[$j]['weekly_ts_id']).'">'.$closeday5[$j]['start_time'].'</a>':''?></td>
                <td><?=!empty($closeday6[$j]['start_time'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$closeday6[$j]['weekly_ts_id']).'">'.$closeday6[$j]['start_time'].'</a>':''?></td>
                <td><?=!empty($closeday7[$j]['start_time'])?'<a data-model="ajaxModal" data-href="'.base_url(ADMIN_SITE.'/Setuphours/editHour/'.$closeday7[$j]['weekly_ts_id']).'">'.$closeday7[$j]['start_time'].'</a>':''?></td>
                
        </tr>
        <?php } ?>
        
     


    </table>
</div>   
<script>
    var value_should_be_greater_then_start_time = "<?php echo lang('value_should_be_greater_then_start_time'); ?>";
    var value_greater_or_equal_to_current_time = "<?php echo lang('value_greater_or_equal_to_current_time'); ?>";
    
</script>


