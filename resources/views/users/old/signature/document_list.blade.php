@extends('layout.user')
@section('content')
@php 
use App\Models\DocumentRoles;
@endphp
<style>
   .document-grid{
   margin: 20px;
   border: 2px solid #ddd;
   border-radius: 6px;  
   display: flex;  
   flex-wrap:wrap
   }
   .document-grid-col{
   flex:0 0 auto;
   width: 24.92%;
   margin: 20px;
   padding: 20px;
   border: 1px solid #ced4da;
   border-radius: 4px;   
   }
   .template-grid-date{
   float: right;
   padding: 8px;
   }
   .grid-btn-row span {
   margin-right: 15px;
   color: rgba(45, 52, 78, 0.7);
   font-size: 13px;
   line-height: 19px;
   text-transform: capitalize;
   display: flex;
   align-items: center;
   }
   .grid-btn-row {
   margin-top: 10px;
   padding-top: 10px;
   display: flex;
   border-top: 1px solid #ced4da;
   }
   .close_frame {
   margin-right: -11px !important;
   margin-top: -11px !important;
   }
   /*            .a-btn span:nth-child(1), .a-btn span:nth-child(3){top: 25px;}*/
</style>
@if($document_list_view=='1')
<style type="text/css">
   /*only for grid view*/
   .doc_listing .zoom-icon-pop {
   top: 48%!important;
   left: 48%!important;
   }
   .doc-image.doc-image_list_view img {
   width: 90%!important;
   margin: 6px 0 5px 9px;
   }
   .doc_listing_list .doc-image {
   height: auto!important;
   width: auto!important;
   }
   .doc_title {
   font-size: 16px!important;
   padding: 15px 0px 5px 0px;
   }
   /*only for grid view*/
