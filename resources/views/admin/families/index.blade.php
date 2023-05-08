@extends('layout.admin')
@section('content')
@push('css')
<link rel="stylesheet" href="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.css')}}" />

@endpush

<div class="d-flex justify-content-between">
<h5 class="page_heading">{{__('Families List')}}  </h5>
<div>
     
    <div class="ms-auto d-flex align-items-center"> 
        <div class="filter-option-heading">
            <a class="filter-show-a" href="javascript:"><svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.79 1.61564C12.3029 0.959102 11.8351 0 11.002 0H1.00186C0.168707 0 -0.299092 0.959101 0.213831 1.61564L5.03983 7.72867C5.17719 7.90449 5.25181 8.1212 5.25181 8.34432V13.7961C5.25181 13.9743 5.46724 14.0635 5.59323 13.9375L6.60536 12.9254C6.69913 12.8316 6.75181 12.7044 6.75181 12.5718V8.34432C6.75181 8.1212 6.82643 7.90449 6.96379 7.72867L11.79 1.61564Z" fill="#464F60"/>
            </svg>
            </a>
            <a class="filter-hide-to"  style="display:none;" href="javascript:"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_213_700)">
                <path d="M10.8625 11.9249C11.1567 11.942 11.446 11.8442 11.6695 11.6521C12.1102 11.2087 12.1102 10.4927 11.6695 10.0494L2.01934 0.399214C1.56096 -0.0297067 0.84169 -0.00586323 0.412769 0.452515C0.0249107 0.867025 0.00230242 1.50414 0.359842 1.94507L10.0668 11.6521C10.2874 11.8414 10.5721 11.939 10.8625 11.9249V11.9249Z" fill="#464F60"/>
                <path d="M1.22373 11.9248C1.5219 11.9236 1.80768 11.8052 2.01939 11.5952L11.6696 1.94503C12.0779 1.46823 12.0223 0.750684 11.5455 0.342351C11.12 -0.022076 10.4924 -0.022076 10.0669 0.342351L0.359896 9.99252C-0.0983706 10.4215 -0.122064 11.1409 0.306968 11.5991C0.324037 11.6174 0.341667 11.635 0.359896 11.6521C0.597582 11.8587 0.910391 11.9575 1.22373 11.9248V11.9248Z" fill="#464F60"/>
                </g>
                <defs>
                <clipPath id="clip0_213_700">
                <rect width="12" height="12" fill="white" transform="matrix(-1 0 0 1 12 0)"/>
                </clipPath>
                </defs>
                </svg>
            </a>
        </div>
        {!! addAction(route($page.'.add') , 'Add New Families' ) !!}
    </div>
    <!-- <button class="btn btn-outline-primary export_csv">Export in CSV</button> -->
