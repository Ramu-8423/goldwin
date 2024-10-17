@extends('admin.body.adminmaster')
@section('content')

     <div class="container-fluid mt-5">
          
               <div class="row" style="backgorund-color: #fff; border-radius: 5px;margin-bottom:10px; ">
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
    
             <form id="filterForm" action="{{route('game_setting')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-1">
            <h5 class="city">Status</h5>
        </div>
        <div class="col-md-1">
            <select id="status" name="status">
                <option value="1" {{$game_settings->status==1?'selected':''}}>On</option>
                <option value="2" {{$game_settings->status==2?'selected':''}}>Off</option>
            </select>
        </div>
        
        <div class="col-md-1">
            <h5 class="city">Result</h5>
        </div>
        <div class="col-md-2">
            <select id="result-type" class="form-control" name="result">
                <option value="1" {{$game_settings->result_type==1?'selected':''}}>Manual</option>
                <option value="2" {{$game_settings->result_type==2?'selected':''}}>Lucky Draw</option>
                <option value="3" {{$game_settings->result_type==3?'selected':''}}>Auto</option>
            </select>
        </div>

        <div class="col-md-1">
            <h5 class="city">Win %</h5>
        </div>
        <div class="col-md-1">
            <select id="winning-percentage" name="percentage">
                <option value="25" {{$game_settings->winning_per==25?'selected':''}}>25-50</option>
                <option value="50" {{$game_settings->winning_per==50?'selected':''}}>50-75</option>
                <option value="75" {{$game_settings->winning_per==75?'selected':''}}>75-100</option>
                <option value="100" {{$game_settings->winning_per==100?'selected':''}}>100-125</option>
            </select>
        </div>

        <div class="col-md-2">
            <h5 class="city">Site Msg</h5>
        </div>
        <div class="col-md-2">
            <textarea id="textarea_id" rows="2" cols="20" name="site_message" placeholder="site msg">{{$game_settings->site_message}}</textarea>
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
           <div class="row" style="padding-bottom:20px;" id="winning-container"></div>
           <div class="row d-flex justify-content-center align-items-center" style="padding-bottom:20px;background-color:white;">
               <div class="col-md-2" style="border:1px solid blue;color:black;font-weight:400;width:100%;height:40px">Total Purchase Points - </div>
                <div class="col-md-2" style="border:1px solid blue;color:black;font-weight:400;width:100%;height:40px" id="toatlPurchaseTicket"></div>
                <div class="col-md-3" style="border:1px solid blue;color:black;font-weight:400;width:100%;height:40px">System max winning points - </div>
                <div class="col-md-2" style="border:1px solid blue;color:black;font-weight:400;width:100%;height:40px" id="maxSystemWinning"></div>
                <div class="col-md-3"></div>
           </div>
               
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
                             <input type="number" name="number" class="form-control" min="1" max="12" placeholder="Result" id="result-number" required>
                         </div>
                         <div class="col-md-2 form-group d-flex">
                            <button type="submit" class="form-control btn btn-info" id="submit-button"><b>Submit</b></button>
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
                    updateBets(data.bet_log,data.result_time,data.total_purchase_point,data.system_winning); // Update the bets
                } else {
                    console.error('Data format is incorrect or bet_log is missing:', data);
                }
            })
            .catch(error => console.error('Error fetching data:', error));
       }

    function updateBets(bet_log,result_time,total_purchase_point,system_winning) {
        console.log('Updating Bets:', bet_log);
        var amountdetailHTML = '';
        var winningdetailHTML = '';
        // var result_time = '<h1>Result Time - '+ result_time + '</h1>';
       
       bet_log.forEach((item, index) => {
                amountdetailHTML += '<div class="card col-md-1 mt-4" style="background-color:#fff;color:black;font-weight:400;">';
                amountdetailHTML += '<div class="card-body" onclick="point_details(' + (index + 1) + ')">';
                amountdetailHTML += '<b style="color:black">' + item.amount + '</b>';
                amountdetailHTML += '</div>';
                amountdetailHTML += '</div>';
                
                winningdetailHTML += '<div class="card col-md-1 mt-4" style="background-color:#fff;color:black;font-weight:400;">';
                winningdetailHTML += '<div class="card-body" onclick="point_details(' + (index + 1) + ')">';
                winningdetailHTML += '<b style="color:black">' + (item.amount/5)*50 + '</b>';
                winningdetailHTML += '</div>';
                winningdetailHTML += '</div>';
                
            });


        $('#amounts-container').html(amountdetailHTML);
        $('#winning-container').html(winningdetailHTML);
        $('#result_time').val(result_time);
        document.getElementById('result_announce_time').textContent = result_time;
        document.getElementById('toatlPurchaseTicket').textContent = total_purchase_point;
        document.getElementById('maxSystemWinning').textContent = system_winning;
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
       function toggleFields() {
        const resultType = document.getElementById('result-type').value;
        const resultNumber = document.getElementById('result-number');
        const submitButton = document.getElementById('submit-button');
        
        if (resultType == '1') {
            // Enable number input and submit button
            resultNumber.disabled = false;
            submitButton.disabled = false;
        } else {
            resultNumber.disabled = true;
            submitButton.disabled = true;
        }
    }
    // Call the function on page load to ensure correct state based on pre-selected value
    window.onload = toggleFields;
</script>




     </div>
@endsection('content')
