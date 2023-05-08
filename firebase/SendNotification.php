<?php

/**
 * @package Custom Work
 */
/*
  Plugin Name: Send Notification
  Plugin URI: https://brahmdhamtirth.org/
  Description: Here I have done all rest api.
  Version: 1.1
  Author: LH PHP Developer
  Text Domain: Brahmdhamtirth
 */
/*
 * All API ROUTS
 */

 

 add_action('admin_menu', 'user_send_notification');
 function user_send_notification()
 {
     add_menu_page(
         'Send Notification',
         'Send Notification',
         'edit_posts',
         'user_send_notification',
         'user_send_notification_callback_function',
         'dashicons-media-spreadsheet'
 
     );
 }

function user_send_notification_callback_function()
{
    global $wpdb;

    date_default_timezone_set("Asia/Calcutta"); 
 

    if (isset($_GET['notification_msg'])) {
  
        if (isset($_GET['payment_status'])) {

 
            $condition = '';
            $having_con = '';
            if (isset($_GET['payment_status']) && !empty($_GET['payment_status']) &&  $_GET['payment_status'] != 'All') {
                $having_con = " HAVING types = '" . $_GET['payment_status'] . "' ";
            }
         
            if (isset($_GET['month_wise']) && !empty($_GET['month_wise'])) {
                $month_wise = date("Y-m", strtotime($_GET['month_wise']));
            } else {
                $month_wise = date('Y-m');
            }

           
         
            if (isset($_GET['payment_status']) && $_GET['payment_status'] == 'all') {

               

                $all_user_total = $wpdb->get_results(
                    "SELECT ID as user_id  FROM wp_users as u 
                        where user_status='0'"
                );

            }else{

                $all_user_total = $wpdb->get_results(
                    "SELECT payment.id as payment_id,user_id,user_name,payments_type,payment_file_url,invoice_number,display_name,user_email,phone,payment_date,u.ID as userid,u.payment_type,amount,transaction_id,created_at,
                    (CASE WHEN NOW() < CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) THEN 'upcoming'
                     WHEN (NOW() BETWEEN CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) AND date_add(CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) , INTERVAL 30 DAY )) AND (SELECT COUNT(id) FROM wp_user_payments WHERE u.ID = user_id AND (payment_date between CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d'))  and  date_add(CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) , INTERVAL 30 DAY))) > 0 THEN 'paid'
                     WHEN NOW() BETWEEN CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) AND date_add(CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) , INTERVAL 30 DAY) THEN 'pending'
                     WHEN NOW() > date_add(CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) , INTERVAL 30 DAY) AND  (SELECT COUNT(id) FROM wp_user_payments WHERE (payment_date between CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d'))  and  date_add(CONCAT('$month_wise-',DATE_FORMAT(u.customer_join_date, '%d')) , INTERVAL 30 DAY)) AND u.ID = user_id) <= 0 THEN 'not_paid'
                     WHEN payment.amount > 0 THEN 'paid'
                     ELSE 'not_paid'
                    END) as types
                         FROM wp_users as u
                         LEFT JOIN wp_user_payments as payment ON payment.user_id = u.ID
                        where user_status='0' and customer_join_date !='0000-00-00'   $condition  GROUP BY user_id  $having_con"
                );

            }
          

             
            foreach ($all_user_total as $val) {

                $fcm_token = get_metadata('user', $val->user_id, 'fcm_token', true);
               // echo '-----------';

             //   echo $val->user_id.'fcm- '.$fcm_token;

                $title = $_GET['title'];
                $message = $_GET['notification_msg'];
                $token = $fcm_token;

                $wpdb->insert('wp_user_notification', array(
                    'user_id' => $val->user_id,
                    'title' => $title,
                    'message' =>$message,
                    'status' => 0, 
                    'created_at' => date('Y-m-d H:i:s')
                ));

                if(isset($fcm_token) && !empty($fcm_token)){ 
                    
                  //  send_android_notification($title, $message, $token, $redirection = "", $group_id = "", $group_name = "", $restaturent_id = "", $notification_id = "", $show_action_button = "no");

                }

                $successMsg = "Notification send successfully to all the users";
            }
           

            header("Location: https://brahmdhamtirth.org/wp-admin/admin.php?page=user_send_notification&msg=" . $successMsg);
            die();
        } 

       
    }

    $token ='dmQ_kYSSQ7-ZznImZ90QDL:APA91bHxc4FR0LTcU6CSynrb6JWKxNZqKXffImIe4IR6Aes9K2rK_rWJGK16wjlhBdG4ZEshiArcaZRn17xLHhl-3pbaYkxhmTAEtUsj2GKw3ohQcxHfMDOnOPEHKQLZsuPtA4Ez3jQW';
   // send_android_notification('test19-april', 'msg-19', $token, $redirection = "", $group_id = "", $group_name = "", $restaturent_id = "", $notification_id = "", $show_action_button = "no")

 
   

