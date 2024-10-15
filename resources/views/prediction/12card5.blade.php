@extends('admin.body.adminmaster')
@section('content')

     <div class="container-fluid mt-5">
          
               <div class="row" style="backgorund-color: #343a40; border-radius: 5px;margin-bottom:10px; ">
                     <div class="col-md-3 ">
                        <b class="city" style="font-weight:bold;font-size:15px;">Result Announcement Time -</b>
                    </div>
                     <div class="col-md-3">
                         <h4 id="result_announce_time"></h4>
                     </div>
                   <div class="col-md-3"></div>
                    <div class="col-md-3">
                        <h4 id="timer"></h4>
                    </div>
               </div>
    
             <form id="filterForm">
                <div class="row" style="backgorund-color: #343a40; border-radius: 5px; ">
                    <div class="col-md-1 ">
                        <h5 class="city">Status </h5>
                    </div>
                    <div class="col-md-1">
                            <select id="status">
                                    <option value="1">On</option>
                                     <option value="2">Off</option>
                                </select>
                    </div>
                    <div class="col-md-1 ">
                        <h5 class="city">Result </h5>
                    </div>
                    <div class="col-md-2">
                            <select id="result-type" class="form-control">
                                    <option value="1">Manual</option>
                                    <option value="2">Lucky Draw</option>
                                    <option value="3">Auto</option>
                            </select>
                    </div>
                    <div class="col-md-1 ">
                        <h5 class="city">Win %</h5>
                    </div>
                    <div class="col-md-1">
                             <select id="winning-percentage">
                                    <option value="25">25-50</option>
                                    <option value="50">50-75</option>
                                    <option value="75">75-100</option>
                                    <option value="100">100-125</option>
                                </select>
                    </div>
                     <div class="col-md-2">
                         <h5 class="city">site msg - </h5>
                     </div>
                      <div class="col-md-2">
                              <textarea name="textarea_name" id="textarea_id" rows="2" cols="10"  placeholder="site msg"></textarea>
                      </div>
                    <div class="col-md-1">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                   
                </div>
            </form>
                           


      
      
      
      
          
          <div class="row" style="padding-top: 2px;">
            @for($i = 1; $i <= 12; $i++)
                <div class="card col-md-1 mt-4" style="height:50px;">
                    <h1>{{ $i }}</h1>
                </div>
            @endfor

          </div>
          <div class="row" style="padding-bottom:20px;">
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/1.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/2.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/3.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/4.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/5.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/6.png"></div>
                
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/7.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/8.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/9.png"></div>
                <div class="card col-md-1 mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/10.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/11.png"></div>
                <div class="card col-md-1 mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/12.png"></div>
          </div>
          <div class="row" style="  padding-bottom:20px;" id="amounts-container"></div>
               
          <form action="{{route('admin_prediction')}}" method="post">
            @csrf
               <!--important input box hidden for prediction insert and also works for custom date selection-->
             <input type="hidden" class="form-control" id="result_time" style="  font-size: 16px;color:#333;border:none" name="result_time" value="">
                   <div class="row ml-4 d-flex" style="margin-bottom: 20px;"> 
                   <div class="col-md-3 form-group d-flex">
                       <p style="color: #000000;font-weight:500;">select date & time only for upcoming prediction, no need for current time prediction - </p>
                   </div>
                        <div class="col-md-3 form-group d-flex">
                            <input type="datetime-local" id="custom_result_date_time" name="custom_result_date_time" class="form-control" value="" placeholder="Result Date">
                         </div>
                         
                         <div class="col-md-2 form-group d-flex">
                             <input type="number" name="number" class="form-control" min="1" max="12" placeholder="Result" required>
                         </div>
                         <div class="col-md-2 form-group d-flex">
                            <button type="submit" class="form-control btn btn-info"><b>Submit</b></button>
                         </div>
                         <div class="col-md-2 form-group d-flex mt-1">
                            <a href=""> <i class="fa fa-refresh" aria-hidden="true" style="font-size:30px;"></i></a>
                         </div>
                   </div>
           </form>
           
            <div class="table-responsive">
                <h3 class="text-center b-3">Bet Points Details</h3>
                <table class="table table-hover" style="white-space: nowrap;">
                    <thead class="thead-dark">
                        <tr>
                           <th>Sr.Number</th>
                           <th>User Terminal Id</th>
                           <th>Stockist Terminal Id</th>
                           <th>Substockist Terminal Id</th>
                           <th>Total Purchase Card</th>
                           <th>Purchase Points</th>
                           <th>Card Name</th>
                           <th>Win Points</th>
                           <th>Bet Status</th>
                           <th>Bet Time</th>
                           <th>Result Time</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!--<tr>-->
                        <!--<td>cmd</td>-->
                        <!--<td>mnsm</td>-->
                        <!--<td>emn x</td>-->
                        <!--<td>mn x</td>-->
                        <!--<td>nmsc x</td>-->
                        <!--<td>jcd </td>-->
                        <!--<td>skjnx</td>-->
                        <!-- <td>cmd</td>-->
                        <!--<td>mnsm</td>-->
                        <!--<td>emn x</td>-->
                        <!--<td>mn x</td>-->
                        <!--<td>nmsc x</td>-->
                        <!--<td>jcd </td>-->
                        <!--<td>skjnx</td>-->
                        <!--</tr>-->
                     </tbody>
              </table>
           </div>
           
           
           
           
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // function fetchData() {
    //     fetch('api/fetch')
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log('Fetched data:', data);
    //             if (data && data.bet_log) {
    //                 updateBets(data.bet_log,data.result_time); //calling updateBets function to update amount in div
    //             } else {
    //                 console.error('Data format is incorrect or bet_log is missing:', data);
    //             }
    //         })
    //         .catch(error => console.error('Error fetching data:', error));
    // }
    
        function fetchData() {
            const dateTimeValue = document.getElementById('custom_result_date_time').value; // Get the value from the input
             console.log(`custom datetime value is - ${dateTimeValue}`);
            fetch('api/fetch', {
                method: 'POST', // Change to POST
                headers: {
                    'Content-Type': 'application/json', // Set the content type
                },
                body: JSON.stringify({ custom_date_time: dateTimeValue }) // Include the parameter in the body
            })
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data);
                if (data && data.bet_log) {
                    updateBets(data.bet_log,data.result_time); // Update the bets
                } else {
                    console.error('Data format is incorrect or bet_log is missing:', data);
                }
            })
            .catch(error => console.error('Error fetching data:', error));
       }

    function updateBets(bet_log,result_time) {
        console.log('Updating Bets:', bet_log);
        var amountdetailHTML = '';
        // var result_time = '<h1>Result Time - '+ result_time + '</h1>';
       
       bet_log.forEach((item, index) => {
                amountdetailHTML += '<div class="card col-md-1 mt-4" style="background-color:#fff;">';
                amountdetailHTML += '<div class="card-body" onclick="point_details(' + (index + 1) + ')">';
                amountdetailHTML += '<b style="color:black">' + item.amount + '</b>';
                amountdetailHTML += '</div>';
                amountdetailHTML += '</div>';
            });


        $('#amounts-container').html(amountdetailHTML);
        $('#result_time').val(result_time);
         document.getElementById('result_announce_time').textContent = result_time;
    }

    function refreshData() {
        fetchData();
        setInterval(fetchData, 3000); // Refresh every 3 seconds
    }
    
    function point_details(card_number){
        const result_time =  document.getElementById('result_time').value; 
        console.log(`result_time is - ${result_time}  and card number is - ${card_number}`);
        
        fetch('api/point_details', {
                method: 'POST', // Change to POST
                headers: {
                    'Content-Type': 'application/json', // Set the content type
                },
                body: JSON.stringify({ result_time: result_time,card_number: card_number}) // Include the parameter in the body
            })
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data);
                if (data && data.point_details) {
                    updatePointsDetails(data.point_details); // Update the bets
                } else {
                    updatePointsDetailsError();
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }
    
    
  function updatePointsDetails(point_details) {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = ''; // Clear the table body before adding new data

    if (point_details.length > 0) {
        point_details.forEach((detail, index) => {
            const row = document.createElement('tr');

          row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${detail.user_ter_id}</td>
                    <td>${detail.stockist_ter_id ?? 'N/A'}</td>
                    <td>${detail.substockist_ter_id ?? 'N/A'}</td>
                    <td>${detail.total_card}</td>
                    <td>${detail.purchase_points}</td>
                    <td>${detail.card_name}</td>
                    <td>${detail.win_points}</td>
                    <td>${detail.bet_status}</td>
                    <td>${detail.bet_time}</td>
                    <td>${detail.result_time}</td>
                `;

            tableBody.appendChild(row);
        });
    } else {
        // If point_details is empty, display "No Data Available"
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="11" class="text-center">No Data Available</td>`;
        tableBody.appendChild(row);
    }
}


function updatePointsDetailsError() {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = `<tr><td colspan="11" class="text-center">No Data Available</td></tr>`;
}

    

    function page_refresh() {
        location.reload();
    }

    function updateClock() {
        const timerElement = document.getElementById('timer');
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        timerElement.textContent = timeString;
    }

    document.addEventListener('DOMContentLoaded', () => {
        refreshData();
        updateClock();
        setInterval(updateClock, 1000); // Update clock every second
    });
</script>
<script type="text/javascript">    
   // setInterval(page_refresh, 300000); // Refresh page every 1 minute
</script>




     </div>
@endsection('content')
