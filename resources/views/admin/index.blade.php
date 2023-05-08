@extends('layout.admin')
@section('content')


<div class="d-flex justify-content-between dashboard-top-bar">
<h5 class="page_heading">Dashboard  </h5>
</div>

<div class="dashboard-card">
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-3">

 
  


  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Families </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-three.png')}}" alt="" />
            </div>
            <h4 class="my-1 "> {{$user_count ?? 0}} </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Population   </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-3.png')}}" alt="" />
            </div>
            <h4 class="my-1 ">{{$FamilyMember_count ?? 0}}</h4>
           </div>
          
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card radius-10">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Male</p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-six.png')}}" alt="" />
            </div>
            <h4 class="my-1 ">{{$FamilyMembermale_count ?? 0}}</h4>
           </div>
          
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Female </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-three.png')}}" alt="" />
            </div>
            <h4 class="my-1 "> {{$FamilyMemberfemale_count ?? 0}} </h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  


  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Countries </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-four.png')}}" alt="" />
            </div>
            <h4 class="my-1"> {{$country_count ?? 0}} </h4>
          </div>
          
        </div>
      </div>
    </div>
  </div>

 

  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total States  </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-five.png')}}" alt="" />
            </div>
            <h4 class="my-1 "> {{$states_count ?? 0}}  </h4>
          </div>
          
        </div>
      </div>
    </div>
  </div>




  <div class="col">
    <div class="card radius-10">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Districts</p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-six.png')}}" alt="" />
            </div>
            <h4 class="my-1 ">{{$districts_count ?? 0}}</h4>
           </div>
          
        </div>
      </div>
    </div>
  </div>


  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Tehsils </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-1.png')}}" alt="" />
            </div>
            <h4 class="my-1 ">{{$tehsils_count ?? 0}}</h4>
           </div>
          
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Panchayat  </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-2.png')}}" alt="" />
            </div>
            <h4 class="my-1 ">{{$panchayat_count ?? 0}}</h4>
           </div>
          
        </div>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card radius-10 ">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <p class="mb-0 text-secondary">Total Village   </p>
            <div class="das-img">
              <img src="{{URL::asset('images/dasboard-3.png')}}" alt="" />
            </div>
            <h4 class="my-1 ">{{$village_count ?? 0}}</h4>
           </div>
          
        </div>
      </div>
    </div>
  </div>



 

</div>
</div>
<!--end row-->



@if(roleName() == 'admin')
<div class="dashboard-card-bottom">
<div class="row">

  <div class="col-12 col-lg-12">
    <div class="card radius-10">
      <div class="card-body row">
        <div class="col-10">
          <div class="d-flex align-items-center">
              <h6 class="mb-0">Families Overview</h6>            
          </div>
          <div class="d-flex align-items-center ms-auto font-13 gap-2 my-3">
            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i> Families </span>
            <!-- <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #ffc107"></i> Subscription </span> -->
          </div>
        </div>
        <div class="col-2">
          <select id="users_year" name="users_year" class="form-select w-100 das-r">
            <option value="<?php echo date('Y') ?>" selected ><?php echo date('Y') ?></option>
            <option value="<?php echo date('Y' , strtotime('-1 year')) ?>"><?php echo date('Y' , strtotime('-1 year')) ?></option>
            <option value="<?php echo date('Y' , strtotime('-2 year')) ?>"><?php echo date('Y' , strtotime('-2 year')) ?></option>                              
          </select>
        </div>
        <div class="chart-container-1">
          <canvas id="chart1"></canvas>
        </div>
      </div>
      <div class="row g-0 row-group text-center border-top">
        <div class="col-md-6">
          <div class="p-3 das-card-bottom">
            <h5 class="mb-0" id="overall_user"> {{$user_count0 ?? 0 }}  </h5>
            <small class="mb-0"> Overall Families </small>
          </div>
        </div>
        
      </div>
    </div>
  </div>

</div>



<div class="col-12 col-lg-12">
    <div class="card radius-10">
      <div class="card-body row">
        <div class="col-10">
          <div class="d-flex align-items-center">
              <h6 class="mb-0">Population Overview</h6>            
          </div>
          <div class="d-flex align-items-center ms-auto font-13 gap-2 my-3">
            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i> Population </span>
            <!-- <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #ffc107"></i> Subscription </span> -->
          </div>
        </div>
        <div class="col-2">
        

          <select id="users_year2" name="users_year2" class="form-select w-100 das-r">
          <?php
              $options = array(
                  '2010-2023',
                  '2000-2010',
                  '1990-2000',
                  '1980-1990',
                  '1970-1980',
                  '1960-1970',
                  '1950-1960',
                  '1940-1950'
              );
              
              foreach ($options as $option) {
                  $selected = '';
                 
                  echo "<option value=\"$option\" $selected>$option</option>";
              }
          ?>
      </select>



        </div>
        <div class="chart-container-1">
          <canvas id="chart2"></canvas>
        </div>
      </div>
      <div class="row g-0 row-group text-center border-top">
        <div class="col-md-6">
          <div class="p-3 das-card-bottom">
            <h5 class="mb-0" id="overall_member"> {{$family_member_data0 ?? 0 }}  </h5>
            <small class="mb-0"> Overall Population </small>
          </div>
        </div>
        
      </div>
    </div>
  </div>

