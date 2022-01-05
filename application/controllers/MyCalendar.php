<?php

/* * ***
 * Version: 1.0.0
 *
 * Description of My Calendar Controller
 *
 *
 * *** */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MyCalendar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_Model');
         
    }
    // index method 
    public function index() { 
        $data = array();
        $data['metaDescription'] = 'Calendar';
        $data['metaKeywords'] = 'Calendar';
        $data['title'] = "Calendar - Techarise";
        $data['breadcrumbs'] = array('Calendar' => '#');
        $data['events'] = $this->User_Model->fetch_all_event();
           // echo  json_encode($data);
        $this->load->view('calendar/index', $data);
    }

    // getCalendar method
    public function getCalendar() { 
            
            $data = array();
            $curentDate = date('m-d-Y', time());
                //print_r($curentDate);

            if ($this->input->post('page') !== null) {
                $malestr = str_replace("?", "", $this->input->post('page'));
                $navigation = explode('/', $malestr);
                $getYear = $navigation[1];
                $getMonth = $navigation[2];
            } else {
                $getYear = date('Y');
                $getMonth = date('m');
            }
            if ($this->input->post('year') !== null) {
                $getYear = $this->input->post('year');
            }
            if ($this->input->post('month') !== null) {
                $getMonth = $this->input->post('month'); 
            }

            $already_selected_value = $getYear;
            $earliest_year = date('Y');
            $startYear = '';
            $googleEventArr = array();
            $calendarData = array();

            $class = 'href="javascript:void(0);" data-days="{day}"';

            $startYear .= '<div class="col-md-3 col-sm-5 col-xs-7 col-md-offset-3 col-sm-offset-1"><div class="select-control"><select name="year" id="setYearVal" class="form-control">';
        foreach (range(date('Y') + 50, $earliest_year) as $x) {
            $startYear .= '<option value="' . $x . '"' . ($x == $already_selected_value ? ' selected="selected"' : '') . '>' . $x . '</option>';
        }
        $startYear .= '</select></div></div>';
        $startMonth = '<div class="col-md-3 col-sm-5 col-xs-7 col-md-offset-3 col-sm-offset-1"><div class="select-control"><select name="mont h" id="setMonthVal" class="form-control"><option value="0">Select Month</option>
            <option value="01" ' . ('01' == $getMonth ? ' selected="selected"' : '') . '>January</option>
            <option value="02" ' . ('02' == $getMonth ? ' selected="selected"' : '') . '>February</option>
            <option value="03" ' . ('03' == $getMonth ? ' selected="selected"' : '') . '>March</option>
            <option value="04" ' . ('04' == $getMonth ? ' selected="selected"' : '') . '>April</option>
            <option value="05" ' . ('05' == $getMonth ? ' selected="selected"' : '') . '>May</option>
            <option value="06" ' . ('06' == $getMonth ? ' selected="selected"' : '') . '>June</option>
            <option value="07" ' . ('07' == $getMonth ? ' selected="selected"' : '') . '>July</option>
            <option value="08" ' . ('08' == $getMonth ? ' selected="selected"' : '') . '>August</option>
            <option value="09" ' . ('09' == $getMonth ? ' selected="selected"' : '') . '>September</option>
            <option value="10" ' . ('10' == $getMonth ? ' selected="selected"' : '') . '>October</option>
            <option value="11" ' . ('11' == $getMonth ? ' selected="selected"' : '') . '>November</option>
            <option value="12" ' . ('12' == $getMonth ? ' selected="selected"' : '') . '>December</option>
    </select></div></div>';
    
        $prefs['template'] = '        

        {table_open}<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" class="calendar">{/table_open}

        {heading_row_start}<tr style="border:none;">{/heading_row_start}

        {heading_previous_cell}<th style="border:none;" class="padB"><a class="calnav" data-calvalue="{previous_url}" href="javascript:void(0);"><i class="fa fa-chevron-left fa-fw"></i></a></th>{/heading_previous_cell}
        {heading_title_cell}<th style="border:none;" colspan="{colspan}"><div class="row">' . $startMonth . '' . $startYear . '</div></th>{/heading_title_cell}
        {heading_next_cell}<th style="border:none;" class="padB"><a class="calnav" data-calvalue="{next_url}" href="javascript:void(0);"><i class="fa fa-chevron-right fa-fw"></i></a></th>{/heading_next_cell}

        {heading_row_end}</tr>{/heading_row_end}

        {week_row_start}<tr>{/week_row_start}
        {week_day_cell}<th>{week_day}</th>{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}

        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td> {/cal_cell_start}
        {cal_cell_start_today}<td>{/cal_cell_start_today}
        {cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}

        {cal_cell_content} <a ' . $class . '>{day}</a>{content}{/cal_cell_content}
        {cal_cell_content_today}<a ' . $class . '>{day}</a>{content}<div class="highlight"></div>{/cal_cell_content_today}

        {cal_cell_no_content}<span class="popUp"></span>  <a ' . $class . '>{day}</a>{/cal_cell_no_content}
        {cal_cell_no_content_today} <a ' . $class . '>{day}</a><div class="highlight"></div>{/cal_cell_no_content_today}

        {cal_cell_blank}&nbsp;{/cal_cell_blank}

        {cal_cell_other}{day}{/cal_cel_other}

        {cal_cell_end}</td>{/cal_cell_end}
        {cal_cell_end_today}</td>{/cal_cell_end_today}
        {cal_cell_end_other}</td>{/cal_cell_end_other}
        {cal_row_end}</tr>{/cal_row_end}

        {table_close}</table>{/table_close}';
        $prefs['start_day'] = 'monday';
        $prefs['day_type'] = 'short';
        $prefs['show_next_prev'] = TRUE;
        $prefs['next_prev_url'] = '?';
        $this->load->library('calendar', $prefs);
        $data['calendar'] = $this->calendar->generate($getYear, $getMonth, $calendarData, $this->uri->segment(3), $this->uri->segment(4));
        echo  $data['calendar'];
    }
    public function User()
    {
                    $data['users']= $this->User_Model->getUserdata();               
                    echo json_encode($data['users']);
    }
    public function event(){
         $events['events'] = $this->User_Model->fetch_all_event();
                  echo json_encode($events['events']);
    }

}