?>


 
 
 

    <div class="page-content wrap">
        <link rel="stylesheet" href="https://brahmdhamtirth.org/wp-content/themes/foodup/css/style_admin.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" />

        <!-- BEGIN PAGE CONTAINER -->
        <div class="NotificationHead">
            <!-- BEGIN PAGE HEAD -->
            <div class="page-head">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <h1>Notification To User</h1>
                  
                </div>
                <hr>
            </div>
        </div>

        <div class="NotificationHeadForm">
            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_0">
                                    <div class="portlet light">
                                        <div class="portlet-title">

                                        </div>

                                        <div class="portlet-body form">
                                            <?php
                                            if (isset($_GET['msg']) && !empty($_GET['msg'])) {
                                                $display = 'block';
                                            } else {
                                                $display = 'none';
                                            }
                                            ?>
                                            <div class="alert alert-success alert-dismissable" style="display:<?php echo $display; ?>;" id="successMsgDiv">
                                                <?php echo $_GET['msg']; ?>
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            </div>
                                            <div class="alert alert-danger alert-dismissable" style="display:none;" id="errorMsgDiv">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            </div>

                                            <div class="form-body">
                                                <!------------ Notification Message Form ----------->

                                                <form method="get" action="<?php echo admin_url('admin.php?page=user_send_notification'); ?>">

                                                    <input type="hidden" id="user-search-input" name="page" value="user_send_notification">
                                                    <input type="hidden" id="user-search-input" name="s" value="">

                                                    <div class="row">
                                                        <!-- <div class="form-group" style="padding-bottom: 30px;">
															<label class="col-sm-2">
																Send To
															</label>
															<div class="col-sm-8">
																<div class="col-sm-6">
																	Selected User <input name="send_type" checked="" type="radio" value="0" class="checkstatus">
																</div>
																<div class="col-sm-6">
																	All User <input name="send_type" type="radio" value="1" class="checkstatus">
																</div>
															</div>
														</div> -->
                                                        <div class="col-md-12">
                                                            <!-- <div class="form-group sluser" style="padding-bottom: 40px;">
																<label class="col-md-2">
																	Select User
																</label>
																<div class="col-md-8">											

																	<?php
                                                                    $all_uses = $wpdb->get_results(
                                                                        "SELECT ID,display_name,user_email " .
                                                                            "FROM $wpdb->users AS users " .
                                                                            "WHERE user_status = '0'"
                                                                    );
                                                                    ?>
																	<select multiple="multiple" id="rider_id" name="rider_id[]" data-style="white" class="form-control form-filter input-sm form-white " tabindex="-1" title="" style="width:100%">
																		<option value="">Select User</option>
																		<?php
                                                                        foreach ($all_uses as $userVal) {
                                                                        ?>
																	<option <?php echo (isset($_GET['user_emails']) && !empty($_GET['user_emails']) && $_GET['user_emails'] == $userVal->ID ? 'selected' : ''); ?> value="<?php echo $userVal->ID; ?>">
																		<?php echo $userVal->display_name . ' - (' . $userVal->user_email . ')'; ?></option>
																<?php } ?>

																	</select>
																</div>
															</div> -->
                                                            <div class="row">
                                                                <div class="col-md-6">                   
                                                                    <div class="form-group">
                                                                        <label>
                                                                            Payment Status
                                                                        </label>
                                                                        <div>
                                                                            <select name="payment_status" class="payment_status" style="width:100%;max-width:100%;min-height: 34px;">
                                                                                <option value="">Select Payment Type</option>
                                                                                <option value="all">All</option>
                                                                                <option value="pending">Pending Payment</option>
                                                                                <option value="paid">Success Payment</option>
                                                                                <option value="not_paid">Failed Payment</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>                
                                                                <div class="col-md-6">            
                                                                    <div class="form-group">
                                                                        <label >
                                                                            Title
                                                                        </label>
                                                                        <div>
                                                                            <input type="text" class="form-control validate[required]" placeholder="Title" name="title">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="row selectmonth">
                                                                          
                                                                <div class="col-md-6">            
                                                                    <div class="form-group">
                                                                        <label >
                                                                            Select Month
                                                                        </label>
                                                                        <div>
                                                                        <input  placeholder="Month Wise" class="form-control" value="<?php echo $_GET['month_wise'];  ?>" name="month_wise" type="text" id="datepicker"  >
                                                                         </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label>Message</label>
                                                                <div>
                                                                    <textarea style="width:100%" cols="70" rows="3" name="notification_msg" id="notification_msg" class="form-cotrol validate[required]" placeholder="Notification message"></textarea>
                                                                </div>
                                                            </div>

                                                            
                                                            <div class="portlet-body  form form-group">
                                                                
                                                                <div class="actions btn-set text-right">
                                                                    <input type="submit" name="riderSubmit" id="riderSubmit" class="btn btn-primary green-haze btn-circle" value="Send Notification">
                                                                </div>
                                                            </div>
                                                            <div style="clear:both"></div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div style="clear:both"></div>
                                            <div style="clear:both"></div>
                                            <div style="clear:both"></div>

                                            </form>

                                            <script type="text/javascript">


                                                $(document).ready(function() {
                                                        $("#datepicker").datepicker({
                                                            dateFormat: 'MM yy',
                                                            changeMonth: true,
                                                            changeYear: true,
                                                            showButtonPanel: true,
                                                            onClose: function(dateText, inst) {
                                                                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                                                                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                                                $(this).val($.datepicker.formatDate('MM-yy', new Date(year, month, 1)));
                                                            }
                                                        });
                                                        //Chosen
                                                        $(".user_emails").select2();
                                                    });

                                                $(document).ready(function() {

                                                    $('.payment_status').change(function() {
                                                        var selectedValue = $(this).val();
                                                        if(selectedValue =='all'){
                                                            $(".selectmonth").hide();
                                                            $('#datepicker').hide();
                                                        }else{
                                                            $(".selectmonth").show();
                                                            $('#datepicker').show();
                                                        }
                                                        });


                                                    $('.group-checkable').toggle(function() {
                                                        $('.checkbox1').attr('checked', 'checked');
                                                        $.uniform.update();
                                                    }, function() {
                                                        $('.checkbox1').removeAttr('checked');
                                                        $('.group-checkable').removeAttr('checked');
                                                        $.uniform.update();
                                                    })
                                                })

                                                $(document).ready(function() {
                                                    $("#driver_id").select2();
                                                    $("#rider_id").select2();
                                                });
                                                $(document).ready(function() {
                                                    $('.checkstatus').click(function() {

                                                        if ($(this).val() == '0') {
                                                            $('.sluser').show();
                                                        } else {
                                                            $('.sluser').hide();
                                                        }
                                                    })
                                                });
                                                $('#riderSubmit').click(function(e) {
                                                    var data = $('#rider_id').val();
                                                    var notification_msg = $('#notification_msg').val();
                                                    if (notification_msg == '') {
                                                        $('#notification_msg').css('border', '1px solid red');
                                                    }
                                                    if (data == null && $('.checkstatus:checked', '#notifiy-frm').val() == '0') {

                                                        $('.select2-choices').css('border', '1px solid red');
                                                        e.preventDefault();
                                                    } else {
                                                        if (notification_msg == '') {
                                                            $('#notification_msg').css('border', '1px solid red');
                                                            e.preventDefault();
                                                        } else {
                                                            $('.select2-choices').css('border', '1px solid #e5e5e5');
                                                            $('#notification_msg').css('border', '1px solid #e5e5e5');
                                                        }

                                                    }
                                                })
                                            </script>

                                            <style>
                                                .select2-highlighted {
                                                    background: #000 !important;
                                                    color: #fff !important;
                                                    /*padding: 10px;*/
                                                }

                                                td:first-child {
                                                    display: none;
                                                }

                                                th:first-child {
                                                    display: none;
                                                }
                                                .ui-datepicker-calendar {
                                                    display: none;
                                                    }
                                            </style>




                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<?php

}