</div> 
</div>
<hr class="page_row">
<div class="card">
    <div class="card-body">


    <div class="row search-filters filter-option-content" id="step1" style="margin-bottom: 15px; display:none;">

        <div class="col-sm-2">                            
        <select required name="country_id" class="form-control country_id">
            <option value="">Select Country</option>
            @foreach($data_country as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            </select>
        </div> 

        <div class="col-sm-2">                           
        <select required name="state_id" class="form-control state_id">
            <option value="">Select State</option>
            @foreach($data_state as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            </select>
        </div>

        <div class="col-sm-2">                            
        <select required name="district_id" class="form-control district_id">
            <option value="">Select District</option>
            @foreach($data_districts as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            </select>
        </div>

        <div class="col-sm-2">                            
        <select required name="tehsil_id" class="form-control tehsil_id">
            <option value="">Select Tehsils</option>
            @foreach($data_tehsils as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            </select>
        </div>

        <div class="col-sm-2">                             
        <select required name="panchayat_id" class="form-control panchayat_id">
            <option value="">Select Panchayat</option>
            @foreach($data_panchayat as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            </select>
        </div>

        <div class="col-sm-2">                            
        <select required name="village_id" class="form-control village_id">
            <option value="">Select Village</option>
            @foreach($data_village as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            </select>
        </div>

        <div class="col-sm-2">                            
        <select required name="gender" class="form-control gender">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option> 
            </select>
        </div>

        <div class="col-sm-2">                            
        <select required name="kulrishi" class="form-control kulrishi">
            <option value="">Select Kulrishi</option>
            <option selected="selected" value="">Select Kulrishi</option>
            <option value="Uddalak Ji">Uddalak Ji (उद्दालक जी)</option>
            <option value="Vasishtha Ji">Vasishtha Ji (वशिष्ठ जी)</option>
            <option value="Kashyapa Ji">Kashyapa Ji (कश्यप जी)</option>
            <option value="Parasara Ji">Parasara Ji (पारासर जी)</option>
            <option value="Piplad Ji">Piplad Ji (पिप्लाद जी)</option>
            <option value="Shandilya Ji">Shandilya Ji (शांडिल्य जी)</option>
            <option value="Bharadwaj Ji">Bharadwaj Ji (भारद्वाज जी)</option>
            </select>
        </div>

        <div class="col-sm-2">                            
        <input class="form-control pin_code" placeholder="Search Pin Code"  name="pin_code" type="text">
        </div>

        <div class="col-sm-2">                            
       <button class="reset_button btn btn-outline-primary rounded-0">Reset</button>
        </div>

     

        </div>
  

        <div class="card-block table-border-style table-responsive">
            <table id="data_table" class="display" cellspacing="0" width="100%" class="table-responsive">
                
                <thead>
                    <tr>
                    <th>#</th>
                        <th>SR</th>
                        <th> {{__('Head of Family')}} </th>
                        <th> {{__('Head Father Name')}} </th>
                        <th> {{__('Sub Caste')}} </th>

                        <th> {{__('Kulrishi')}} </th>
                        <th> {{__('kuldevi')}} </th>
                        <th> {{__('Gender')}} </th>
                        <th> {{__('Country')}} </th>
                        <th> {{__('State')}} </th>
                        <th> {{__('District')}} </th>
                        <th> {{__('City/Tehsil')}} </th>
                        <th> {{__('Gram panchayat')}} </th>
                        <th> {{__('Village ')}} </th>
                        <th> {{__('Pin Code')}} </th>  
                        <th> {{__('Created Date')}}</th>
                        <th> {{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')

<?php 
$copy_id = isset($_GET['id']) ? $_GET['id'] : '';
?>


<script src="{{URL::asset('admin/plugins/datatable/jquery.dataTables.min.js')}}"></script>
<script>

$(document).ready(function() {
  // Add or remove 'active' class on checkbox change
  $('table').on('change', 'input[type="checkbox"]', function() {
    var isChecked = $(this).is(':checked');
    var bgColor = isChecked ? '#e6f2ff' : 'transparent';
    $(this).closest('tr').toggleClass('active', isChecked).css('background-color', bgColor);
  });
}); 



$(document).on("click",".get_copy",function() {
    var family_id = $(this).attr('id');

$.ajax({
   type: "GET",
   url: "{{ route('families.copy-family') }}",
   data: { id: family_id },
   dataType: "json",
   success: function (data) {
    if (data.status === true) {
        if (typeof (data.redirect_url) != "undefined" && data.redirect_url !== null) {
           // window.location.replace(data.redirect_url+'?id='+data.id);
            window.open(data.redirect_url+'?id='+data.id, '_blank');

        }

   }else{


       }
   }
}); 
  });

    $(document).ready(function() {

        $(".get_copy").click(function() {

        var family_id = $(this).attr('id');

        $.ajax({
           type: "GET",
           url: "{{ route('families.copy-family') }}",
           data: { family_id: family_id },
           dataType: "json",
           success: function (data) {
            if (data.status === true) {
                if (typeof (data.redirect_url) != "undefined" && data.redirect_url !== null) {
                    window.location.replace(data.redirect_url+'?id='+data.id);
                }

           }else{


               }
           }
       }); 
       
     });
 

        var table = $('#data_table').DataTable({
            responsive: true, 
           // bFilter:true,
            "order": [
                [0, "desc"]
            ], // order by desc 
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [10,50,100,500],
            "pageLength": 50,
            //"sDom": '<"top" <"row" <"" <"">>>>tr<"bottom" <"row" <"col-sm-4" l><"col-sm-4 text-center" i><"col-sm-4" p>>>',

            ajax: {
                url: "{{ route($page.'.index') }}",
                data: function (d) {
                    d.copy_id = '<?php echo $copy_id; ?>'; 
                    d.country_id = $('.country_id').val(); 
                    d.state_id = $('.state_id').val(); 
                    d.district_id = $('.district_id').val(); 
                    d.tehsil_id = $('.tehsil_id').val(); 
                    d.panchayat_id = $('.panchayat_id').val(); 
                    d.village_id = $('.village_id').val(); 
                    d.gender = $('.gender').val();
                    d.kulrishi = $('.kulrishi').val();
                    d.pin_code = $('.pin_code').val();
                },
                error: function() {
                    $.alert('something_went_wrong!');

                }
            },

            "aoColumns": [
                { // checkbox column definition
                    mData: null,
                    bSortable: false,
                    mRender: function (data, type, row) {
                        return '<input type="checkbox" class="checkbox" value="' + row.id + '">';
                    }
                },
                {
                    mData: 'id'
                },
                {
                    mData: 'head_family'
                },
                {
                    mData: 'head_father_name'
                },
                {
                    mData: 'sub_caste'
                },
                {
                    mData: 'kulrishi'
                },
                {
                    mData: 'kildevi'
                },
                {
                    mData: 'gender'
                },
                {
                    mData: 'cname'
                },
                {
                    mData: 'sname'
                },
                {
                    mData: 'dname'
                },
                {
                    mData: 'tname'
                },
                {
                    mData: 'pname'
                },
                {
                    mData: 'vname'
                }, 
                {
                    mData: 'pin_code'
                }, 
 
                {
                    mData: 'updated_at'
                },
                {
                    mData: 'actions'
                },
            ],
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [-1,-3]
            }, ],
        });
 


        $('.pin_code').keyup(function(){
            table.draw();
        });
    
        $('.country_id').on('change',function(){
            table.draw();
        });
        $('.state_id').on('change',function(){
            table.draw();
        });
        $('.district_id').on('change',function(){
            table.draw();
        });
        $('.tehsil_id').on('change',function(){
            table.draw();
        });
        $('.panchayat_id').on('change',function(){
            table.draw();
        });
        $('.village_id').on('change',function(){
            table.draw();
        });
        $('.kulrishi').on('change',function(){
            table.draw();
        });

        $('.gender').on('change',function(){
            table.draw();
        });


     // Click event listener for the reset button
    $('.reset_button').on('click', function() {
        // Clear the search input and remove the search filter
        
        table.search('').draw();
        
        // Reset any other filter inputs you have, for example:
        $('.country_id').val('');
        $('.state_id').val('');
        $('.district_id').val('');
        $('.tehsil_id').val('');
        $('.panchayat_id').val('');
        $('.village_id').val('');
        $('.gender').val('');
        $('.kulrishi').val('');
        $('.pin_code').val('');
    });
    $('.reset_button').on('click', function() {
        // Clear the search input and remove the search filter
        
        table.search('').draw();
        
        // Reset any other filter inputs you have, for example:
        $('.country_id').val('');
        $('.state_id').val('');
        $('.district_id').val('');
        $('.tehsil_id').val('');
        $('.panchayat_id').val('');
        $('.village_id').val('');
        $('.gender').val('');
        $('.kulrishi').val('');
        $('.pin_code').val('');
    });


      
    });
    $('.export_csv').on('click',function () {
        var ff = "name_search="+$('#name_search').val()+"&email_search="+$('#email_search').val()+"&mobile_search="+$('#mobile_search').val()+"&status_search="+$('#status_search').val()+"&from_date="+$('#from_date').val()+"&to_date="+$('#to_date').val()+"";
        window.location="{{ url('admin/users/users_export') }}?"+ff;
    });
</script>

<style>
    table.dataTable tbody tr.active{
        background-color: #e6f2ff !important;
    }

  table.dataTable.display tbody tr.odd.active {
    background-color: #e6f2ff !important;
}
</style>



<script type="text/javascript">
    $(".filter-show-a").click(function () {

        $('.filter-show-a').hide();
        $('.filter-hide-to').show();
        $('.filter-option-content').show();
     
    });
    $(".filter-hide-to").click(function () {

        $('.filter-hide-to').hide();
        $('.filter-show-a').show();
       
        $('.filter-option-content').hide();

});



   
</script>
@endpush