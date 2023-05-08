<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="{{URL::asset('esign_example/frontend-theme/compressedCss/request.signdoc.min.css')}}" />
    <script type="text/javascript" src="{{URL::asset('esign_example/frontend-theme/js/jquery.min.js')}}"></script>
    <title>Sign document online </title>

    <script>
        var SITE_URL = "{{url('/').'/'}}";;
        var CORE_THEME_BASE = "/themes/main-site";
        var THEME_BASE = "/themes/frontend-theme";
        var prepare_doc_sel_show = "role_name";
        var FRONT_URL = "{{url('/').'/'}}";
       
    </script>
</head>

<body style="padding-top: 0px;">

    <div class="header">
        <div class="panel-sitename col-sm-2">
            <img src="https://static.esignly.com/main-site/images/logo.svg" alt="" />
        </div>
        <div class="col-sm-6">
            <!-- You'll want to use a responsive image option so this logo looks good on devices - I recommend using something like retina.js (do a quick Google search for it and you'll find it) -->
            <!-- <a class="navbar-brand" href="index.html">Modern Business</a> -->
            <div class="">
                <div class="panel-docname">
                    <h4>{{$document_info->document_title}} </h4>
                </div>

            </div>
        </div>
        @php
            $signature_array = json_decode($document_info->signature_request_json,true);
        @endphp
        <!-- Collect the nav links, forms, and other content for toggling -->
        <!-- /.navbar-collapse -->
        @if($document_info->signature_status == '0')
        <div class="col-sm-4 progress-bar-header sign-progress-bar">
            <div class="col-sm-10">
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="sr-only ">0/0</span>
                    </div>
                </div>
            </div>
            <div class="progress-count col-sm-2">
                0/{{count($signature_array)}}
            </div>
        </div>
        @endif
        <div class="clearfix"></div>
    </div>
    <style>
        .close_frame {
            margin-right: -11px !important;
            margin-top: -11px !important;
        }

        .cust-fa-stamp {
            background: #27324f;
        }

        .tool_comp_main .remove {
            opacity: 1;
        }

        .container .inner_d_cont {
            background: #FFF;
        }

        /* .doc_container {
        max-width: 880px;
        min-width: 740px;
        margin: 0 auto;
    } */

        .doc_container {
            max-width: 100%;
            min-width: 100%;
            margin: 0 auto;
        }

        .space {
            margin-top: 30px;
        }

        .pq_form {
            margin: 10px auto;
            max-width: 530px;
            width: 96%;
        }

        .pq_form .controls #btn-proceed {
            margin-top: 20px;
        }

        .pq_form .control-label {
            margin-top: 20px;
            text-transform: capitalize;
        }

        .alert-danger i {
            background: rgba(255, 255, 255, 0.4) none repeat scroll 0 0;
            border-radius: 2px;
            margin-right: 5px;
            padding: 5px;
        }

        .attach_file {
            bottom: 0;
            cursor: pointer;
            left: 0;
            opacity: 0;
            padding: 6px 0;
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
        }

        .btn_doc_upload {
            margin: 0 0 0 20px !important;
            position: relative;
            width: 156px;
        }

        #docs_file {
            bottom: 0;
            cursor: pointer;
            left: 0;
            opacity: 0;
            padding: 6px 0;
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
        }

        .del_doc a {
            color: red !important;
            text-align: center
        }

        #form_upl {
            margin: 0;
        }

        .btn_doc_upload span {
            color: #222;
        }

        .upload-image-div {
            display: block;
            width: s%;
            text-align: center;
            clear: both;
            background: #fffdd0;
            margin-top: 5px;
        }

        .upload-image-div:after {
            content: '';
            clear: both;
            display: block;
        }

        .footer_content.agree_terms_cont p {
            margin: 0;
            display: inline-block;
            float: none;
        }

        .footer_content.agree_terms_cont p.del_doc {
            display: block;
        }

        .footer_content.agree_terms_cont p.del_doc a {
            text-transform: capitalize;
        }

        .footer_content.agree_terms_cont p.del_doc {
            display: block;
            line-height: 20px;
            padding-bottom: 8px;
        }

        .footer_content.agree_terms_cont .inner_sign_ft>p {
            float: left;
        }

        .agree-btn-signing {
            float: left;
            margin: 0 10px;
        }

        .btn_doc_upload {
            margin: 0 !important;
        }

        .btn_doc_upload {
            padding: 5px 12px;
        }

        #iDecline {
            margin: 0;
        }

        #upload_doc_div>p {
            font-size: initial;
            margin-left: 20%;
        }

        .edtrMain {
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .edtrMain .tools_bar_container {
            width: 100%;
            max-width: 200px;
            margin: 0 15px 0 0;
        }

        .content .span12 {
            width: 100%;
        }

        .zoomSelect.desk {
            display: block;
            position: static;
            margin: 0 0 10px;
        }

        .select-box {
            width: 188px;
            height: 40px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-right: 10px;
            position: relative;
            cursor: pointer;
        }

        .zoomSelect {
            position: absolute;
            bottom: 100%;
            right: 0;
            margin: 0 0 50px;
        }

        .select-box select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .select-box select {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 4px;
            background: #fff;
            padding: 0 0 0 15px;
            color: #9699a6;
            font-size: 16px;
            cursor: pointer;
        }

        .select-box:after {
            content: "";
            width: 28px;
            height: 100%;
            position: absolute;
            top: 0;
            right: 0;
            border-radius: 0 4px 4px 0;
            background: #fff url(../../images/down-arrow.png) no-repeat center left;
            pointer-events: none;
        }

        .container .inner_d_cont {
            background: #FFF;
            max-width: 1076px;
            margin: 0 auto;
        }

        .doc_container.fixed_doc_container {
            width: 100%;
            max-width: 1076px;
            background: #eff0f2;
            border: 1px solid #ced4da;
            padding: 0;
            position: relative;


        }
    </style>
    <script>
        var doc_setdata;
        var request;
        var user_key;
        var user_date_format = 'MM / DD /YYYY';
        var redirect_link = '';
    </script>
    <div class="sectiona">
        <div class="container">
            <div class="inner_d_cont">

                <!-- <div class="edtrMain">
                    <div class="tools_bar_container row-fluid">
                        <div class="span12 select-box filter-select zoomSelect desk">
                            <select id="zoomSelect">
                                <option value=".5">50%</option>
                                <option value=".75">75%</option>
                                <option selected value="1">Fit to width</option>
                                <option value="1.25">125%</option>
                                <option value="1.5">150%</option>
                                <option value="1.75">175%</option>
                                <option value="2">200%</option>
                                <option value="4">400%</option>
                            </select>
                        </div>
                    </div>
                </div> -->

                <input type="hidden" value="1" id="zoomSelect">
                <div class="doc_container fixed_doc_container">
                    <div class="main_doc_container" style="position: relative;">
                        <div id="prepare_doc_sign">

                            
                                <script>
                                    var doc_setdata = @php echo $document_info->signature_request_json; @endphp;
                                    var request = "{{$request_id}}";
                                    var user_key = "{{$user_id}}";
                                </script>
                           
                            @if($document_info->signature_status == '0')
                                @if(isset($pages_array) && !empty($pages_array))
                                    @foreach($pages_array as $key=>$page)
                                        <div id="indoc_container_{{($page+1)}}" class="doc_page portrait_doc span10 wrap-center text-center">
                                            <img style="" class="doc_page_img" data-src="{{url('/').'/uploads/documents/getdoc/assign_doc/'.$document_info->document_ids.'-'.($page+1).'.jpeg'}}" id="doc_img_{{($page+1)}}">
                                        </div>
                                    @endforeach
                                @endif
                            @else
                            <div class="alert alert-success dyna-alert text-center" style="margin-top: 173.5px; font-size: 30px;">
                                You have already signed this document.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="doc_loader" style="width: 880px;" class="wrap-center text-center displaynonehard">
                    <div class="xlarge-ht-diff"></div>
                    <div class="progress progress-striped">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="load-sr-only">0%</span>
                        </div>
                    </div>
                    <div class="xlarge-ht-diff"></div>
                    <div class="xlarge-ht-diff"></div>
                </div>
                @if($document_info->signature_status == '0')
                <div class="sign_tool_visual">
                    <a href="#" class="up_btn"><i class="fa fa-chevron-up"></i></a>

                    <div class="sign-tool-main">
                    @if(isset($signature_array) && !empty($signature_array))
                        @foreach($signature_array as $key=>$signature)
                            @foreach($signature as $key_inner=>$sign)
                                <div tmp="701283{{$key}}" class="sign_tool_disp sign_tl_icon cust-fa-{{$sign['field_type']}}" id="sign_visual_{{$sign['div_id']}}">
                                    @php 
                                        if($sign['field_type'] == 'signdate'){
                                            $icon = "fa fa-calendar";
                                        }else if($sign['field_type'] == 'textbox'){
                                            $icon = "fa fa-font";
                                        }else{
                                            $icon = "fa fa-pencil";
                                        }
                                    @endphp
                                    <i class="{{$icon}}" title="{{$sign['field_type']}}"></i>
                                    
                                    <span>{{$sign['field_type']}}</span>
                                    <!--                    <img class="" title="fa fa-pencil" src="/themes/frontend-theme/img/icons/sign_tool_50/fa fa-pencil.png" id="sign_visual_312371507_1" />-->
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                    </div>
                    <a href="#" class="down_btn"><i class="fa fa-chevron-down"></i> </a>
                </div>
                @endif
            </div>
        </div>
       
        <div class="row-fluid">
            <div id="sign_footer">
            
                <div class="footer_content agree_terms_cont">
                    <div class="container">
                        <div class="inner_sign_ft">
                            @if($document_info->signature_status == '0')
                            <p class="hide" id="agree_sign_error"></p>

                            <p>I Agree To Be Legally Bound By This Document And The <a href="/terms-and-conditions.htm" target="_blank">eSignly Terms Of Service</a>.</p>
                            <div class="agree-btn-signing">

                                &nbsp;


                                <input type="button" class="btn btn-primary" value="I agree" disabled="disabled" id="iAgreeButton">


                                <div class="agree-info-bubble pull-right hide">
                                    Click Here To Proceed </div>
                            </div>

                            <div id="img_info_div" class="upload-image-div" style="display:none;">
                                <p id="user_doc_name"></p>
                                <p class="del_doc"><a data-key="" id="user_doc_delete" href="javaScript:void(0)"><i class="fa fa-times" aria-hidden="true"></i></a> </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
       
    </div>
    @if($document_info->signature_status == '0')
    <div id="tool_sign_tmp" class="custom_template">
        <div class="tool_comp_main draggable component comp_customform" id="tool_component_${compCount}">
            <div class="handle-wrapper">
                <div class="wrapper interactive">
                    <div class="ie_rgba_fix"></div>
                    <div class="dummyimage"></div>
                    <p data-c="${compCount}" class="description text-center" title="${compTitle}" style="line-height: 30px; height: 30px;">${compText}</p>
                </div>
            </div>
            <div data-count="${compCount}" class="remove"></div>
        </div>
    </div>

    <div id="tool_rem_icon_tmp" class="custom_template">
        <div data-count="${compCount}" class="remove"></div>
    </div>
       


    <!-- Modal -->
    <div class="fullmodal modal fade" id="add_sign_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_frame" data-dismiss="modal" aria-hidden="true">&times;</button>

            </div>
            <div class="modal-body">
                Loading...... </div>

        </div>
        <!-- /.modal-content -->

    </div><!-- /.modal -->

    <!-- Modal -->
    <div class="modal fade" id="request_loader_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Please Wait <div class="progress progress-striped active" style="height: 24px;">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            <span class="sr-only">100%</span>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>

    </div><!-- /.modal -->

    <script>

    </script>
   
    <div class="container">
        <hr>
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Company 2013</p>
                </div>
            </div>
        </footer>
    </div>
    @endif
    <div class="cust_alert text-muted" id="cust_alert">


    </div>
    <!--    Start of Js Files -->
    @if($document_info->signature_status == '1')
    <script>
    $(function () {
        $("footer").parent().remove();

        var height = ($(window).height() - 110);
        $(".fixed_doc_container").height(height);
        $(".sign-progress-bar").remove();
        $(".fixed_doc_container .dyna-alert").css({'margin-top': (height / 2) - 100, 'font-size': '30px'});
    });
    </script>
    @endif
    <script src="{{URL::asset('esign_example/frontend-theme/js/sign_doc_page.min.js')}}"></script>

    <script src="{{URL::asset('esign_example/frontend-theme/js/custom/common_functions.js')}}"></script>

    <script src="{{URL::asset('main-site/js/sign_doc_page1.min.js')}}"></script>

    <div class="cust_alert text-muted" id="cust_alert"></div>
    @if($document_info->signature_status == '0')
    <script type="text/javascript" src="{{URL::asset('main-site/js/custom/jquery.animate-shadow.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('esign_example/js/jquery.tmpl.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('main-site/js/custom/request/prepareDoc.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('main-site/js/custom/request/request_functions.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('esign_example/frontend-theme/js/custom/jquery-ui.min.js')}}"></script>
    @endif
</body>

</html>