add_action('restrict_manage_users', 'filter_by_phone_no');
function filter_by_phone_no($which)
{
	$st = '';
	$select = '';
	// template for filtering


	$phone_no_srch =  (isset($_GET['phone_no_top']) && !empty($_GET['phone_no_top']) ? $_GET['phone_no_top'] : '');
	$joining_date_srch =  (isset($_GET['joining_date_top']) && !empty($_GET['joining_date_top']) ? $_GET['joining_date_top'] : '');
	$f_name_srch =  (isset($_GET['f_name_top']) && !empty($_GET['f_name_top']) ? $_GET['f_name_top'] : '');
	$dob_srch =  (isset($_GET['dob_top']) && !empty($_GET['dob_top']) ? $_GET['dob_top'] : '');
	$occurrence_city_srch =  (isset($_GET['occurrence_city_top']) && !empty($_GET['occurrence_city_top']) ? $_GET['occurrence_city_top'] : '');
	$village_srch =  (isset($_GET['village_top']) && !empty($_GET['village_top']) ? $_GET['village_top'] : '');
	$tehsil_srch =  (isset($_GET['tehsil_top']) && !empty($_GET['tehsil_top']) ? $_GET['tehsil_top'] : '');
	$city_srch =  (isset($_GET['city_top']) && !empty($_GET['city_top']) ? $_GET['city_top'] : '');
	$state_srch =  (isset($_GET['state_top']) && !empty($_GET['state_top']) ? $_GET['state_top'] : '');
	$country_srch =  (isset($_GET['country_top']) && !empty($_GET['country_top']) ? $_GET['country_top'] : '');
	$payment_status_srch =  (isset($_GET['payment_status_top']) && !empty($_GET['payment_status_top']) ? $_GET['payment_status_top'] : '');





	$phone_st = '<input placeholder="Phone No" value="' . $phone_no_srch . '" type="text" name="phone_no_%s" style="float:none;margin-left:10px;">';
	$joing_date_st = '<input placeholder="Joining Date" value="' . $joining_date_srch . '" type="text" name=joining_date_%s" style="float:none;margin-left:10px;">';
	$f_name_st = '<input placeholder="Father Name" value="' . $f_name_srch . '" type="text" name="f_name_%s" style="float:none;margin-left:10px;">';
	$dob_st = '<input placeholder="DOB" value="' . $dob_srch . '" type="text" name="dob_%s" style="float:none;margin-left:10px;">';
	$occurrence_city_st = '<input placeholder="Occurrence City" value="' . $occurrence_city_srch . '" type="text" name="occurrence_city_%s" style="float:none;margin-left:10px;">';
	$village_st = '<input placeholder="Village" value="' . $village_srch . '" type="text" name="village_%s" style="float:none;margin-left:10px;">';
	$tehsil_st = '<input placeholder="Tehsil" value="' . $tehsil_srch . '" type="text" name="tehsil_%s" style="float:none;margin-left:10px;">';
	$city_st = '<input placeholder="City" value="' . $city_srch . '" type="text" name="city_%s" style="float:none;margin-left:10px;">';
	$state_st = '<input placeholder="State" value="' . $state_srch . '" type="text" name="state_%s" style="float:none;margin-left:10px;">';
	$country_st = '<input placeholder="Country" value="' . $country_srch . '" type="text" name="country_%s" style="float:none;margin-left:10px;">';
	$st_payment_status = '<select name="payment_status_%s" style="float:none;margin-left:10px;">
	<option value="All">%sAll Payment</option>%s</select>';



	// generate options
	$options_payment = '<option value="pending_payment">Pending Payment</option>
    <option value="success_payment">Success Payment</option>
	<option value="failed_payment">Failed Payment</option>';
	$options = '';

	// combine template and options
	$phone_no_sec = sprintf($phone_st, $which, '', $options);
	$joing_date_sec = sprintf($joing_date_st, $which, '', $options);
	$f_name_sec = sprintf($f_name_st, $which, '', $options);
	$dob_sec = sprintf($dob_st, $which, '', $options);
	$occurrence_city_sec = sprintf($occurrence_city_st, $which, '', $options);
	$village_sec = sprintf($village_st, $which, '', $options);
	$tehsil_sec = sprintf($tehsil_st, $which, '', $options);
	$city_sec = sprintf($city_st, $which, '', $options);
	$state_sec = sprintf($state_st, $which, '', $options);
	$country_sec = sprintf($country_st, $which, '', $options);
	$payment_status_sec = sprintf($st_payment_status, $which, '', $options_payment);



	// output <select> and submit button
	echo $phone_no_sec;
	echo $joing_date_sec;
	echo $f_name_sec;
	echo $dob_sec;
	echo '<br>';
	echo '<br>';
	echo $occurrence_city_sec;
	echo $village_sec;
	echo $tehsil_sec;
	echo $city_sec;
	echo $state_sec;
	echo $country_sec;
	echo '<br>';
	echo '<br>';
	echo $payment_status_sec;
	submit_button(__('Filter'), null, $which, false);
}

