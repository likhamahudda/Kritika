<style>
    .docs-listing {
        height: 299px;
    }

    .template_modal_body {
        max-height: 100%;
        min-height: 600px;
    }

    .tab-content {
        border: 1px solid #ddd;
    }
</style>
<div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#myTempTab" aria-controls="myTempTab" role="tab" data-toggle="tab" class="sys_temp_tab" data-type="1">My purchased templates</a>
        </li>
        <li role="presentation"><a href="#sysFreetempTab" aria-controls="sysFreetempTab" role="tab" data-toggle="tab" class="sys_temp_tab" data-type="2">Free templates</a>
        </li>
        <li role="presentation" class="hidepricingios"><a href="#sysPaidtempTab" aria-controls="sysPaidtempTab" role="tab" data-toggle="tab" class="sys_temp_tab" data-type="3">Paid templates</a>

        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="myTempTab">
            <div class="row-fluid">
                <div class="span2">
                    <select name="temp_type" id="select_temp_type" class="search_sys_temp_cat">
                        <option value="">Select category</option>
                        @if(isset($template_category) && !empty($template_category))
                        @foreach($template_category as $t_category)
                        <option value="{{$t_category->id}}">{{$t_category->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="span2">
                    <!--  	<a class="search_clear clear-text"><i class="icon-remove"></i> Clear search</a> -->
                </div>

            </div>
            <div id="my_paid_temp_search_category">
                <div class="box-body doc_listing">
                    <ul class="doclist_ul span12">

                        <li class="span12">
                            <div class="text-center alert alert-success">No Records found.</div>
                        </li>
                    </ul>
                    <div class="pagination">
                    </div>
                </div>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="sysFreetempTab">
            <div class="row-fluid">
                <div class="span2">
                    <select name="temp_type" id="select_temp_type" class="search_sys_temp_cat">
                        <option value="">Select category</option>
                        @if(isset($template_category) && !empty($template_category))
                        @foreach($template_category as $t_category)
                        <option value="{{$t_category->id}}">{{$t_category->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="span2">
                    <!--  	<a class="search_clear clear-text"><i class="icon-remove"></i> Clear search</a> -->
                </div>

            </div>

            <div id="free_temp_search_category">
                <div class="box-body doc_listing">
                    <ul class="doclist_ul span12">
                        @if(isset($free_templats) && !empty($free_templats))
                        @foreach($free_templats as $f_templats)
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

                                </span>


                                <div class="select-template-button">

                                    <a class="template-sign-doc-link" href="javaScript:void(0)" id="selectfreesystemp_{{$f_templats->id}}" data-doc-name="{{$f_templats->file}}"> Use it</a>

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
                    @if($total_free_template > $limit)
                    <!--  hide / show previous button of pagination --> 
                    @php
                        $hide_previous = '';
                    @endphp
                    @if($page_no == '1')
                        @php
                            $hide_previous = 'hidden';
                        @endphp
                    @endif

                    
                    <div class="pagination">
                        <ul class="run_time_ajax_pagination_li" id="free_temp_search_category" type="2" page_url="user_templates/ajaxPaginationTmpllist'}}">
                            <li class="first {{$hide_previous}}"><a id="yt&amp;lt;&amp;lt; First" data-type="2" data-div="free_temp_search_category" data-page-no="1" data-page-url="user_templates/ajaxPaginationTmpllist?page=1" href="#">&lt;&lt; First</a></li>
                            <li class="previous {{$hide_previous}}"><a id="yt&amp;lt; Previous" data-type="2" data-div="free_temp_search_category" data-page-no="{{$page_no - 1}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$page_no - 1}}" href="#">&lt; Previous</a></li>
                            @php
                            $total_free_template_pages = intval($total_free_template / $limit);
                            $free_template_reminder = fmod($total_free_template, $limit); // get reminder
                            @endphp
                            @if($free_template_reminder > 0)
                                @php
                                $total_free_template_pages = $total_free_template_pages + 1;
                                @endphp
                            @endif
                            @for($i=1;$i<=$total_free_template_pages;$i++)
                                <li class="page {{($i== $page_no ? 'selected' : '')}}"><a id="yt{{$i}}" data-type="2" data-div="free_temp_search_category" data-page-no="{{$i}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$i}}" href="#">{{$i}}</a></li>
                            @endfor

                            <!--  hide / show next and last button of pagination --> 
                            @php
                                $hide_next = '';
                            @endphp
                            @if($page_no == $total_free_template_pages)
                                @php
                                $hide_next = 'hidden';
                                @endphp
                            @endif
                            <li class="next {{$hide_next}}"><a id="ytNext &amp;gt;" data-type="2" data-div="free_temp_search_category" data-page-no="{{$page_no + 1}}" data-page-url="user_templates/ajaxPaginationTmpllist?page=2" href="#">Next &gt;</a></li>
                            <li class="last {{$hide_next}}"><a id="ytLast &amp;gt;&amp;gt;" data-type="2" data-div="free_temp_search_category" data-page-no="{{$total_free_template_pages}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$total_free_template_pages}}" href="#">Last &gt;&gt;</a></li>
                        </ul>
                    </div>
                    @endif

                </div>

            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="sysPaidtempTab">
            <div class="row-fluid">
                <div class="span2">
                    <select name="temp_type" id="select_temp_type" class="search_sys_temp_cat">
                        <option value="">Select category</option>
                        @if(isset($template_category) && !empty($template_category))
                        @foreach($template_category as $t_category)
                        <option value="{{$t_category->id}}">{{$t_category->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="span2">
                    <!--  	<a class="search_clear clear-text"><i class="icon-remove"></i> Clear search</a> -->
                </div>

            </div>
            <div id="paid_temp_search_category">
                <div class="box-body doc_listing">
                    <ul class="doclist_ul span12">
                        @if(isset($paid_templats) && !empty($paid_templats))
                        @foreach($paid_templats as $p_templats)
                        <li id="assignli_" class="docs-listing">
                            <div class="doc-listing-hold">
                                <div class="doc-image">
                                    <a href="javascript:void(0)">
                                        <div id="imagedone{{$p_templats->id}}" class="">
                                            <img data-src="js/holder.js/300x200" class="img-rounded" alt="userdoc" src="{{url('/').'/uploads/template_images/'.$p_templats->id.'-template-1.jpeg'}}">
                                        </div>
                                    </a>

                                    <div id="overlaydiv{{$p_templats->id}}" class="overlay">
                                        <a data-page="{{$p_templats->total_pages}}" data-name="{{$p_templats->id}}-template" data-orname="{{$p_templats->id}}-template" data-delno="" data-ukey="" href="javascript:void(0)" class="btnpreview_sys_template doc_view_thumb">
                                            <i class="icon-zoom-in zoom-icon-pop"></i>
                                        </a>
                                    </div>
                                    <a href="javascript:void(0)" id="btnsystempdetails_{{$p_templats->id}}" class="asmnt-details-clk1" data-type="2"> <i class="icon-zoom-in"></i> Document details </a>
                                </div>

                                <p class="main-doc-name" id="assignname">
                                {{$p_templats->title}} </p>
                                <span class="docs-name"><i class="icon-copy"></i> {{$p_templats->total_pages}} Pages
                                    <p class="pull-right temp_price">
                                        $ {{$p_templats->price}} </p>

                                </span>

                                <div class="select-template-button">


                                    <a class="template-buy-now" href="javaScript:void(0)" data-temp-key="{{$p_templats->id}}" data-doc-name="General contract for services"> Buy Now</a>

                                </div>

                            </div>

                        </li>
                        @endforeach
                        @else
                        <li class="span12">
                            <div class="text-center alert alert-success">No Records found.</div>
                        </li>
                        @endif
                    </ul>
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

                    
                    <div class="pagination">
                        <ul class="run_time_ajax_pagination_li" id="paid_temp_search_category" type="3" page_url="user_templates/ajaxPaginationTmpllist'}}">
                            <li class="first {{$hide_previous}}"><a id="yt&amp;lt;&amp;lt; First" data-type="3" data-div="paid_temp_search_category" data-page-no="1" data-page-url="user_templates/ajaxPaginationTmpllist?page=1" href="#">&lt;&lt; First</a></li>
                            <li class="previous {{$hide_previous}}"><a id="yt&amp;lt; Previous" data-type="3" data-div="paid_temp_search_category" data-page-no="{{$page_no - 1}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$page_no - 1}}" href="#">&lt; Previous</a></li>
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
                                <li class="page {{($i== $page_no ? 'selected' : '')}}"><a id="yt{{$i}}" data-type="3" data-div="paid_temp_search_category" data-page-no="{{$i}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$i}}" href="#">{{$i}}</a></li>
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
                            <li class="next {{$hide_next}}"><a id="ytNext &amp;gt;" data-type="3" data-div="paid_temp_search_category" data-page-no="{{$page_no + 1}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$page_no + 1}}" href="#">Next &gt;</a></li>
                            <li class="last {{$hide_next}}"><a id="ytLast &amp;gt;&amp;gt;" data-type="3" data-div="paid_temp_search_category" data-page-no="{{$total_paid_template_pages}}" data-page-url="user_templates/ajaxPaginationTmpllist?page={{$total_paid_template_pages}}" href="#">Last &gt;&gt;</a></li>
                        </ul>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

