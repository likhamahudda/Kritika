@extends('layout.user')
@section('content')
<!-- content -->
<div class="content">
   <!-- content-header -->
   <div class="header_container-top">
      <!--<div class="content-breadcrumb">
         </div> -->
      <!-- /content-header -->
      <div class="page-name">
         <p>Sign Document</p>
      </div>
   </div>
   <!-- content-body -->
   <div class="content-body">
      <div class="mobile-signature">
         <div class="signature-main-box">
            <!--always define class .first for first-child of li element sidebar left-->
            <ul class="signature-back" role="menu" aria-labelledby="dLabel">
               <li>
                  <div class="signature-main">
                     <h1 class="Sign-heading">Your Signature</h1>
                     <div class="signature-image" id="create_sign_disp_ajax">
                        @if(isset($signaure->signature_image) && !empty($signaure->signature_image))
                        <div id="edit_user_sign" class="edit_sign_cont text-center">
                           <a href="javascript:void(0);" id="edit_sign_link" class="edit-icon" title="Edit Signature"><i class="icon-pencil"></i></a>
                           <img class="edit_this_img" src="{{url('uploads/signature')}}/{{$signaure->signature_image}}" />
                        </div>
                        @else
                        <a class="add-sign-init" href="javascript:void(0);" id="add_sign" title="Add Signature"><i class="icon-plus-sign"></i></a>
                        @endif
                     </div>
                  </div>
                  <div class="signature-main">
                     <h1 class="Sign-heading">Your initials</h1>
                     <div class="signature-image" id="create_initial_disp_ajax">
                        @if(isset($initials->signature_image) && !empty($initials->signature_image))
                        <div id="edit_user_initial" class="edit_sign_cont text-center">
                           <a href="javascript:void(0);" id="edit_initial_link" class="edit-icon" title="Edit Initial"><i class="icon-pencil"></i></a>
                           <img class="edit_this_img" src="{{url('uploads/signature')}}/{{$initials->signature_image}}" />
                        </div>
                        @else
                        <a class="add-sign-init" href="javascript:void(0);" id="add_initial" title="Add Initial"><i class="icon-plus-sign"></i></a>
                        @endif
                     </div>
                  </div>
               </li>
            </ul>
            <div class="clearfix"></div>
         </div>
         <div id="">
         </div>
      </div>
      <div class="row-fluid">
         <div class="span12">
            <div id="content">
               <div class="row-fluid">
                  <div class="span12">
                     <div id="content">
                        <script>
                           //----------------- for google drive picker -----------------------//
                           var clientId = '237526612699-tj94upq3h96tvem27sga2r82tptb6him.apps.googleusercontent.com';
                           var developerKey = 'AIzaSyAMlPUnIyjKJ_dgKYHeBQYN-xJyQDQOo5I';
                           // for box picker 
                           var client_id_box = 'fedb8msfwzblru5h7se6efozy8ica4fw';
                           var coreframe_temp = "/billing/SpecificPay/?is_iframe=1&is_from_pricing=1";
                        </script>
                        <style>
                           #attach_doc_drpdown {
                           margin-bottom: 40px;
                           }
                           #attach_doc_drpdown label {
                           padding-top: 10px;
                           font-weight: bold;
                           }
                           .select2-container-multi .select2-choices {
                           border: 1px solid #EAE9E5;
                           order-radius: 4px;
                           -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                           box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                           -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                           transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                           }
                           #attach_doc_drpdown .select2-container {
                           width: 100%;
                           margin-left: 0;
                           border: 0;
                           box-shadow: none;
                           }
                           .setting_checkbox {
                           margin-bottom: 10px;
                           }
                           .checkbox-top {
                           margin: 0 !important;
                           }
                           #attach_doc_drpdown .select2-container:focus {
                           border-color: #9a9995;
                           outline: 0 none;
                           }
                           #is_notification_enable {
                           margin-right: 2px;
                           }
                        </style>
                        <script>
                           var user_date_format = 'MM / DD /YYYY';
                        </script>
                        <div class="row-fluid">
                           <div class="box corner-all">
                              <div class="listing-main-hold span12">
                                 <!--<span class="span12">
                                    <h1 class="cmn-header">Create Signature Request </h1><!--/page header-->
                                 <!--</span>-->
                                 <div class="pull-right">
                                    <input type="hidden" id="doc_valid_size" value="10485760">
                                 </div>
                                 <div class="clearfix"></div>
                                 <div class="row-fluid sign_doc_main">
                                    <h4 class="pagetitle">Signature Request </h4>
                                    <div class="sign-left">
                                       <div id="fill_doc_details" class="assign-details-main">
                                          <div id="div_doc_name">
                                             <div class="span12">
                                                <form id="uploadfile" method="post" enctype="multipart/form-data">
                                                   <div class="">
                                                      <h5>1. What needs to be signed?</h5>
                                                   </div>
                                                   <div class="create-assign-upl row-fluid" id="divdragfiles">
                                                      <div class="btn-group pull-left">
                                                         <a href="javaScript:void(0)" data-toggle="dropdown" class="dropdown-toggle btn-large btn-action-basic up-btn action-btn btn-primary duplbtn">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <g clip-path="url(#clip0_215_1418)">
                                                                  <path d="M12 0.75C5.79675 0.75 0.75 5.79639 0.75 12C0.75 18.2036 5.79675 23.25 12 23.25C18.2033 23.25 23.25 18.2036 23.25 12C23.25 5.79639 18.2033 0.75 12 0.75ZM12 21.75C6.62366 21.75 2.25 17.376 2.25 12C2.25 6.62402 6.62366 2.25 12 2.25C17.3763 2.25 21.75 6.62402 21.75 12C21.75 17.376 17.3763 21.75 12 21.75Z" fill="#84A9EE"/>
                                                                  <path d="M10.1726 8.98169L11.25 7.9043V14.445C11.25 14.8575 11.5875 15.195 12 15.195C12.4125 15.195 12.75 14.8575 12.75 14.445V7.9043L13.8274 8.98169C13.9739 9.12817 14.1658 9.20141 14.3577 9.20141C14.5496 9.20141 14.7415 9.12817 14.8879 8.98169C15.1809 8.68871 15.1809 8.21411 14.8879 7.92114L12.5303 5.56348C12.2373 5.27051 11.7627 5.27051 11.4697 5.56348L9.11206 7.92114C8.81909 8.21411 8.81909 8.68872 9.11206 8.98169C9.40504 9.27465 9.87964 9.27466 10.1726 8.98169Z" fill="#84A9EE"/>
                                                                  <path d="M17.8187 11.3379C17.4045 11.3379 17.0687 11.6733 17.0687 12.0879C17.0687 14.8828 14.7949 17.1562 12 17.1562C9.20508 17.1562 6.93127 14.8828 6.93127 12.0879C6.93127 11.6733 6.59546 11.3379 6.18127 11.3379C5.76709 11.3379 5.43127 11.6733 5.43127 12.0879C5.43127 15.7097 8.37817 18.6562 12 18.6562C15.6218 18.6562 18.5687 15.7097 18.5687 12.0879C18.5687 11.6733 18.2329 11.3379 17.8187 11.3379Z" fill="#84A9EE"/>
                                                               </g>
                                                               <defs>
                                                                  <clipPath id="clip0_215_1418">
                                                                     <rect width="24" height="24" fill="white"/>
                                                                  </clipPath>
                                                               </defs>
                                                            </svg>
                                                            Upload<i class="icon-chevron-down"></i>
                                                         </a>
                                                         <ul role="menu" class="dropdown-menu span12">
                                                            <li>
                                                               <a href="javascript:void(0);" id="open_file_upl" title="upload from system" class="">
                                                               <span class="icon-desktop"></span> Choose from System
                                                               </a>
                                                            </li>
                                                            <li><a href="javascript:void(0);" class="select-sys-template-btn"> <span class="icon-copy"></span>
                                                               Template directory</a>
                                                            </li>
                                                         </ul>
                                                      </div>
                                                      <div class="option or_bg pull-right">or</div>
                                                      <div class="drag-files-here">
                                                         <span>Drop your files here</span>
                                                         <span class="alert-new label label-important displaynone pull-right" id="doc_upload_sel_error">Don't forget to attach a file.</span>
                                                         <div class="clearfix"></div>
                                                         <input type="file" name="upl" id="upl" />
                                                      </div>
                                                   </div>
                                                   <input type="hidden" id="doc_name" name="doc_name" value="">
                                                   <input name="doc_desc" type="hidden" id="doc_desc" />
                                                   <input type="hidden" name="" value="" />
                                                </form>
                                             </div>
                                          </div>
                                          <form name="assign_details" id="assign_details">
                                             <div class="cont-sign-1 common_assign">
                                                <div class="span8 view-doc-popup hide " id="updatedDocListCont">
                                                   <select name="doclist" id="doclist" class="span12">
                                                   </select> <!--                                    <input type="text" value="" placeholder="Select Documents" class="form-control span12">-->
                                                   <!--                                        <a href="#" id="open_doc_gall" class="doc-view" ><i class="icon-white  icon-zoom-in"></i></a>-->
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="row-fluid common_inner_box">
                                                   <div class="span4 mobile-float-left">
                                                      <p class="status-doc">
                                                         <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip0_215_1424)">
                                                               <path d="M10 0.625C4.83063 0.625 0.625 4.83033 0.625 10C0.625 15.1697 4.83063 19.375 10 19.375C15.1694 19.375 19.375 15.1697 19.375 10C19.375 4.83033 15.1694 0.625 10 0.625ZM10 18.125C5.51971 18.125 1.875 14.48 1.875 10C1.875 5.52002 5.51971 1.875 10 1.875C14.4803 1.875 18.125 5.52002 18.125 10C18.125 14.48 14.4803 18.125 10 18.125Z" fill="#929FB8"/>
                                                               <path d="M8.47717 7.48475L9.375 6.58691V12.0375C9.375 12.3813 9.65626 12.6625 10 12.6625C10.3437 12.6625 10.625 12.3813 10.625 12.0375V6.58691L11.5228 7.48474C11.6449 7.60681 11.8048 7.66785 11.9647 7.66785C12.1246 7.66785 12.2845 7.60681 12.4066 7.48474C12.6508 7.2406 12.6508 6.84509 12.4066 6.60095L10.4419 4.63623C10.1977 4.39209 9.80224 4.39209 9.5581 4.63623L7.59339 6.60095C7.34924 6.84509 7.34924 7.2406 7.59339 7.48474C7.83753 7.72888 8.23303 7.72888 8.47717 7.48475Z" fill="#929FB8"/>
                                                               <path d="M14.849 9.44824C14.5038 9.44824 14.224 9.72779 14.224 10.0732C14.224 12.4023 12.3292 14.2969 10.0001 14.2969C7.67096 14.2969 5.77612 12.4023 5.77612 10.0732C5.77612 9.72779 5.49628 9.44824 5.15112 9.44824C4.80597 9.44824 4.52612 9.72779 4.52612 10.0732C4.52612 13.0914 6.98187 15.5469 10.0001 15.5469C13.0182 15.5469 15.474 13.0914 15.474 10.0732C15.474 9.72778 15.1942 9.44824 14.849 9.44824Z" fill="#929FB8"/>
                                                            </g>
                                                            <defs>
                                                               <clipPath id="clip0_215_1424">
                                                                  <rect width="20" height="20" fill="white"/>
                                                               </clipPath>
                                                            </defs>
                                                         </svg>
                                                         Upload Status
                                                      </p>
                                                   </div>
                                                   <div class="span8 mobile-float-right">
                                                      <a href="javascript:void(0);" class="select-temp select-template -right"><i class="far fa-plus"></i>Select Template</a>
                                                   </div>
                                                   <div class="row-fluid">
                                                      <div class="span12 loader-upload-before doc-status-main hide" id="box_corner_filest">
                                                         <div class="box-body">
                                                            <ul class="border" id="uplstatus">
                                                               <p class="no-doc">No Documents Upload here</p>
                                                            </ul>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="cont-assign-3 common_assign">
                                                <div>
                                                   <h5>2. Who needs to sign?&nbsp; <i data-tipso="<ul><li>Define the type who are involved in signing.</li></ul>" class="animate icon icon-info-sign"></i></h5>
                                                </div>
                                                <div class="row-fluid">
                                                   <div class="span12 text-center " id="swicth_assign_type">
                                                      <ul class="create_list">
                                                         <li class="create_list_col">
                                                            <a href="javascript:void(0)" data-assign-t="1" class="template-main-anchor active">
                                                               <div class="templates-type-hold">
                                                                  <div class="template-pic-type-hold">
                                                                     <img data-src="holder.js/300x200" class="" alt="assignment_type" src="{{URL::asset('esign_example/frontend-theme/img/me.svg')}}" />
                                                                  </div>
                                                                  <div class="Signed-text">
                                                                     <span>Signer is</span>
                                                                     <div class="text">Just Me</div>
                                                                  </div>
                                                                  <div class="clearfix"></div>
                                                               </div>
                                                            </a>
                                                         </li>
                                                         <li class="create_list_col">
                                                            <a href="javascript:void(0)" data-assign-t="2" class="template-main-anchor ">
                                                               <div class="templates-type-hold">
                                                                  <div class="template-pic-type-hold">
                                                                     <img data-src="holder.js/300x200" class="" alt="assignment_type" src="{{URL::asset('esign_example/frontend-theme/img/me_others.svg')}}" />
                                                                  </div>
                                                                  <div class="Signed-text">
                                                                     <span>Signers are</span>
                                                                     <div class="text">Me & Others</div>
                                                                  </div>
                                                                  <div class="clearfix"></div>
                                                               </div>
                                                            </a>
                                                         </li>
                                                         <li class="create_list_col">
                                                            <a href="javascript:void(0);" data-assign-t="3" class="template-main-anchor ">
                                                               <div class="templates-type-hold">
                                                                  <div class="template-pic-type-hold">
                                                                     <img data-src="holder.js/300x200" class="" alt="assignment_type" src="{{URL::asset('esign_example/frontend-theme/img/others.svg')}}" />
                                                                  </div>
                                                                  <div class="Signed-text">
                                                                     <span>Signer(s)</span>
                                                                     <div class="text">Just Others</div>
                                                                  </div>
                                                                  <div class="clearfix"></div>
                                                               </div>
                                                            </a>
                                                         </li>
                                                      </ul>
                                                   </div>
                                                </div>
                                                <!--  row fluid ends here -->
                                                <div class="alert alert-danger displaynone" id="receps_error"></div>
                                                <style>
                                                   .controls.rows-disp {
                                                   position: relative;
                                                   float: left;
                                                   width: 100%;
                                                   }
                                                   .order_main .controls.rows-disp {
                                                   padding-left: 30px;
                                                   }
                                                   .btn_move_div i {
                                                   margin: 0;
                                                   line-height: 14px;
                                                   }
                                                   .btn_move_div {
                                                   background: none;
                                                   left: 0;
                                                   position: absolute;
                                                   top: 0;
                                                   border: 1px solid #999;
                                                   color: #999;
                                                   border-radius: 2px;
                                                   cursor: pointer;
                                                   text-align: center;
                                                   line-height: 16px;
                                                   width: 16px;
                                                   height: 16px;
                                                   }
                                                   .btn_move_div.btn_chain_up {
                                                   top: 0;
                                                   }
                                                   .btn_move_div.btn_chain_down {
                                                   top: 18px;
                                                   }
                                                   .error_msg_pq_class {}
                                                   .duplbtn {
                                                   min-width: 153px;
                                                   }
                                                   .dropbox-dropin-btn,
                                                   .dropbox-dropin-btn:link,
                                                   .dropbox-dropin-btn:hover {
                                                   -moz-border-bottom-colors: none;
                                                   -moz-border-left-colors: none;
                                                   -moz-border-right-colors: none;
                                                   -moz-border-top-colors: none;
                                                   background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #fcfcfc 0%, #f5f5f5 100%) repeat scroll 0 0;
                                                   border-color: none !important;
                                                   border-image: none;
                                                   border-radius: 2px;
                                                   border-style: none;
                                                   border-width: 1px;
                                                   color: #636363;
                                                   display: inline-block;
                                                   font-family: "Lucida Grande", "Segoe UI", "Tahoma", "Helvetica Neue", "Helvetica", sans-serif;
                                                   font-size: 12px;
                                                   font-weight: 500;
                                                   height: 14px;
                                                   padding: 1px 7px 5px 10px;
                                                   text-decoration: none;
                                                   }
                                                </style>
                                                <div class="control-group">
                                                   <div id="recep_controls">
                                                      <div style="display: none;" id="merecep_div">
                                                         <h6>My details</h6>
                                                         <div class="row-fluid">
                                                            <div class="span12 controls rows-disp" id="recep_inputbox_0">
                                                               <input type="text" class="form-control role_txt" id="role_txt_name_0" name="role_txt[]" placeholder="My role" value="Manager">
                                                               <input type="text" class="form-control recep_txt" id="recep_txt_name_0" name="recep_txt[]" placeholder="First name" value="Kartik">
                                                               <input readonly="readonly" type="text" class="form-control recep_email_txt" id="recep_txt_email_0" name="recep_email_txt[]" placeholder="Recipient's Email" value="k.pareek@mtoag.com">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div id="recep_div" style="display: none;" class="recep-roles FieldContainer">
                                                         <h6>Other signers details</h6>
                                                         <div class="controls rows-disp order_main new-input roles-recep-new-row OrderingField" id="recep_inputbox_2">
                                                            <input type="text" class="form-control role_txt" id="role_txt_name_2" name="role_txt[]" placeholder="Like :-  Manager or Client etc" value="">
                                                            <input type="text" class="form-control recep_txt" id="recep_txt_name_2" name="recep_txt[]" placeholder="First name" value="">
                                                            <input type="text" class="form-control recep_email_txt" id="recep_txt_email_2" name="recep_email_txt[]" placeholder="Recipient's Email" value="">
                                                            <div class="button_move_display displaynone">
                                                               <span></span>
                                                               <button value='up' class="btn_move_div btn_chain_up"><i class="icon-angle-up"></i></button>
                                                               <button value='down' class="btn_move_div btn_chain_down"><i class="icon-angle-down"></i></button>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <a href="#" id="add_recep" class="displaynone pull-left"><i class="icon-white icon-plus-sign"></i>Add Recipient</a>
                                                      <input type="hidden" value="" id="hdn_roles_ids" name="hdn_roles_ids">
                                                   </div>
                                                </div>
                                                <div class="clearfix"></div>
                                             </div>
                                             <div class="clearfix"></div>
                                             <div class="alert alert-danger displaynone" id="not_assign_details_error"></div>
                                             <div class="clearfix"></div>
                                             <div class="cont-assign-3 common_assign">
                                                <div class="row-fluid">
                                                   <h5>3. Where do they need to sign? <i data-tipso="<ul><li>Define places in uploaded documents where you want your recepients to put the content</li></ul>" class="animate icon icon-info-sign"></i></h5>
                                                   <a href="#" id="btn_prepare_doc" class="btn btn-default"><i class="icon-white icon-file-text"></i> Prepare Docs For Signing</a>
                                                </div>
                                             </div>
                                             <div class="cont-assign-4 common_assign">
                                                <div class="alert alert-danger displaynone" id="doc_details_error"></div>
                                                <div class="row-fluid">
                                                   <h5>4. Add a title and custom message</h5>
                                                </div>
                                                <div class="row-fluid cus_message">
                                                   <div class="span7">
                                                      <div class="control-group">
                                                         <div class="controls">
                                                            <input class="form-control" type="text" id="assign_title" name="assign_title" placeholder="Signature Request  Name" value="" />
                                                         </div>
                                                      </div>
                                                      <div class="control-group">
                                                         <div class="controls">
                                                            <textarea class="form-control" id="assign_desc" name="assign_desc" placeholder="Signature Request  Message"></textarea>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="clearfix mar_top10"></div>
                                             <div class="cont-assign-6 common_assign last">
                                                <a href="#" class="btn btn-primary  btn_save_assignment "><i class="icon-white icon-ok-sign"></i> Submit</a>
                                                <a href="#" class="btn btn-default btn_reset_assignment"><i class="icon-white icon-refresh"></i> Reset</a>
                                             </div>
                                             <div class="small-ht-diff"></div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!--/row-fluid-->
                        <input type="hidden" name="unkey" id="unkey" value="{{$unkey}}" />
                        <!-- Modal box for Details -->
                        <div class="nymodal modal hide fade template-buy-modal" id="template_details" aria-hidden="true">
                           <div class="modal-dialog" style="width:100%">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <a aria-hidden="true" data-dismiss="modal" class="close_out" type="button"></a>
                                    <h4>Template directory</h4>
                                 </div>
                                 <div id="template_modal_body" class="modal-body template_modal_body">
                                 </div>
                                 <div class="modal-footer hide">
                                    <a href="javascript:void(0)" id="btn-authpayment" class="btn btn-primary hide"><i class="icon-white icon-legal"></i> Authorise</a>
                                    <a href="javascript:void(0)" id="btn-cancel" data-dismiss="modal" class="btn btn-default"><i class="icon-white icon-remove"></i> Close</a>
                                 </div>
                              </div>
                              <!-- /.modal-content -->
                           </div>
                           <!-- /.modal-dialog -->
                        </div>
                        <div class="nymodal modal hide fade" id="template_details_user" style="max-height:500px; overflow-y:auto;" aria-hidden="true">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <a aria-hidden="true" data-dismiss="modal" class="close_out" type="button"></a>
                                    <h4>Choose Template</h4>
                                 </div>
                                 <div id="template_modal_body" class="modal-body">
                                    <!--  body content here -->
                                 </div>
                                 <div class="modal-footer hide">
                                    <a href="javascript:void(0)" id="btn-authpayment" class="btn btn-primary hide"><i class="icon-white icon-legal"></i> Authorise</a>
                                    <a href="javascript:void(0)" id="btn-cancel" data-dismiss="modal" class="btn btn-default"><i class="icon-white icon-remove"></i> Close</a>
                                 </div>
                              </div>
                              <!-- /.modal-content -->
                           </div>
                           <!-- /.modal-dialog -->
                        </div>
                        <!-- Modal box for personal questions -->
                        <div class="nymodal modal hide fade" id="personal_questions" aria-hidden="true">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <a aria-hidden="true" data-dismiss="modal" class="close_out" type="button"></a>
                                    <h4>Create secret questions</h4>
                                 </div>
                                 <div id="pq_modal_body" class="modal-body">
                                    <div class="error_msg_pq_class">
                                       <div id="error_msg_pq" class="hide alert alert-danger">Fields are not filled properly.</div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="">
                                       <div id="pq_inputbox" class="controls rows-disp roles-recep-new-row">
                                          <input type="text" class="form-control span4 uniqpq" id="q_box_1" name="q_txt1" placeholder="Question" value="">
                                          <input type="text" class="form-control span4" id="a_box_1" name="a_txt1" placeholder="Answer" value="">
                                       </div>
                                       <div id="pq_inputbox" class="controls rows-disp roles-recep-new-row mar_top10">
                                          <input type="text" class="form-control span4" id="q_box_2" name="q_txt2" placeholder="Question" value="" readonly=true>
                                          <input type="text" class="form-control span4" id="a_box_2" name="a_txt2" placeholder="Answer" value="" readonly=true>
                                          <input type="checkbox" name="vehicle" value="2" id="pqcheck2">
                                       </div>
                                    </div>
                                    <div class="clearfix"></div>
                                 </div>
                                 <div class="clearfix"></div>
                                 <div class="modal-footer mar_top10">
                                    <a href="javascript:void(0)" id="btn-pqsave" data-dismiss="modal" class="btn btn-primary"><i class="icon-white icon-ok-sign"></i> Done</a>
                                 </div>
                                 <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                           </div>
                           <div class="modal-backdrop hide in" id="back_sign_drop"></div>
                        </div>
                        <!-- Modal box for cloud service problem -->
                        <div class="nymodal modal hide fade" id="service_problem" aria-hidden="true">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <a aria-hidden="true" data-dismiss="modal" class="close_out" type="button"></a>
                                    <h4 class="service_title">Google Drive</h4>
                                 </div>
                                 <div class="modal-body" id="service_content">
                                    <p>
                                       Service is not working properly due to any of the following reasons
                                    </p>
                                    <ul>
                                       <li>
                                          1. Your internet settings are not allowing services to be loaded
                                       </li>
                                       <li>
                                          2. Internet speed is slow to load the services
                                       </li>
                                       <li>
                                          3. Page could not be loaded properly.
                                       </li>
                                    </ul>
                                    <!-- /.modal-content -->
                                    <p>Try reloading page. <a href="#" onclick="window.location.reload(true);" title="reload"><i class="icon-refresh"></i></a></p>
                                 </div>
                                 <!-- /.modal-dialog -->
                              </div>
                           </div>
                        </div>
                        <!--   Modal for buy template -->
                        <div class="nymodal modal hide fade" id="buy_temp_modal" aria-hidden="true">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <div class="model-header-buy-temp">
                                    <a aria-hidden="true" data-dismiss="modal" class="close_out" type="button"></a>
                                    <h4 class="service_title">Buy Template</h4>
                                 </div>
                                 <div class="modal-body" id="payment_form">
                                 </div>
                                 <!-- /.modal-dialog -->
                              </div>
                           </div>
                        </div>
                        <div id="rolecount" class="custom_template">
                           <div id="recep_inputbox_${totRecepCount}" class="controls rows-disp hide">
                              <input type="text" class="form-control span4 ${roleclassname}" id="role_txt_name_${totRecepCount}" name="role_txt[]" placeholder="Manager or Client etc" value="">
                              <a title="remove" class="link ct_recep_remove" href="#" data-id="${totRecepCount}">
                              <i class="icon-black icon-remove-sign"></i>
                              </a>
                           </div>
                        </div>
                        <div id="recepcount" class="custom_template">
                           <div id="recep_inputbox_${totRecepCount}" class="controls rows-disp hide roles-recep-new-row OrderingField">
                              <input type="text" class="form-control span4 ${roleclassname}" id="role_txt_name_${totRecepCount}" name="role_txt[]" placeholder="Manager or Client etc" value="">
                              <input type="text" class="form-control span4 ${recepclassname}" id="recep_txt_name_${totRecepCount}" name="recep_txt[]" placeholder="First name" value="">
                              <input type="text" class="form-control span4 ${recepclassemail}" id="recep_txt_email_${totRecepCount}" name="recep_email_txt[]" placeholder="Recipient's Email" value="">
                              <a title="remove" class="link ct_recep_remove roles-recep-new-link" href="#" data-id="${totRecepCount}">
                              <i class="icon-black icon-remove-sign"></i>
                              </a>
                              <div class="button_move_display displaynone">
                                 <span></span>
                                 <button value='up' class="btn_move_div btn_chain_up"><i class="icon-angle-up"></i></button>
                                 <button value='down' class="btn_move_div btn_chain_down"><i class="icon-angle-down"></i></button>
                              </div>
                           </div>
                        </div>
                        <div id="docpagetemplate" class="custom_template">
                           <div class="doc_page ${doc_type} span10 wrap-center text-center" id="indoc_container_${docCount}">
                              <img id="doc_img_${docCount}" src="${docImagePath}" class="${docImgClass}" style="" />
                           </div>
                        </div>
                        <div id="tool_common_adv_tmp" class="custom_template">
                        </div>
                        <div id="tool_sign_tmp" class="custom_template">
                           <div class="tool_comp_main draggable component comp_customform" id="tool_component_${compCount}">
                              <div class="handle-wrapper">
                                 <div class="handleBar interactive">
                                    <div class="ie_rgba_fix"></div>
                                    <div class="dummyimage"></div>
                                 </div>
                                 <div class="wrapper interactive">
                                    <div class="ie_rgba_fix"></div>
                                    <div class="dummyimage"></div>
                                    <p class="description text-center" title="${compTitle}" style="line-height: 30px; height: 30px;">
                                       ${compTitle}
                                    </p>
                                 </div>
                              </div>
                              <div data-count="${compCount}" class="remove"></div>
                              <div class="arrow_box component-menu component-menu-signer add-my-own-menu-right">
                                 <span class="top-arrow"></span>
                                 <div class="assignment-container">
                                    <label>${compTypeLabel}</label>
                                    <select class="assignment-select form-control">
                                    </select>
                                 </div>
                                 <div class="required-box">
                                    <input checked="checked" type="checkbox" class="checkbox_required" name="checkbox_ruired_${compCount}">
                                    <label>Required</label>
                                 </div>
                                 <div class="advname-box sign-advanced">
                                    <label>Advanced features (optional)</label>
                                    <input type="text" value="" placeholder="ex: First Name">
                                    <a class="show-advanced">Advanced features »</a>
                                 </div>
                                 <div class="hide-component-menu text-center">
                                    <a href="#" data-count="${compCount}">Hide Menu</a>
                                 </div>
                                 <a href="#" data-count="${compCount}" class="menu-plus-button">+</a>
                                 <div class="clearfix"></div>
                              </div>
                           </div>
                        </div>
                        <div id="loadDiv" class="hide">
                           <div class="progress progress-striped active">
                              <div class="bar" style="width: 100%;">Please wait..</div>
                           </div>
                        </div>
                        <!-- 
                           <script type="text/javascript"
                               src="https://apis.google.com/js/client.js"></script>
                            -->
                        <script type="text/javascript">
                           function downloadJSAtOnload() {
                           
                               //---------------------- for google drive -------------------//
                               var element = document.createElement("script");
                               element.src = "https://apis.google.com/js/api.js";
                               document.body.appendChild(element);
                           
                               //------------------------ for box ------------------------//
                               var element = document.createElement("script");
                               element.src = "https://app.box.com/js/static/select.js";
                               document.body.appendChild(element);
                           
                           
                               //------------------- for dopboox -------------------//
                               var element = document.createElement("script");
                               element.src = "https://www.dropbox.com/static/api/2/dropins.js";
                               element.setAttribute("data-app-key", "mvsa4dyx9yoq71e");
                               element.setAttribute("id", "dropboxjs");
                               document.body.appendChild(element);
                           
                               //------------------- for one dive -------------------//
                               var element = document.createElement("script");
                               element.src = "https://js.live.net/v5.0/OneDrive.js";
                               element.setAttribute("client-id", "000000004016FC24");
                               element.setAttribute("id", "onedrive-js");
                               document.body.appendChild(element);
                           
                           
                           }
                           if (window.addEventListener)
                               window.addEventListener("load", downloadJSAtOnload, false);
                           else if (window.attachEvent)
                               window.attachEvent("onload", downloadJSAtOnload);
                           else window.onload = downloadJSAtOnload;
                        </script>
                     </div>
                     <!-- content -->
                  </div>
                  <div class="span3">
                     <div id="sidebar">
                     </div>
                     <!-- sidebar -->
                  </div>
               </div>
            </div>
         </div>
      </div>
      <footer class="footer">
         <p>&copy; 2022 Copyright Kodago-Vendors | All Rights Reserved.</p>
      </footer>
      <div class="divider-content"><span></span></div>
   </div>
   <!--/content-body -->
</div>
<!-- /content -->
@endsection