add_filter('pre_get_users', 'filter_users_by_phone_no_section');

function filter_users_by_phone_no_section($query)
{
	global $wpdb;
	global $pagenow;
	if (is_admin() && 'users.php' == $pagenow) {
		$phone_no_top = $_GET['phone_no_top'] ? $_GET['phone_no_top'] : null;
		$joining_date = $_GET['joining_date_top'] ? $_GET['joining_date_top'] : null;
		$f_name = $_GET['f_name_top'] ? $_GET['f_name_top'] : null;
		$dob = $_GET['dob_top'] ? $_GET['dob_top'] : null;
		$occurrence_city = $_GET['occurrence_city_top'] ? $_GET['occurrence_city_top'] : null;
		$village = $_GET['village_top'] ? $_GET['village_top'] : null;
		$tehsil = $_GET['tehsil_top'] ? $_GET['tehsil_top'] : null;
		$city = $_GET['city_top'] ? $_GET['city_top'] : null;
		$state = $_GET['state_top'] ? $_GET['state_top'] : null;
		$country = $_GET['country_top'] ? $_GET['country_top'] : null;
		$payment_status = $_GET['payment_status_top'] ? $_GET['payment_status_top'] : null;
		// $bottom = $_GET['membership_status_bottom'] ? $_GET['membership_status_bottom'] : null;
		if (!empty($phone_no_top) or !empty($joining_date) or !empty($f_name) or !empty($dob) or !empty($occurrence_city)  or !empty($village)  or !empty($tehsil) or !empty($city)  or !empty($state) or !empty($country)) {
			// $section = !empty($top) ? $top : $bottom;

			// change the meta query based on which option was chosen

			$fliter_array = array(

				array(
					'key' => 'phone_no',
					'value' => $phone_no_top,
					'compare' => 'LIKE'
				),
				array(
					'key' => 'joining_date',
					'value' => $joining_date,
					'compare' => '='
				),
				array(
					'key' => 'f_name',
					'value' => $f_name,
					'compare' => 'LIKE'
				),
				array(
					'key' => 'dob',
					'value' => $dob,
					'compare' => '='
				),
				array(
					'key' => 'occurrence_city',
					'value' => $occurrence_city,
					'compare' => 'LIKE'
				),
				array(
					'key' => 'village',
					'value' => $village,
					'compare' => 'LIKE'
				),
				array(
					'key' => 'tehsil',
					'value' => $tehsil,
					'compare' => 'LIKE'
				),
				array(
					'key' => 'city',
					'value' => $city,
					'compare' => 'LIKE' 
				),
			);

			$aarayFilter = array();
			foreach ($fliter_array as $val) {

				if (isset($val['value']) && !empty($val['value'])) {

					$aarayFilter[] = array(
						'key' => $val['key'],
						'value' =>  $val['value'],
						'compare' => '='
					);
				}
			}

			//	echo '<pre>';
			//print_r( $aarayFilter);die;



			$meta_query = array(
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'phone_no',
						'value' => $phone_no_top,
						'compare' => '='
					),
					array(
						'key' => 'joining_date',
						'value' => $joining_date,
						'compare' => 'LIKE'
					),
					array(
						'key' => 'f_name',
						'value' => $f_name,
						'compare' => '='
					),
					array(
						'key' => 'dob',
						'value' => $dob,
						'compare' => '='
					),
					array(
						'key' => 'occurrence_city',
						'value' => $occurrence_city,
						'compare' => '='
					),
					array(
						'key' => 'village',
						'value' => $village,
						'compare' => '='
					),
					array(
						'key' => 'tehsil',
						'value' => $tehsil,
						'compare' => '='
					),
					array(
						'key' => 'city',
						'value' => $city,
						'compare' => '='
					),
				)
			);


			$meta = array();

			if (isset($phone_no_top) && !empty($phone_no_top)) {
				// restrict
				$meta[] = array(
				'key' => 'phone_no',
				'value' => $phone_no_top,
				'compare' => 'LIKE');
				}
				if (isset($f_name) && !empty($f_name)) {
				$meta[] = array(
				'key' => 'f_name',
				'value' =>$f_name,
				'compare' => 'LIKE');
				}
				if (isset($joining_date) && !empty($joining_date)) {
					$meta[] = array(
					'key' => 'joining_date',
					'value' =>$joining_date,
					'compare' => '=');
				}
				if (isset($dob) && !empty($dob)) {
					$meta[] = array(
					'key' => 'dob',
					'value' =>$dob,
					'compare' => '=');
				}
				if (isset($occurrence_city) && !empty($occurrence_city)) {
					$meta[] = array(
					'key' => 'occurrence_city',
					'value' =>$occurrence_city,
					'compare' => 'LIKE');
				}
				if (isset($village) && !empty($village)) {
					$meta[] = array(
					'key' => 'village',
					'value' =>$village,
					'compare' => 'LIKE');
				}
				if (isset($tehsil) && !empty($tehsil)) {
					$meta[] = array(
					'key' => 'tehsil',
					'value' =>$tehsil,
					'compare' => 'LIKE');
				}
				if (isset($city) && !empty($city)) {
					$meta[] = array(
					'key' => 'city',
					'value' =>$city,
					'compare' => 'LIKE');
				}
				
			 
			
			   //	$args = array( 'post_type' => 'people', 'orderby' => 'title','order' => ASC, 'posts_per_page' => -1,
			   //	'meta_query' => array(
			   //'relation' => 'OR', $meta));
               //$loop = new WP_Query( $args );

			//echo '<pre>';
			//print_r( $meta_query);die;  
			// echo '<pre>';
			//print_r($meta_query);die; 

			$meta_query = array(
				'meta_query' => array(
					'relation' => 'OR',
					 $meta,
				)
			);

			$query->set('meta_query', $meta_query);
		}
	}
}

 



 