</div>


</div>
@endif
<!--end row-->
@endsection

@push('scripts')
    <script src="{{URL::asset('admin/plugins/chartjs/js/Chart.min.js')}}"></script>

    <script>
   $("#users_year").change(function() {
        var users_year = $(this).val();
        get_user_data(users_year);
    });

function get_user_data(year) {
        $.ajax({
            type: 'get',
            url: "<?php echo route('admin.get_chart_data')?>",
            data: {
                'year': year,
            },
            dataType: 'json',
            success: function(data) {
                //drawUserChart(data.month_name)
                $('#overall_user').text(data.user_count);
              //  $('#overall_suscription').text(data.subscription_count);
                Array.prototype.splice.apply(myChartFamily.data.datasets[0].data, [0, data.monthly_users.length].concat(data.monthly_users));
               // Array.prototype.splice.apply(myChart.data.datasets[1].data, [0, data.subscription_monthly.length].concat(data.subscription_monthly));
               myChartFamily.update();
                //console.log(myChart.data.datasets[0].data);
                
            }
        });
    }

  // chart 1
    var ctx = document.getElementById("chart1").getContext('2d');
    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
        gradientStroke1.addColorStop(0, '#6078ea');  
        gradientStroke1.addColorStop(1, '#17c5ea'); 
     
    // var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    //     gradientStroke2.addColorStop(0, '#ff8359');
    //     gradientStroke2.addColorStop(1, '#ffdf40');
  
        var myChartFamily = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: <?php echo $month_array; ?>,
            datasets: [{
              label: 'Families',
              data: <?php echo $monthly_users; ?>,
              borderColor: gradientStroke1,
              backgroundColor: gradientStroke1,
              hoverBackgroundColor: gradientStroke1,
              pointRadius: 0,
              fill: false,
              borderWidth: 0
            }]
          },
      
      options:{
        maintainAspectRatio: false,
        legend: {
          position: 'bottom',
                display: false,
          labels: {
                  boxWidth:8
                }
              },
        tooltips: {
          displayColors:false,
        },	
        scales: {
          xAxes: [{
          barPercentage: .5
          }]
           }
      }
        });
    	

 

    $("#users_year2").change(function() {
        var users_year = $(this).val();
        get_member_data(users_year);
    });  

 

    function get_member_data(year) {
        $.ajax({
            type: 'get',
            url: "<?php echo route('admin.get_member_chart_data')?>",
            data: {
                'year': year,
            },
            dataType: 'json',
            success: function(data) {
                //drawUserChart(data.month_name)
                $('#overall_member').text(data.user_count);
             //   $('#mem_overall_suscription').text(data.subscription_count);
                Array.prototype.splice.apply(myChart2.data.datasets[0].data, [0, data.monthly_users.length].concat(data.monthly_users));
                myChart2.update();
                //console.log(myChart.data.datasets[0].data);
                
            }
        });
    }




    // chart 2
    var ctx2 = document.getElementById("chart2").getContext('2d');
    var gradientStroke12 = ctx2.createLinearGradient(0, 0, 0, 300);
        gradientStroke12.addColorStop(0, '#6078ea');  
        gradientStroke12.addColorStop(1, '#17c5ea'); 
     
   
  
        var myChart2 = new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: <?php echo $pop_month_array; ?>,
            datasets: [{
              label: 'Population',
              data: <?php echo $pop_monthly_users; ?>,
              borderColor: gradientStroke12,
              backgroundColor: gradientStroke12,
              hoverBackgroundColor: gradientStroke12,
              pointRadius: 0,
              fill: false,
              borderWidth: 0
            } ]
          },
      
      options:{
        maintainAspectRatio: false,
        legend: {
          position: 'bottom',
                display: false,
          labels: {
                  boxWidth:8
                }
              },
        tooltips: {
          displayColors:false,
        },	
        scales: {
          xAxes: [{
          barPercentage: .5
          }]
           }
      }
        });
      </script>
@endpush