</style>
@endif
<!-- content -->
<div class="content">
   <!-- content-header -->
   <div class="header_container-top">
      <!--<div class="content-breadcrumb">
         </div> -->
      <!-- /content-header -->
      <div class="page-name">
         <p>Documents</p>
      </div>
   </div>
   <!-- content-body -->
   <div class="content-body">
      <div class="row-fluid">
         <div class="span12">
            <div id="content">
               <div class="row-fluid">
                  <div class="span12">
                     <div id="content">
                        <script>
                           var dropbox_api_key = 'mvsa4dyx9yoq71e';
                        </script>
                        <div class="clearfix"> </div>
                        <div class="listing-main-hold">
                        <div class="table_cmn_update">
                           <!--three column-->
                           <div class="box box-search-form document-search-bar">
                              <div class="table_search">
                                 <form action="/assignments/list" method="post" name="searchform" id="searchform">
                                    <select name="search_assign_status" id="search_assign_status" class="search-fld  selector">
                                       <option value="0" selected="selected">Select Filters</option>
                                       <option value="1">Sent</option>
                                       <option value="2">Received</option>
                                       <option value="3">Awaiting Response
                                       </option>
                                       <option value="5">Completed</option>
                                       <option value="6">Document to be deleted</option>
                                       <option value="7">Scheduled Document</option>
                                    </select>
                                    <div class="search_fld">
                                       <div class="searchform-main">
                                          <input type="text" class="search-fld" placeholder="Search Documents" name="title" id="title" value="">
                                          <button class="search-btn"><i class="icon-search"></i></button>
                                       </div>
                                       <!--<button type="submit" title="Export Csv" class="btn btn-primary btn-sm " name="btn_type" value="export_doc_list"> <i class="icon-download"></i> Export</button> -->
                                    </div>
                                 </form>
                                 <style>
                                    .active-doc-note {
                                    clear: both;
                                    border: 1px solid #cfdbe2;
                                    background: #f5f7fa;
                                    padding: 10px 15px;
                                    border-radius: 3px;
                                    line-height: 21px;
                                    }
                                 </style>
                              </div>
                              <div class="pull-right view-box table-icon-right">
                                 <span>
                                 @if($document_list_view == '0')
                                 <a class="@if($document_list_view=='1') active_preference @endif" title="Grid view" href="{{url('assignments/saveDisplay/1')}}"><i class="fas fa-grip-horizontal"></i></a>
                                 @else 
                                 <a class="@if($document_list_view=='0') active_preference @endif" title="List view" href="{{url('assignments/saveDisplay/0')}}"><i class="icon-list"></i></a> </span>
                                 @endif
                              </div>
                           </div>
                           
                              <div class="box-body doc_listing doc_listing_list">
                                 @if(isset($prepared_docs) && count($prepared_docs) > 0)
                                 <div class="table-responsive document-table">
                                    @if($document_list_view!='1')
                                    <table class="table table-striped table-hover table-bordered">
                                       <thead>
                                          <tr>
                                             <th>Document</th>
                                             <th>Description</th>
                                             <th>Created at</th>
                                             <th>Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @foreach($prepared_docs as $document)
                                          @php
                                          $document_roles_info = DocumentRoles::where('prepared_document_ids',$document->id)->where('user_id',Auth::guard('user')->user()->id)->first();
                                          $count_all_receivers = DocumentRoles::where('prepared_document_ids',$document->id)->count();
                                          $count_all_completed_singnature = DocumentRoles::where('prepared_document_ids',$document->id)->where('signature_status','1')->count();
                                          @endphp
                                          <tr title="">
                                             <td class="td-schedule">
                                                <div class="doc-image doc-image_list_view">
                                                   <a href="javascript:void(0)" class="assignpreview doc_view_thumb thumbnail" data-doc="c93338185788319fce81159e14a7a61e" data-page="{{$document->total_pages}}" data-name="4598e05f469987b0" data-orname="gasgasd" data-delno="0" data-assign="81030">
                                                   <img class="img-rounded" alt="userdoc" src="{{url('/themes/frontend-theme/images/doc_thumb.png')}}">
                                                   </a>
                                                   <div class="overlay">
                                                      <a title="Document preview" href="javascript:void(0)" class="assignpreview" data-doc="{{$document->document_ids}}" data-page="{{$document->total_pages}}" data-name="4598e05f469987b0" data-orname="gasgasd" data-delno="0" data-assign="81030"> <i class="icon-zoom-in zoom-icon-pop"></i>
                                                      </a>
                                                   </div>
                                                </div>
                                             </td>
                                             <td class="td-schedule">
                                                <p id="assignname" class="doc_title">
                                                   <a href="javascript:void(0)" id="btndetails_e9c4b9f9a349b21f6899544f75259dce">{{ $document->assign_desc}}</a>
                                                </p>
                                                <ul class="listing">
                                                   <li><i class="far fa-clone"></i>{{$document->total_pages}}</li>
                                                   <li class="sign_doc"> <i class="far fa-paper-plane"></i>
                                                      Sent
                                                   </li>
                                                   @if(isset($document_roles_info->signature_status) && $document_roles_info->signature_status == '0')
                                                   <li>
                                                      <a target="_blank" href="{{url('request/signdoc?request=').$document_roles_info->unique_request_id}}"><i class="far fa-pencil"></i> Sign Doc</a>
                                                   </li>
                                                   @endif
                                                   <li class="asmnt-main-detail">
                                                      @if($count_all_receivers == $count_all_completed_singnature)
                                                      <i class="far fa-check-circle"></i> Completed
                                                      @else
                                                      <i class="far fa-clock"></i> Awaiting Response
                                                      @endif
                                                   </li>
                                                </ul>
                                             </td>
                                             <td class="td-schedule">{{date('M d, Y',strtotime($document->created_at))}}</td>
                                             <td class="actions_col td-schedule">
                                                <div class="btn-group asmnt-details-clk">
                                                   <button type="button" class="dropdown-toggle" title="Action" data-toggle="dropdown" aria-expanded="false">
                                                   <span class="sr-only" style="position: unset!important;"> Actions <i class="icon-circle-arrow-down"></i>
                                                   </span>
                                                   </button>
                                                   <ul class="dropdown-menu common-ul" role="menu">
                                                      <li><a href="javascript:void(0)" class="add_recep" title="Send docs" id="{{'btnsendattachment_'.$document->id}}">
                                                         <i class="icon-white icon-envelope"></i>Send docs
                                                         </a>
                                                      </li>
                                                      <li>
                                                         <a href="javascript:void(0)" class="don_recep" title="Download" id="{{'downloadbt_'.$document->document_ids}}"><i class="icon-download-alt"></i> Download </a>
                                                      </li>
                                                      <li><a href="javascript:void(0)" title="Move Documnet To Folder" id="{{'movedocument_'.$document->id}}" class="movedocument" data-id="{{$document->id}}" user-key="">
                                                         <i class="icon-copy"></i> Move Document
                                                         </a>
                                                      </li>
                                                      <li> <a href="javascript:void(0)" class="del_recep" title="Delete" id="{{'assigndlbt_'.$document->document_ids}}" completed="1"> <i class="icon-trash"></i> Delete </a> </li>
                                                   </ul>
                                                </div>
                                             </td>
                                          </tr>
                                          @endforeach
                                       </tbody>
                                    </table>
                                    @else
                                    <div class="row document-grid grid-cut">
                                       @foreach($prepared_docs as $document)
                                       @php
                                       $document_roles_info = DocumentRoles::where('prepared_document_ids',$document->id)->where('user_id',Auth::guard('user')->user()->id)->first();
                                       $count_all_receivers = DocumentRoles::where('prepared_document_ids',$document->id)->count();
                                       $count_all_completed_singnature = DocumentRoles::where('prepared_document_ids',$document->id)->where('signature_status','1')->count();
                                       @endphp
                                       <div class="document-grid-col">
                                          <div class="doc-image doc-image_list_view">
                                             <a href="javascript:void(0)" class="assignpreview" data-doc="c93338185788319fce81159e14a7a61e" data-page="{{$document->total_pages}}" data-name="4598e05f469987b0" data-orname="gasgasd" data-delno="0" data-assign="81030">
                                             <img class="img-rounded" alt="userdoc" src="{{url('/themes/frontend-theme/images/doc_thumb.png')}}">
                                             </a>
                                             <div class="overlay">
                                                <a title="Document preview" href="javascript:void(0)" class="assignpreview" data-doc="{{$document->document_ids}}" data-page="{{$document->total_pages}}" data-name="4598e05f469987b0" data-orname="gasgasd" data-delno="0" data-assign="81030"> <i class="icon-zoom-in zoom-icon-pop"></i>
                                                </a>
                                             </div>
                                          </div>
                                          <p id="assignname" class="doc_title">
                                             <a href="javascript:void(0)" id="btndetails_e9c4b9f9a349b21f6899544f75259dce">{{ $document->assign_desc}}</a>
                                          </p>
                                          <div class="btn-group asmnt-details-clk">
                                             <button type="button" class="dropdown-toggle" title="Action" data-toggle="dropdown" aria-expanded="false">
                                             <span class="sr-only" style="position: unset!important;"> Actions <i class="icon-circle-arrow-down"></i>
                                             </span>
                                             </button>
                                             <ul class="dropdown-menu common-ul" role="menu">
                                                <li><a href="javascript:void(0)" class="add_recep" title="Send docs" id="{{'btnsendattachment_'.$document->document_ids}}">
                                                   <i class="icon-white icon-envelope"></i>Send docs
                                                   </a>
                                                </li>
                                                <li>
                                                   <a href="javascript:void(0)" class="don_recep" title="Download" id="{{'downloadbt_'.$document->document_ids}}"><i class="icon-download-alt"></i> Download </a>
                                                </li>
                                                <li><a href="javascript:void(0)" title="Move Documnet To Folder" id="{{'movedocument_'.$document->id}}" class="movedocument" data-id="{{$document->id}}" user-key="">
                                                   <i class="icon-copy"></i> Move Document
                                                   </a>
                                                </li>
                                                <li> <a href="javascript:void(0)" class="del_recep" title="Delete" id="{{'assigndlbt_'.$document->document_ids}}" completed="1"> <i class="icon-trash"></i> Delete </a> </li>
                                             </ul>
                                          </div>
                                          <div class="template-grid-date">
                                             <svg xmlns="http://www.w3.org/2000/svg" width="11.375" height="13" viewBox="0 0 11.375 13">
                                                <path id="march" d="M3.758-4.062a.294.294,0,0,0,.216-.089.294.294,0,0,0,.089-.216V-5.383A.294.294,0,0,0,3.974-5.6a.294.294,0,0,0-.216-.089H2.742a.294.294,0,0,0-.216.089.294.294,0,0,0-.089.216v1.016a.294.294,0,0,0,.089.216.294.294,0,0,0,.216.089Zm2.742-.3a.294.294,0,0,1-.089.216.294.294,0,0,1-.216.089H5.18a.294.294,0,0,1-.216-.089.294.294,0,0,1-.089-.216V-5.383A.294.294,0,0,1,4.964-5.6a.294.294,0,0,1,.216-.089H6.2a.294.294,0,0,1,.216.089.294.294,0,0,1,.089.216Zm2.438,0a.294.294,0,0,1-.089.216.294.294,0,0,1-.216.089H7.617A.294.294,0,0,1,7.4-4.151a.294.294,0,0,1-.089-.216V-5.383A.294.294,0,0,1,7.4-5.6a.294.294,0,0,1,.216-.089H8.633a.294.294,0,0,1,.216.089.294.294,0,0,1,.089.216ZM6.5-1.93a.294.294,0,0,1-.089.216.294.294,0,0,1-.216.089H5.18a.294.294,0,0,1-.216-.089.294.294,0,0,1-.089-.216V-2.945a.294.294,0,0,1,.089-.216A.294.294,0,0,1,5.18-3.25H6.2a.294.294,0,0,1,.216.089.294.294,0,0,1,.089.216Zm-2.437,0a.294.294,0,0,1-.089.216.294.294,0,0,1-.216.089H2.742a.294.294,0,0,1-.216-.089.294.294,0,0,1-.089-.216V-2.945a.294.294,0,0,1,.089-.216.294.294,0,0,1,.216-.089H3.758a.294.294,0,0,1,.216.089.294.294,0,0,1,.089.216Zm4.875,0a.294.294,0,0,1-.089.216.294.294,0,0,1-.216.089H7.617A.294.294,0,0,1,7.4-1.714a.294.294,0,0,1-.089-.216V-2.945A.294.294,0,0,1,7.4-3.161a.294.294,0,0,1,.216-.089H8.633a.294.294,0,0,1,.216.089.294.294,0,0,1,.089.216Zm2.438-6.6a1.175,1.175,0,0,0-.355-.863,1.175,1.175,0,0,0-.863-.355H8.938v-1.32a.294.294,0,0,0-.089-.216.294.294,0,0,0-.216-.089H7.617a.294.294,0,0,0-.216.089.294.294,0,0,0-.089.216v1.32H4.063v-1.32a.294.294,0,0,0-.089-.216.294.294,0,0,0-.216-.089H2.742a.294.294,0,0,0-.216.089.294.294,0,0,0-.089.216v1.32H1.219a1.175,1.175,0,0,0-.863.355A1.175,1.175,0,0,0,0-8.531V.406a1.175,1.175,0,0,0,.355.863,1.175,1.175,0,0,0,.863.355h8.938a1.175,1.175,0,0,0,.863-.355,1.175,1.175,0,0,0,.355-.863ZM10.156.254a.146.146,0,0,1-.051.1.146.146,0,0,1-.1.051H1.371a.146.146,0,0,1-.1-.051.146.146,0,0,1-.051-.1V-7.312h8.938Z" transform="translate(0 11.375)" fill="rgba(45,52,78,0.7)"></path>
                                             </svg>
                                             {{date('M d, Y',strtotime($document->created_at))}}
                                          </div>
                                          <div class="listing grid-btn-row">
                                             <!-- <span>
                                                <i class="icon-copy"></i>{{$document->total_pages}}
                                                </span> -->
                                             <span class="sign_doc"> 
                                             <i class="far fa-paper-plane "></i>
                                             &nbsp;Sent
                                             </span>
                                             @if(isset($document_roles_info->signature_status) && $document_roles_info->signature_status == '0')
                                             <span>
                                             <a target="_blank" href="{{url('request/signdoc?request=').$document_roles_info->unique_request_id}}"><i class="far fa-pencil"></i> Sign Doc</a>
                                             </span>
                                             @endif
                                             <span class="asmnt-main-detail">
                                             @if($count_all_receivers == $count_all_completed_singnature)
                                             <i class="far fa-check-circle"></i> Completed
                                             @else
                                             <i class="far fa-clock"></i> Awaiting Response
                                             @endif
                                             </span>
                                          </div>
                                       </div>
                                       @endforeach
                                    </div>
                                    @endif
                                 </div>
                                 @else
                                 <ul id="view_opt" class="doclist_ul span12">
                                    <li class="span12">
                                       <div class="text-center alert alert-success">No Document exist with this search criteria.</div>
                                    </li>
                                 </ul>
                                 @endif
                                 <!--/three column-->
                              </div>
                              <form action="/assignments/dnAssignment" method="post" id="download_form"><input type="hidden" name="assignid" value="" id="hassignid">
                              </form>
                              <form action="/assignments/dnUserAttachZip" method="post" id="download_user_form"><input type="hidden" name="assign_id" value="" id="doc_assign_id">
                              </form>
                              <p class="active-doc-note"> For your data security &amp; privacy, we show only active documents (in process of a signatures or Signed in last 30 days). All previous documents (sent already on your email) are auto archived by eSignly System in offline drives. Need any of your older electronic documents? Please contact us at <a href="mailto:help@esignly.com">help@esignly.com</a>
                              </p>
                              <div class="pagination">
                              </div>
                           </div>
                        </div>
                        <!--/row-fluid-->
                     </div>
                     <!-- Modal box for Details -->
                     <div class="modal fade" id="assignment_details" style="display: none; max-height: 500px;" aria-hidden="true">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                              <div class="modal-header">
                                 <a type="button" class="close_out" data-dismiss="modal" aria-hidden="true"></a>
                                 <h4 class="modal-title">Document Details</h4>
                              </div>
                              <div id="assign_modal_body" class="modal-body">
                                 <!--  body content here -->
                              </div>
                           </div>
                           <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                     </div>
                     <!-- /.modal -->
                     <!-- Modal box for Activity Details -->
                     <div class="modal fade hide" id="activity_details" style="max-height: 580px; overflow-y: auto;" aria-hidden="true">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                 <h4 class="modal-message">Assignment Activities</h4>
                              </div>
                              <div id="assign_modal_body" class="modal-body">
                                 <div class="accordion" id="accordion2"></div>
                              </div>
                              <div class="modal-footer">
                                 <div class="progress progress-striped active hide">
                                    <div id="step_id" class="bar" style="width: 0%;"></div>
                                 </div>
                                 <a href="javascript:void(0)" id="btn-cancel" data-dismiss="modal" class="btn btn-primary"><i class="icon-white icon-remove"></i>Close</a>
                              </div>
                           </div>
                           <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                     </div>
                     <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
               </div>
               <!-- /.modal -->
               <div class="modal fade" id="request_loader_modal">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-body">
                           Please wait........
                           <div class="progress progress-striped active" style="height: 24px;">
                              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- /.modal-content -->
                  </div>
               </div>
               <!-- /.modal -->
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
@endsection