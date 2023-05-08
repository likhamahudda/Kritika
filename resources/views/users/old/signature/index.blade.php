<html style="" class=" js no-touch csstransforms csstransitions">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">
    <link href="https://fonts.googleapis.com/css?family=Aclonica:regular" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('esign_example/frontend-theme/css/custom.css')}}" rel="stylesheet">
    <link href="{{URL::asset('esign_example/frontend-theme/css/custom-common.css')}}" rel="stylesheet">
    <link href="{{URL::asset('esign_example/frontend-theme/css/sign_box_iframe.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('esign_example/frontend-theme/css/styles.css')}}">
    <script src="{{URL::asset('esign_example/frontend-theme/js/jquery.js')}}"></script>
    <script src="{{URL::asset('esign_example/frontend-theme/js/modernizr.custom.17475.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('esign_example/frontend-theme/css/signature.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('esign_example/frontend-theme/css/fupload_style.css')}}">

    <title>{{$title ?? ''}} | {{Constant::APP_NAME}} </title>
    <script>
        var SITE_URL = "{{url('/').'/'}}";
        var THEME_BASE = "/themes/frontend-theme";
        var prepare_doc_sel_show = "role_name";
        var FRONT_URL = "https://www.esignly.com/";
    </script>
</head>

<body style="">
    <!-- section content -->
    <section class="section">
        <div class="row-fluid">
            <!-- span content -->
            <div class="span12">
                <!-- content-body -->
                <div class="content-body">

                    <script>
                        var sign_initial = "{{$type}}";
                        var set_back = "0";
                        var user_key = "{{$user_id}}"; // Add user id
                    </script>
                    <style>
                        /*    .insert_both{margin-top: 5px;}*/
                    </style>
                    @php
                    $signature_cls = (($type == '1') ? "" : "hide");
                    $initials_cls = (($type == '2') ? "" : "hide");
                    $insert_sign_cls = (($version == '1') ? "" : "hide")
                    @endphp
                    <div class="pop-hold" id="signature-area">
                        <div class="sign-left-panel">
                            <!--<h5 class="pop-head"><span class="signature">Signature</span> <span class="hide initial">Initial</span> Options
        </h5>-->
                            <ul class="nav nav-tabs sign-control-item">
                                <li class="active"><a href="#existing_sign" data-toggle="tab" title="Existing signatures">
                                        <i class="icon-pencil"></i>
                                        Existing <span class="signature {{$signature_cls}}">signatures</span> <span class="{{$initials_cls}} initial">initials</span></a>
                                </li>
                                <li><a href="#draw_it_in" data-toggle="tab" title="Draw it in">
                                        <i class="icon-dashboard"></i>
                                        Draw it in</a></li>
                                <li><a href="#type_in_sign" data-toggle="tab" title="Type in signature">
                                        <i class="icon-font"></i>
                                        Type in <span class="signature {{$signature_cls}}">signature</span><span class="{{$initials_cls}} initial">initials</span></a></li>
                                <li><a href="#upload_image_file" data-toggle="tab" title="Upload image file">
                                        <i class="icon-upload-alt"></i>
                                        Upload image file</a></li>
                                <li><a href="javascript:void(0)"><input type="checkbox" name="copy_all" value="1" id="copy_at_all_place">
                                        Copy same sign at all places</a></li>
                            </ul>
                        </div>
                        <div class="sign-right-panel">
                            <div class="sidebar-right-content">
                                <div class="tab-content tab_relative">
                                    <div class="tab-pane fade active in" id="existing_sign">

                                        <div class="text-center">
                                            @if(!isset($signatures) || count($signatures) == '0')
                                            <div class="nosigncontent">
                                                <h4 class="no_signatures" style="display: none;">You have not yet saved <span class="signature">a signature</span> <span class="hide initial">any initials</span>.
                                                </h4>

                                                <p class="no_signatures" style="display: none;">Please select an option from the left panel
                                                    to create <span class="signature {{$signature_cls}}">a signature</span> <span class="{{$initials_cls}} initial">your initials</span>.<br><br>Then You
                                                    can save the <span class="signature {{$signature_cls}}">signature</span> <span class="{{$initials_cls}} initial">initials</span>
                                                    for later use.</p>

                                            </div>
                                            @else
                                            <div id="signatures_load" class="displaynone sign-canvas" style="display: block;">
                                                <h2 class="sign_heading">Existing <span class="signature {{$signature_cls}}">Signatures</span> <span class="{{$initials_cls}} initial">Initials</span></h2>
                                            </div>
                                            <div id="slider_sign">
                                                <ul class="slides">
                                                    <li>
                                                        <img src="{{url('uploads/signature')}}/{{(isset($signatures[0]->signature_image) ? $signatures[0]->signature_image : '')}}" data-id="{{(isset($signatures[0]->signature_image) ? $signatures[0]->signature_image : '')}}" style="max-height: 125px; max-width: 300px;">
                                                    </li>

                                                </ul>
                                            </div>
                                            <div class="sig_slider">
                                                <div class="elastislide-wrapper elastislide-horizontal">
                                                    <div class="elastislide-carousel">
                                                        <ul id="slider_sign_carousel" class="elastislide-list" style="display: block; max-height: 100px; transition: all 500ms ease-in-out 0s;">
                                                            @foreach($signatures as $key => $sign)
                                                            <li class="slide slide_cc_{{$sign->id}}{{$key}}" style="width: 100%; max-width: 220.172px; max-height: 100px;">
                                                                <a>
                                                                    <img class="main_img" data-id="{{$sign->signature_image}}" src="{{url('uploads/signature')}}/{{$sign->signature_image}}">
                                                                </a>
                                                                <i class="icon-remove remove_sign" src="{{url('themes/frontend-theme/img/icons/remove-r.png')}}" data-id="{{$sign->id}}"></i>

                                                            </li>
                                                            
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <nav><span class="elastislide-prev" style="display: none;">Previous</span><span class="elastislide-next" style="display: none;">Next</span></nav>
                                                </div>
                                            </div>
                                            @endif
                                            @if($version == '1')
                                            <div class="btn-tools">
                                                <button class="btn btn-primary {{$signature_cls}} insertsignature pull-right" id="insert_signature">Insert
                                                    Signature
                                                </button>
                                                <button class="btn btn-info {{$initials_cls}} insertinitial pull-right" id="insert_initial">Insert
                                                    Initial
                                                </button>
                                            </div>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="tab-pane fade in" id="draw_it_in">
                                        <div class="wrap-center sign-canvas">
                                            <h2 class="sign_heading">Sign Below</h2>

                                            <div class="sign_cont_hold" id="signature-pad">
                                                <canvas id="canvas-display" width="500" height="150"></canvas>
                                            </div>
                                            <div class="row-fluid">
                                                <div align="center" class="canvas_fixed_buttons wrap-center">
                                                    <button class="btn btn-primary " id="save_sign_canvas">Add <span class="signature {{$signature_cls}}">Signature</span><span class="{{$initials_cls}} initial">Initial</span></button>
                                                    <button class="btn btn-default " id="clear_sign_canvas">Clear</button>
                                                    <button class="btn btn-info hide insert_both " id="insert_save_sign_canvas">Save &amp;
                                                        Insert
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="in fade tab-pane" id="type_in_sign">
                                        <h2 class="sign_heading">Type in signature</h2>
                                        <form class="form-horizontal" id="type_in_sign_form">
                                            <div id="error_text_add" class="displaynonehard alert alert-danger span8">You must type your
                                                <span class="signature {{$signature_cls}}">signature</span> <span class="{{$initials_cls}} initial">initial</span>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="wrap-center form_signature">
                                                <!--<div class="control-group span12">
                                            <label for="" class="control-label">First Name:</label>
                                            <div class="controls">
                                                <input type="text" placeholder="First Name" value="" name="firstname" id="ajaxSaveUser_7216522931" class="span12 update_data form-control" data-submit-t="firstName" data-submit="firstname">
                                            </div>
                                        </div>-->
                                                <div class="control-group">
                                                    <label class="control-label" for="txtSignName">Type in your <span class="signature {{$signature_cls}}">signature</span>
                                                        <span class="{{$initials_cls}} initial">initial</span></label>
                                                    <div class="controls">
                                                        <input maxlength="60" type="text" id="txtSignName" name="txtSignName" placeholder="Signature" value="{{ (isset($type_in_signatures->sign_name) && !empty($type_in_signatures->sign_name) ? $type_in_signatures->sign_name : '') }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label" for="selFont">Change Font</label>

                                                    <div class="controls">
                                                        <select id="selFont" name="selFont">
                                                            <option {{ (isset($type_in_signatures->font) && $type_in_signatures->font == 'HerrVonMuellerhoffRegular' ? 'selected' : '') }} value="HerrVonMuellerhoffRegular">Herr Von Muellerhoff</option>
                                                            <option {{ (isset($type_in_signatures->font) && $type_in_signatures->font == 'Windsong' ? 'selected' : '') }} value="Windsong">Windsong</option>
                                                            <option {{ (isset($type_in_signatures->font) && $type_in_signatures->font == 'Carpenter' ? 'selected' : '') }} value="Carpenter">Carpenter</option>
                                                            <option {{ (isset($type_in_signatures->font) && $type_in_signatures->font == 'archieshand-regular' ? 'selected' : '') }} value="archieshand-regular">ArchiesHand-Regular</option>
                                                            <option {{ (isset($type_in_signatures->font) && $type_in_signatures->font == 'HoneyScript-SemiBold' ? 'selected' : '') }} value="HoneyScript-SemiBold">HoneyScript-SemiBold</option>
                                                            <option {{ (isset($type_in_signatures->font) && $type_in_signatures->font == 'MrDeHavilandRegular' ? 'selected' : '') }} value="MrDeHavilandRegular">Satisfaction</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                            <div class="control-group sig_text">
                                                <div class="type-txt-prv-cc">
                                                    <div class="text-center" style="line-height: 100px; font-size: 70px;font-family: {{ (isset($type_in_signatures->font) && !empty($type_in_signatures->font) ? $type_in_signatures->font : '') }}" id="sign_prev_txt">{{ (isset($type_in_signatures->sign_name) && !empty($type_in_signatures->sign_name) ? $type_in_signatures->sign_name : '') }}</div>
                                                    <canvas class="pad" width="520" id="hdn_sign_prev_txt" height="120" style="font-family: {{ (isset($type_in_signatures->font) && !empty($type_in_signatures->font) ? $type_in_signatures->font : '') }}"></canvas>
                                                </div>
                                            </div>
                                            <div class="wrap-center add_sig">
                                                <div class="control-group">
                                                    <div class=" ">
                                                        <button id="add_text_sign" type="submit" class="btn btn-primary">Add <span class="signature {{$signature_cls}}">signature</span> <span class="{{$initials_cls}} initial">initial</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="tab-pane fade in" id="upload_image_file">
                                        <h2 class="sign_heading">Sign Below</h2>
                                        <div class="upload_crop_top_nav displaynonehard">
                                            <div class="span6 li_upload_pane active">Upload</div>
                                            <div class="span6 li_crop_pane">Crop &amp; Save</div>
                                        </div>
                                        <form id="upload_sign" class="span7 upload_sign_from" method="post" action="upload.php" enctype="multipart/form-data">
                                            <div class="large-ht-diff"></div>
                                            <div class="span4 btn btn-lar btn-primary btn_sign_upload">
                                                <span>Select File</span>
                                                <input type="file" name="sign_up_file" id="sign_up_file" accept="image/png, image/jpg, image/jpeg">
                                            </div>

                                            <div align="center" class="file-sz">Max file size 1 MB</div>
                                            <div class="small-ht-diff"></div>
                                            <ul class="border wrap-center upld_status" id="uplstatus">

                                            </ul>
                                            <div class="small-ht-diff"></div>
                                        </form>
                                        <div class="row-fluid">
                                            <div class="crop_rotate displaynonehard span10">
                                                <img width="" height="" src="" id="crop_sign_img" alt="Jcrop Image" accept="image/png, image/jpg, image/jpeg">

                                                <div>To crop your <span class="signature {{$signature_cls}}">signature</span> <span class="{{$initials_cls}} initial">initial</span>,
                                                    click and drag to create a bounding box.
                                                </div>
                                                <div class="small-ht-diff"></div>
                                                <div class="row-fluid">
                                                    <div class="span12 reload_icon">
                                                        <img class="sign-rot-left" src="/themes/frontend-theme/img/icons/rot-left.png">
                                                        <img class="sign-rot-right" src="/themes/frontend-theme/img/icons/rot-right.png">
                                                    </div>
                                                    <a class="btn btn-default bl_link" href="#" id="back_to_upload">Cancel</a>
                                                    <button class="btn btn-info" id="save_sign_crop">Save</button>
                                                </div>
                                                <div class="small-ht-diff"></div>
                                                <div class="displaynone">
                                                    <div class="span8">Rotate <span class="signature">Signature</span> <span class="hide initial">Initial</span></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="loading_fade row-fluid displaynonehard">
                                        <div class="loader_content span12" id="saving">Loading..</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                </div>
                <!--/content-body -->

            </div>
            <!-- /span content -->

        </div>
        <!--/row-->
    </section>
    <!--/section content-->
    <!--    Start of Js Files -->
    <script src="{{URL::asset('esign_example/frontend-theme/js/sign_box_iframe_page.min.js')}}"></script>

    <script src="{{URL::asset('esign_example/frontend-theme/js/bootbox.min.js')}}"></script>
    <script src="{{URL::asset('esign_example/frontend-theme/js/custom/signature_pad.min.js')}}"></script>
    <script>
        var mobile_device = 1;
        var wrapper = document.getElementById("signature-pad"),
            clearButton = document.getElementById("clear_sign_canvas"),
            canvas = wrapper.querySelector("canvas"),
            signaturePad;

        signaturePad = new SignaturePad(canvas);

        clearButton.addEventListener("click", function(event) {
            event.preventDefault();
            signaturePad.clear();
        });
    </script>
    <script src="{{URL::asset('esign_example/frontend-theme/js/custom/signature_functions.js')}}" type="text/javascript"></script>
    <script defer="" src="{{URL::asset('esign_example/frontend-theme/js/jquerypp.custom.js')}}"></script>
    <script defer="" src="{{URL::asset('esign_example/frontend-theme/js/jquery.elastislide.js')}}"></script>
    <!-- End of JS Files -->
    <script type="text/javascript" src="{{URL::asset('esign_example/frontend-theme/js/js-upload/jquery.iframe-transport.js')}}"></script>

    <div id="cboxOverlay" style="display: none;"></div>
    <div id="colorbox" class="" role="dialog" tabindex="-1" style="display: none;">
        <div id="cboxWrapper">
            <div>
                <div id="cboxTopLeft" style="float: left;"></div>
                <div id="cboxTopCenter" style="float: left;"></div>
                <div id="cboxTopRight" style="float: left;"></div>
            </div>
            <div style="clear: left;">
                <div id="cboxMiddleLeft" style="float: left;"></div>
                <div id="cboxContent" style="float: left;">
                    <div id="cboxTitle" style="float: left;"></div>
                    <div id="cboxCurrent" style="float: left;"></div><button type="button" id="cboxPrevious"></button><button type="button" id="cboxNext"></button><button id="cboxSlideshow"></button>
                    <div id="cboxLoadingOverlay" style="float: left;"></div>
                    <div id="cboxLoadingGraphic" style="float: left;"></div>
                </div>
                <div id="cboxMiddleRight" style="float: left;"></div>
            </div>
            <div style="clear: left;">
                <div id="cboxBottomLeft" style="float: left;"></div>
                <div id="cboxBottomCenter" style="float: left;"></div>
                <div id="cboxBottomRight" style="float: left;"></div>
            </div>
        </div>
        <div style="position: absolute; width: 9999px; visibility: hidden; display: none;"></div>
    </div>
</body>

</html>