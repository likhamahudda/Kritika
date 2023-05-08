<div class="box-body doc_listing">
    <ul class="doclist_ul span12">
        @if(isset($templats) && count($templats) > 0)
        @foreach($templats as $f_templats)
        <li id="assignli_" class="docs-listing">
            <div class="doc-listing-hold">
                <div class="doc-image">
                    <a href="javascript:void(0)">
                        <div id="imagedone{{$f_templats->id}}" class="">
                            <img data-src="js/holder.js/300x200" class="img-rounded" alt="userdoc" src="{{url('/').'/uploads/template_images/'.$f_templats->id.'-template-1.jpeg'}}">
                        </div>

                    </a>

                    <div id="overlaydiv{{$f_templats->id}}" class="overlay">
                        <a data-page="{{$f_templats->total_pages}}" data-name="{{$f_templats->id}}-template" data-orname="{{$f_templats->id}}-template" data-delno="" data-ukey="" href="javascript:void(0)" class="btnpreview_sys_template doc_view_thumb">
                            <i class="icon-zoom-in zoom-icon-pop"></i>
                        </a>
                    </div>
                    <a href="javascript:void(0)" id="btnsystempdetails_{{$f_templats->id}}" class="asmnt-details-clk1" data-type="1"> <i class="icon-zoom-in"></i> Document details </a>
                </div>

                <p class="main-doc-name">{{$f_templats->title}}</p>

                <span class="docs-name"><i class="icon-copy"></i> {{$f_templats->total_pages}} Pages
                @if($template_type == '3')
                <p class="pull-right temp_price">$ {{$f_templats->price}} </p>
                @endif
                </span>


                <div class="select-template-button">
                    @if($template_type == '2')
                        <a class="template-sign-doc-link" href="javaScript:void(0)" id="selectfreesystemp_{{$f_templats->id}}" data-doc-name="{{$f_templats->file}}"> Use it</a>
                    @else
                        <a class="template-buy-now" href="javaScript:void(0)" data-temp-key="{{$f_templats->id}}" data-doc-name="{{$f_templats->file}}"> Buy Now</a>
                    @endif
                </div>

                <div class="clearfix"></div>
            </div>
        </li>
        @endforeach
        @else
        <li class="span12">
            <div class="text-center alert alert-success">No Records found.</div>
        </li>
        @endif
    </ul>
    <!--/three column-->
    @if($total_paid_template > $limit)
    <!--  hide / show previous button of pagination --> 
    @php
        $hide_previous = '';
    @endphp
    @if($page_no == '1')
        @php
            $hide_previous = 'hidden';
        @endphp
    @endif

    <!-- template type condition -->
    @if($template_type == '1')
        @php
            $data_div = "my_paid_temp_search_category";
            $data_type = "1";
        @endphp
    @elseif($template_type == '2')
        @php
            $data_div = "free_temp_search_category";
            $data_type = "2";
        @endphp
    @else
        @php
            $data_div = "paid_temp_search_category";
            $data_type = "3";
        @endphp
    @endif

    <div class="pagination">
        <ul class="run_time_ajax_pagination_li" id="{{$data_div}}" type="{{$data_type}}" page_url="user_templates/ajaxPaginationTmpllist">
            <li class="first {{$hide_previous}}"><a id="yt&amp;lt;&amp;lt; First" data-type="{{$data_div}}" data-div="{{$data_div}}" data-page-no="1" data-page-url="user_templates/ajaxPaginationTmpllist?page=1" href="#">&lt;&lt; First</a></li>
            <li class="previous {{$hide_previous}}"><a id="yt&amp;lt; Previous" data-type="{{$data_div}}" data-div="{{$data_div}}" data-page-no="{{$page_no - 1}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$page_no - 1}}" href="#">&lt; Previous</a></li>
            @php
                $total_paid_template_pages = intval($total_paid_template / $limit);
                $paid_template_reminder = fmod($total_paid_template, $limit); // get reminder
            @endphp

            @if($paid_template_reminder > 0)
                @php
                    $total_paid_template_pages = $total_paid_template_pages + 1;
                @endphp
            @endif

            @for($i=1;$i<=$total_paid_template_pages;$i++) 
                <li class="page {{($i== $page_no ? 'selected' : '')}}"><a id="yt{{$i}}" data-type="{{$data_div}}" data-div="{{$data_div}}" data-page-no="{{$i}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$i}}" href="#">{{$i}}</a></li>
            @endfor
             <!--  hide / show next and last button of pagination --> 
            @php
                $hide_next = '';
            @endphp

            @if($page_no == $total_paid_template_pages)
                @php
                    $hide_next = 'hidden';
                @endphp
            @endif

                <!-- <li class="page selected"><a id="yt1" data-type="1" data-div="paid_temp_search_category" data-page-no="1" data-page-url="/templates/ajaxPaginationTmpllist?page=1" href="#">1</a></li> -->
                <li class="next {{$hide_next}}"><a id="ytNext &amp;gt;" data-type="{{$data_div}}" data-div="{{$data_div}}" data-page-no="{{$page_no + 1}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$page_no + 1}}" href="#">Next &gt;</a></li>
                <li class="last {{$hide_next}}"><a id="ytLast &amp;gt;&amp;gt;" data-type="{{$data_div}}" data-div="{{$data_div}}" data-page-no="{{$total_paid_template_pages}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$total_paid_template_pages}}" href="#">Last &gt;&gt;</a></li>
        </ul>
    </div>
    @endif

</div>