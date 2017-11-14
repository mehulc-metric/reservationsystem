<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Timeshedule extends CI_Controller {

    function __construct() {
        parent::__construct();
        if(checkAdminPermission('Timeshedule','view') == false)
        {
               redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = ucfirst($this->router->fetch_class ());
     
    }

    /*
      @Author : Niral Patel
      @Desc   : Add Timeshedule 
      @Input  :
      @Output :
      @Date   : 12-6-2017
     */

    public function index() {
        
        //check weekly total slot
        $startDate = (date('D') == 'Sun' ? date('Y-m-d') : date('Y-m-d', strtotime('last sunday')));
        //get total slot
        $match = array('week_start_date'=>$startDate);
        $totalWeekSlot = $this->common_model->get_records(WEEKLT_TOTAL_SLOT,array('total_slot_id','total_slot'), '', '',$match, '', '', '', '','', '', '', '', '', '');


        //get no_of_slot_per_hour
        $field = array('*');
        $match = array('config_key' => 'no_of_slot_per_hour');
        $slotPerHour = $this->common_model->get_records(CONFIG_TABLE,$field, '', '',$match);

        $total_slot = (!empty($totalWeekSlot[0]['total_slot']))?$totalWeekSlot[0]['total_slot']:$slotPerHour[0]['value'];
        $data['slot_duration'] = 60/$total_slot;
        $data['main_content']  = $this->type . '/' . $this->viewname . '/add';
        $data['footerJs'][0]   = base_url('uploads/custom/js/timeshedule/timeshedule.js');
        $this->load->view($this->type.'/assets/timeshedule_template',$data);
    }
    /*
      @Author : Niral Patel
      @Desc   : Add Timeshedule 
      @Input  :
      @Output :
      @Date   : 14-6-2017
     */

    public function getEvents() {
       $postData = $this->input->post ();
       //get hourly weekly hours
       $wherestring = 'date >= "'.$postData['start'].'" and date <= "'.$postData['end'].'"';
       $fields = array('ht.hourly_ts_id,ht.weekly_ts_id,ht.weekly_ts_id,ht.date,ht.start_time,ht.end_time,ht.is_reservable,count(us.hourly_ts_id) as totaluser');
       $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
       $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT.' as ht',$fields, $joinTables, 'left','', '', '', '', '','', 'ht.hourly_ts_id', $wherestring, '', '', '');
     
       $data =array();
       if (!empty($hourlyData)) {
            foreach ($hourlyData as $row) {
                $isRes = ($row['is_reservable'] == 1)?'Open':'Close';
                $data[] = array('hourly_ts_id' => $row['hourly_ts_id'],
                    'date'          => $row['date'],
                    'title'         => date('h:i a',strtotime($row['start_time'])).' - '.date('h:i a',strtotime($row['end_time'])),
                    'weekly_ts_id'  => $row['weekly_ts_id'],
					'start_date'	 => $row['date'].' '.$row['start_time'],
                    'start'         => $row['date'].'T'.$row['start_time'],
                    'end'           =>($row['end_time'] == '00:00:00')? $row['date'].'T'.'23:59:59':$row['date'].'T'.$row['end_time'],
                    'color'         => ($row['is_reservable'] == 1)?'#008000':'#ff0000',
                    'description'   => 'From '.date('h:i a',strtotime($row['start_time'])).' to '.date('h:i a',strtotime($row['end_time'])).' '.$isRes,
                    'totaluser'     => $row['totaluser'],
                    'url' => base_url(ADMIN_SITE.'/Timeshedule/editRecord/' . $row['hourly_ts_id']));
            }
        }
        echo json_encode($data);
    }
    function getTimeSlot()
    {
        $postData = $this->input->post ();
        //get total slot as per week 
         $match = array('week_start_date'=>dateformat($postData['week_start_date']));
         $totalWeekSlot = $this->common_model->get_records(WEEKLT_TOTAL_SLOT,array('total_slot_id','total_slot'), '', '',$match, '', '', '', '','', '', '', '', '', '');

        //get no_of_slot_per_hour
        $field = array('*');
        $match = array('config_key' => 'no_of_slot_per_hour');
        $slotPerHour = $this->common_model->get_records(CONFIG_TABLE,$field, '', '',$match);

        $total_slot = (!empty($totalWeekSlot[0]['total_slot']))?$totalWeekSlot[0]['total_slot']:$slotPerHour[0]['value'];
        echo $slot_duration = 60/$total_slot;
    }
    /*
      @Author : Niral Patel
      @Desc   : Add Timeshedule 
      @Input  :
      @Output :
      @Date   : 14-6-2017
     */

    public function editRecord($id) {
       $data=array();
        //check weekly hours
        $match = array('hourly_ts_id'=>$id);
        $data['hourlyData'] = $this->common_model->get_records(HOURLY_TIMESLOT,array('hourly_ts_id','weekly_ts_id','date','end_time','start_time','is_reservable'), '', '',$match, '', '', '', '','', '', '', '', '', '');
        $this->load->view($this->type . '/' . $this->viewname . '/ajax_add',$data);
    }
   /*
      @Author : Niral Patel
      @Desc   : Add Timeshedule 
      @Input  :
      @Output :
      @Date   : 12-6-2017
     */
     public function insert() {
        
      $postData = $this->input->post (); 
      $totalDuration = $postData['total_duration'];
      $totalMinute   = $postData['total_minute'];
      //check weekly hours and insert if not exist
      $match = array('week_start_date'=>dateformat($postData['week_start_date']));
      $weeklyTotalData = $this->common_model->get_records(WEEKLT_TOTAL_SLOT,array('total_slot_id'), '', '',$match, '', '', '', '','', '', '', '', '', '1');

      if(empty($weeklyTotalData))
      {
            //get no_of_slot_per_hour
            $field = array('*');
            $match = array('config_key' => 'no_of_slot_per_hour');
            $slot = $this->common_model->get_records(CONFIG_TABLE,$field, '', '',$match);
            $weeklyData = array(
                'total_slot'        => !empty($slot[0]['value']) ? $slot[0]['value'] : '',
                'week_start_date'   => dateformat($postData['week_start_date']),
                'week_end_date'     => date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $postData['week_end_date']) ) )),
                'created_at'        => date("Y-m-d H:i:s")
            );

             $weeklyTotalId = $this->common_model->insert(WEEKLT_TOTAL_SLOT, $weeklyData);
       }
       //Insert weekly and hourly 
       $starttime = explode(':',$postData['start_time']);
       $endtime   = explode(':',$postData['end_time']);
       $prevhour = '';
       $totalSlot = (($totalMinute/$totalDuration) == 0)?1:($totalMinute/$totalDuration);
       for($i=1;$i<=$totalSlot;$i++)
       {
            $stTime =(empty($etTime))?$postData['start_time']:$etTime;
            $etTime = date('H:i:s',strtotime("+".$totalDuration." minutes", strtotime($stTime)));

            $startHour = explode(':',$stTime);
           
            if($startHour[0] != $prevhour)
            {  
               if($startHour[0] == 00)
               {
                    $startTime = '00:00';
                    $endTime = '01:00';
               }
               else
               {
                    $startTime = $startHour[0].':00';
                    $endTime =  date('H:i', strtotime($startTime.'+1 hour'));
               }
                //check weekly hours
               $match = array('date'=>dateformat($postData['date']),'start_time'=>$startTime,'end_time'=>$endTime);
               $weeklyData = $this->common_model->get_records(WEEKLY_TIMESLOT,array('weekly_ts_id'), '', '',$match, '', '', '', '','', '', '', '', '', '');
               
                if(!empty($weeklyData))
                {
                    $weeklyId = $weeklyData[0]['weekly_ts_id'];
                }
                else //insert weekly slot
                {
                      $weeklyData = array(
                          'date'       => dateformat($postData['date']),
                          'start_time' => $startTime,
                          'is_open'    => $postData['slot_type'],
                          'end_time'   => $endTime,
                          'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                          'created_at' => date("Y-m-d H:i:s")
                      );
                      $weeklyId = $this->common_model->insert(WEEKLY_TIMESLOT, $weeklyData);
                }
            }
            
            //get total exist or not
            $match = array('date'=>dateformat($postData['date']),'start_time'=>$stTime,'end_time'=>$etTime);
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
            $hourlyCount = $this->common_model->get_records(HOURLY_TIMESLOT.' as ht',array('ht.weekly_ts_id,ht.hourly_ts_id,count(us.hourly_ts_id) as totalhour'), $joinTables, 'left',$match, '', '', '', '','', '', '', '', '', '');
             
             //insert
             if(empty($hourlyCount[0]['hourly_ts_id']) && $hourlyCount[0]['totalhour'] == 0)
             {
                  $hourlyData[] = array(
                          'weekly_ts_id'  => $weeklyId,
                          'date'          => dateformat($postData['date']),
                          'start_time'    => $stTime,
                          'end_time'      => $etTime,
                          'is_reservable' => $postData['slot_type'],
                          'created_by'    => $this->session->userdata['reservation_admin_session']['admin_id'],
                          'created_at'    => date("Y-m-d H:i:s")
                  );
                  
              }
              else //update
              {
                  if($hourlyCount[0]['totalhour'] == 0)
                  {
                      $hourlyUpdateData[] = array(
                              'hourly_ts_id'  => $hourlyCount[0]['hourly_ts_id'],
                              'is_reservable' => $postData['slot_type'],
                              'modified_by'    => $this->session->userdata['reservation_admin_session']['admin_id'],
                              'modified_at'    => date("Y-m-d H:i:s")
                      );
                  }
              }
              $prevhour = $startHour[0];
        }
        
       //update batch
       if(!empty($hourlyUpdateData))
        {
            $this->common_model->update_batch(HOURLY_TIMESLOT, $hourlyUpdateData,'hourly_ts_id');
        }
        //insert hourly slot
        if(!empty($hourlyData))
        {
            $hourlyId = $this->common_model->insert_batch(HOURLY_TIMESLOT, $hourlyData);    
        }
       echo $msg = '1';
    }
    public function inserthour() {
        
       $postData = $this->input->post ();
       $starttime = explode(':',$postData['start_time']);

       if($starttime[0] == 00)
       {
            $startTime = '00:00';
            $endTime = '01:00';
       }
       else
       {
            $startTime = $starttime[0].':00';
            $endTime =  date('H:i', strtotime($startTime.'+1 hour'));
       }
        //check weekly hours
       $match = array('week_start_date'=>dateformat($postData['week_start_date']));
       $weeklyTotalData = $this->common_model->get_records(WEEKLT_TOTAL_SLOT,array('total_slot_id'), '', '',$match, '', '', '', '','', '', '', '', '', '1');

       if(empty($weeklyTotalData))
       {
            //get no_of_slot_per_hour
            $field = array('*');
            $match = array('config_key' => 'no_of_slot_per_hour');
            $slot = $this->common_model->get_records(CONFIG_TABLE,$field, '', '',$match);
            $weeklyData = array(
                'total_slot'        => !empty($slot[0]['value']) ? $slot[0]['value'] : '',
                'week_start_date'   => dateformat($postData['week_start_date']),
                'week_end_date'     => date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $postData['week_end_date']) ) )),
                'created_at'        => date("Y-m-d H:i:s")
            );

            $weeklyTotalId = $this->common_model->insert(WEEKLT_TOTAL_SLOT, $weeklyData);
       }
       //check weekly hours
       $match = array('date'=>dateformat($postData['date']),'start_time'=>$startTime,'end_time'=>$endTime);
       $weeklyData = $this->common_model->get_records(WEEKLY_TIMESLOT,array('weekly_ts_id'), '', '',$match, '', '', '', '','', '', '', '', '', '');
      if(!empty($weeklyData))
      {
          $weeklyId = $weeklyData[0]['weekly_ts_id'];
      }
      else //insert weekly slot
      {
            $weeklyData = array(
                'date'       => dateformat($postData['date']),
                'start_time' => $startTime,
                'end_time'   => $endTime,
                'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                'created_at' => date("Y-m-d H:i:s")
            );
            $weeklyId = $this->common_model->insert(WEEKLY_TIMESLOT, $weeklyData);
      }
       //get total exist or not
       $match = array('date'=>dateformat($postData['date']),'start_time'=>$postData['start_time'],'end_time'=>$postData['end_time']);
       $hourlyCount = $this->common_model->get_records(HOURLY_TIMESLOT,array('weekly_ts_id'), '', '',$match, '', '', '', '','', '', '', '', '', '1');
       if(empty($hourlyCount))
       {
           $hourlyData = array(
                'weekly_ts_id'  => $weeklyId,
                'date'          => dateformat($postData['date']),
                'start_time'    => $postData['start_time'],
                'end_time'      => $postData['end_time'],
                'is_reservable' => $postData['slot_type'],
                'created_by'    => $this->session->userdata['reservation_admin_session']['admin_id'],
                'created_at'    => date("Y-m-d H:i:s")
            );
           $hourlyId = $this->common_model->insert(HOURLY_TIMESLOT, $hourlyData);
        }
       // Insert query
        if (isset($hourlyId)) {
            echo $msg = '1';
            
        } else {
            // error
            echo $msg = '0' ;
        }
    }
   /*
      @Author : Niral Patel
      @Desc   : Update Timeshedule 
      @Input  :
      @Output :
      @Date   : 14-6-2017
     */
      public function update() {
         $postData = $this->input->post ();
          $hourlyData = array(
            'is_reservable' => $postData['slot_type'],
            'modified_by'    => $this->session->userdata['reservation_admin_session']['admin_id'],
            'modified_at'    => date("Y-m-d H:i:s")
        );
       
       if ($this->common_model->update(HOURLY_TIMESLOT, $hourlyData,array('hourly_ts_id'=>$postData['hourly_ts_id']))) {
            echo $msg = '1';
            
        } else {
            // error
            echo $msg = '0' ;//$this->lang->
        }
      }
      /*
      @Author : Niral Patel
      @Desc   : Delete Timeshedule 
      @Input  :
      @Output :
      @Date   : 15-6-2017
     */
      function deleteEvent()
      {
        $postData = $this->input->post ();
        if ($this->common_model->delete(HOURLY_TIMESLOT,array('hourly_ts_id'=>$postData['id']))) {
            echo $msg = '1';
            
        } else {
            // error
            echo $msg = '0' ;
        }
      }
}