function hkdc_admin_styles()
{
	wp_enqueue_style('jquery-ui-datepicker-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
}
add_action('admin_print_styles', 'hkdc_admin_styles');
function hkdc_admin_scripts()
{
	wp_enqueue_script('jquery-ui-datepicker');
}
add_action('admin_enqueue_scripts', 'hkdc_admin_scripts');








	function modify_jquery()
	{
		if (!is_admin()) {
			// comment out the next two lines to load the local copy of jQuery
			wp_deregister_script('jquery');
			wp_register_script('jquery', 'http://ajax.googleapis.com/ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', false, '2.1.1');
			wp_enqueue_script('jquery');
		}
	}

	// send notification to android 
function send_android_notification($title, $message, $token, $redirection = "", $group_id = "", $group_name = "", $restaturent_id = "", $notification_id = "", $show_action_button = "no")
{
    if (!is_array($token)) {
        $token = array($token);
    }
    
  
    $SERVER_API_KEY = "AAAA6CWd9ag:APA91bFsHTnmdrOOGRwfb3_JA94-begqCmgtCMWM5JuuDE3nSjbCKN1VV2zi4RtbPy73QCbCphGxTpm9_Jef6UqtnUquFhilPjXqnCwoNbVP3g7F5WJxFT5Fw0hrp-BSv5SiG9m8QVMc";
    /* $data = [
        "registration_ids" => $token,
        "data" => [
            "title" => $title,
            "body" => $message,
            "redirection" => $redirection,
            "group_id" => $group_id,
        ]
    ]; */

    $data = [
        "registration_ids" => $token,
        "notification" => [
            "title" => $title,
            "body" => $message,
            "sound" => "default",
            "message" => $message,
            "largeIcon" => "notification_icon",
            "smallIcon" => "ic_notification",
            "show_in_foreground" => true,
            "content_available" => true,
            "priority" => "high",
            "userInteraction" => false,
        ],
        "data" => [
            "message" => $message,
            "largeIcon" => "notification_icon",
            "smallIcon" => "ic_notification",
            "show_in_foreground" => true,
            "content_available" => true,
            "priority" => "high",
            "userInteraction" => false,
            "redirection" => $redirection,
            "group_id" => $group_id,
            "group_name" => $group_name,
            "restaturent_id" => $restaturent_id,
            "notification_id" => $notification_id,
            "show_action_button" => $show_action_button,
        ],
    ];
    // "<pre>"; print_r($data); die;

    $dataString = json_encode($data);
    //echo  $dataString; die;
    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $rest = curl_exec($ch);

    // Close connection
    curl_close($ch);
   // echo $rest;die;
   //echo  $rest; die;
}


	wp_enqueue_script('wp-jquery-date-picker', get_template_directory_uri() . '/js/admin.js');





add_action('admin_head', 'my_custom_fonts');

///SK DJ Sound add css here

function my_custom_fonts() {
  echo '<style> 

  .alignleft.actions.bulkactions + .alignleft.actions{margin:10px 0  !important;}
  .alignleft.actions.bulkactions + .alignleft.actions input{margin:0 10px 0 0 !important;}
  .alignleft.actions.bulkactions + .alignleft.actions select{margin:0 10px 0 0 !important;}


  </style>';
}

// See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
//add_filter( 'cron_schedules', 'isa_add_every_five_minutes' );
 
 

// Hook into that action that'll fire every five minutes
add_action( 'send_message_all_user', 'every_five_minutes_event_func' );
function every_five_minutes_event_func() {
    // do something here you can perform anything
	
	 
   // mail("likhamahudda@gmail.com",'ramk','nnn');
 date_default_timezone_set("Asia/Calcutta"); 

$current_time = strtotime('now'); // Get the current timestamp
$start_time = strtotime('4:00:00'); // Get the timestamp for 5:00:00 AM
$end_time = strtotime('7:00:00'); // Get the timestamp for 6:00:00 AM

if ($current_time >= $start_time && $current_time <= $end_time) {
    // Current time is between 5:00:00 AM and 6:00:00 AM
    global $wpdb; 
    $date = date('Y-m-d');
    $get_message = $wpdb->get_results(
        "SELECT * " .
            "FROM wp_user_message " .
            "WHERE is_send = '0' and send_date ='". $date."'"
    );
    if(count($get_message) > 0){ //
    
    $current_date = date("Y-m-d");
    
    $all_uses = $wpdb->get_results(
        "SELECT ID,display_name,user_email " .
            "FROM $wpdb->users AS users " .
            "WHERE user_status = '0'  and (message_date IS NULL OR message_date !='".$current_date."')  ORDER BY ID ASC LIMIT 50 "
    );
    
    $msg_id = $get_message[0]->id;
    
    
    $total_uses = $wpdb->get_results(
        "SELECT  COUNT(ID) as total_count " .
            "FROM $wpdb->users AS users " .
            "WHERE user_status = '0'  and (message_date IS NULL OR message_date !='".$current_date."') "
    );
     
     
    if(isset($total_uses[0]->total_count) &&  ($total_uses[0]->total_count) == 0){  
    
        $wpdb->query(
        $wpdb->prepare(
            "UPDATE wp_user_message SET is_send = %s WHERE id = %d",
            '1',
            $msg_id
        )
        );
    
    }  
    
    foreach ($all_uses as $val) { 
    
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE wp_users SET message_date = %s WHERE ID = %d",
                $current_date,
                $val->ID
            )
            );
    
        $fcm_token = get_metadata('user', $val->ID, 'fcm_token', true);

        if(isset( $fcm_token) && !empty( $fcm_token)){
    
        $title = $get_message[0]->title;
        $message =$get_message[0]->message;
        $token = $fcm_token;
    
       // mail("likhamahudda@gmail.com",$title,$message);
    
         send_android_notification($title, $message, $token, $redirection = "", $group_id = "", $group_name = "", $restaturent_id = "", $notification_id = "", $show_action_button = "no");
        }
     }
    
     }

} else {
    // Current time is not between 5:00:00 AM and 6:00:00 AM
   // echo 'Current time is not between 5:00:00 AM and 6:00:00 AM.';
}

}


