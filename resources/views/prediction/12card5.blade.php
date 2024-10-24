@extends('admin.body.adminmaster')
@section('content')


     <div class="container-fluid mt-5">
          
               <div class="row" style="backgorund-color: #fff; border-radius: 5px;margin-bottom:10px; ">
                     <div class="col-sm-3 ">
                        <b class="city" style="font-weight:bold;font-size:15px;">Result Announcement Time -</b>
                    </div>
                     <div class="col-sm-3">
                         <h4 id="result_announce_time"></h4>
                     </div>
                   <div class="col-sm-3"></div>
                    <div class="col-sm-3">
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
        
        <div class="col-sm-1">
            <h5 class="city">Result</h5>
        </div>
        <div class="col-sm-2">
            <select id="result-type" class="form-control" name="result">
                <option value="1" {{$game_settings->result_type==1?'selected':''}}>Manual</option>
                <option value="2" {{$game_settings->result_type==2?'selected':''}}>Lucky Draw</option>
                <option value="3" {{$game_settings->result_type==3?'selected':''}}>Auto</option>
            </select>
        </div>

        <div class="col-sm-1">
            <h5 class="city">Win %</h5>
        </div>
        <div class="col-sm-1">
            <select id="winning-percentage" name="percentage">
                <option value="25" {{$game_settings->winning_per==25?'selected':''}}>25-50</option>
                <option value="50" {{$game_settings->winning_per==50?'selected':''}}>50-75</option>
                <option value="75" {{$game_settings->winning_per==75?'selected':''}}>75-100</option>
                <option value="100" {{$game_settings->winning_per==100?'selected':''}}>100-125</option>
            </select>
        </div>

        <div class="col-sm-2">
            <h5 class="city">Site Msg</h5>
        </div>
        <div class="col-sm-2">
            <textarea id="textarea_id" rows="2" cols="20" name="site_message" placeholder="site msg">{{$game_settings->site_message}}</textarea>
        </div>

        <div class="col-sm-1">
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>



