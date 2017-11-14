<div class="col-md-12 text-center">
    <?php if(!empty($hoursSlot[0]['date'])){
        
        $date=date_create($hoursSlot[0]['date']);
        $displayDate = date_format($date,"d F y");
    }
?>
    <h3><?=lang('select_time_for')?>
      <!-- <span><?=!empty($hoursSlot[0]['date'])?displaydateformat($hoursSlot[0]['date']):''?>
      </span> -->
        <span><?=!empty($hoursSlot[0]['date'])? $displayDate:''?>
      </span>
    </h3>
    <ul class="slots">
    <?php if(!empty($hoursSlot)) {
        foreach ($hoursSlot as $row) {   

        $colorType = (
         ($row['slotcolor'] == 1) ? "green" :
          (($row['slotcolor'] == 2) ? "yellow" : "red"
         ));
/*        echo $row['date'].' '.$row['start_time'].'<br>';
        echo datetimeformat();*/
       $hourPast = ($row['date'].' '.$row['start_time'] >= datetimeformat())?'':'fc-hour-past';
        ?>
           
        <li id="hr-<?=!empty($row['weekly_ts_id'])?$row['weekly_ts_id']:''?>" class="<?=$hourPast?> hourslot <?=$colorType?>" data-color="<?=!empty($row['slotcolor'])?$row['slotcolor']:''?>" data-id="<?=!empty($row['weekly_ts_id'])?$row['weekly_ts_id']:''?>" start-time="<?=!empty($row['start_time'])?date('H:i',strtotime($row['start_time'])):''?>" end-time="<?=!empty($row['end_time'])?date('H:i',strtotime($row['end_time'])):''?>">
            <span><?=!empty($row['start_time'])?date('h:i a',strtotime($row['start_time'])):''?> -
            <?=!empty($row['end_time'])?date('h:i a',strtotime($row['end_time'])):''?></span>
        </li>


    <?php } }?>
    </ul>
    <input type="hidden" value="" id="weekly_ts_hour" name="weekly_ts_hour">
</div>