// Hook into that action that'll fire every five minutes
add_action( 'send_notification_all_user', 'send_notification_every_two_minutes_event_func' );
function send_notification_every_two_minutes_event_func() {
    // do something here you can perform anything

    //die;

      //mail("likhamahudda@gmail.com",'77777','111111');
 
	global $wpdb;  
    $date = date('Y-m-d');
	$get_message = $wpdb->get_results(
		"SELECT * " .
			"FROM wp_user_notification " .
			"WHERE status = '0' ORDER BY created_at ASC LIMIT 50"
	);
	if(count($get_message) > 0){ // 
  
	foreach ($get_message as $val) {

		$fcm_token = get_metadata('user', $val->user_id, 'fcm_token', true);

		$title = $val->title;
		$message =$val->message;
		$token = $fcm_token;
        $notification_id =  $val->id;


        $wpdb->query(
            $wpdb->prepare(
                "UPDATE wp_user_notification SET status = %s WHERE id = %d",
                '1',
                $notification_id
            )
            );

       // mail("likhamahudda@gmail.com",$title,$message);
       if(isset($fcm_token) && !empty($fcm_token)){

        send_android_notification($title, $message, $token, $redirection = "", $group_id = "", $group_name = "", $restaturent_id = "", $notification_id = "", $show_action_button = "no");
 
       }

     
    
		 
 	}

	}

}
 