<div class="card p-2 mt-5">
    <div class="table-responsive mt-5 ">
        <table class="table table-bordered" style="width: 100%; text-align: center;">
            <thead>
                <tr>
                    <th>Card</th>
                    <th class="mt-2">jack</th>
                    <th class="mt-2">Queen</th>
                    <th class="mt-2">king</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="h1">&#9827;</span><span class=" d-block">Clubs</span></td>
                    <td class="bg-danger-hover" onclick="point_details(1)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">1</span>
                            <h5 class="p-3" id="jc"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover" onclick="point_details(5)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">5</span>
                            <h5 class="p-3" id="qc"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover" onclick="point_details(9)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">9</span>
                            <h5 class="p-3" id="kc"></h5>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="h1 text-danger">&#9830;</span><span class=" d-block">Diamonds</span></td>
                    <td class="bg-danger-hover" onclick="point_details(2)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">2</span>
                            <h5 class="p-3" id="jd"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover" onclick="point_details(6)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">6</span>
                            <h5 class="p-3" id="qd"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover" onclick="point_details(10)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">10</span>
                            <h5 class="p-3" id="kd"></h5>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="h1">&#9824;</span><span class=" d-block">Spades</span></td>
                    <td class="bg-danger-hover" onclick="point_details(3)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">3</span>
                            <h5 class="p-3" id="js"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover" onclick="point_details(7)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">7</span>
                            <h5 class="p-3" id="qs"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover" onclick="point_details(11)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">11</span>
                            <h5 class="p-3" id="ks"></h5>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="h1 text-danger">&#9829;</span><span class=" d-block">Hearts</span></td>
                    <td class="bg-danger-hover" onclick="point_details(4)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">4</span>
                            <h5 class="p-3" id="jh"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover" onclick="point_details(8)">
                        <div style="position: relative;">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">8</span>
                            <h5 class="p-3" id="qh"></h5>
                        </div>
                    </td>
                    <td class="bg-danger-hover">
                        <div style="position: relative;" onclick="point_details(12)">
                            <span style="position: absolute; top: 0; left: 0; font-weight: bold; color:red;">12</span>
                            <h5 class="p-3" id="kh"></h5>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
  
 <div class="card mt-4">
    <div class="container">
        <div class="row">
            <div class="col-sm-7">
                <div style="">
                    <div style="font-size:15 px; font-weight: bold;">Total Purchase Points: <span style="font-size:15 px; font-weight: bold;  margin-top: 10px; margin-left:20px;" id="toatlPurchaseTicket"></span></div>
                    <div style="font-size:15 px; font-weight: bold; margin-top: 10px;">Auto Win Points:<span style="font-size:15 px; font-weight: bold;  margin-left:20px;" id="maxSystemWinning"></span></div>
                </div>
            </div>
            <div class="col-sm-5 mt-3">
                <select name="users_id" id="users-akash" class="form-control select2"
                    style="border:2px solid black;color:black;font-weight:400;width:70%;height:40px">
                    <option value="">Select User</option>
                    @foreach($users as $user_value)
                    <option value="{{$user_value->id}}">{{$user_value->terminal_id}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
</div>
</div>
  
          <form action="{{route('admin_prediction')}}" method="post" class="mt-4">
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
           
            <div class="table-responsive mb-5">
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
                     </tbody>
              </table>
           </div>
           
           
          

  <!--<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>-->
<script>

     document.addEventListener('DOMContentLoaded', () => {
                refreshData();
                updateClock();
                setInterval(updateClock, 1000); // Update clock every second
                
                $('#users-akash').select2({
                placeholder: "All User Terminals",
                allowClear: true
                  });
            });

            function refreshData() {
                fetchData();
                setInterval(fetchData, 3000); // Refresh every 3 seconds
            }
    
        function fetchData() {
                const dateTimeValue = document.getElementById('custom_result_date_time').value; // Get the value from the input
                const userId = document.getElementById('users-akash').value; // Get the value from the input
                 console.log(`custom datetime value is - ${dateTimeValue}`);
                 console.log(`users id is - ${userId}`);
                fetch('api/fetch', {
                    method: 'POST', // Change to POST
                    headers: {
                        'Content-Type': 'application/json', // Set the content type
                    },
                    body: JSON.stringify({ custom_date_time: dateTimeValue,user_id:userId}) // Include the parameter in the body
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
    
        document.getElementById('jc').textContent = bet_log[0].amount;
        document.getElementById('jd').textContent = bet_log[1].amount;
        document.getElementById('js').textContent = bet_log[2].amount;
        document.getElementById('jh').textContent = bet_log[3].amount;
        document.getElementById('qc').textContent = bet_log[4].amount;
        document.getElementById('qd').textContent = bet_log[5].amount;
        document.getElementById('qs').textContent = bet_log[6].amount;
        document.getElementById('qh').textContent = bet_log[7].amount;
        document.getElementById('kc').textContent = bet_log[8].amount;
        document.getElementById('kd').textContent = bet_log[9].amount;
        document.getElementById('ks').textContent = bet_log[10].amount;
        document.getElementById('kh').textContent = bet_log[11].amount;
       

        $('#amounts-container').html(amountdetailHTML);
        $('#winning-container').html(winningdetailHTML);
        $('#result_time').val(result_time);
        document.getElementById('result_announce_time').textContent = result_time;
        document.getElementById('toatlPurchaseTicket').textContent = total_purchase_point;
        document.getElementById('maxSystemWinning').textContent = system_winning;
    }
    
    function point_details(card_number){
        const result_time =  document.getElementById('result_time').value; 
        const userId = document.getElementById('users-akash').value;
        
        console.log(`result_time is - ${result_time}  and card number is - ${card_number}`);
        
        fetch('api/point_details', {
                method: 'POST', // Change to POST
                headers: {
                    'Content-Type': 'application/json', // Set the content type
                },
                body: JSON.stringify({ result_time: result_time,card_number: card_number,user_id:userId}) // Include the parameter in the body
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
