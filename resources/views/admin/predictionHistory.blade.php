@extends('admin.body.adminmaster')
@section('content')

<div class="col-md-12 margin_top_30">
    <!--<div class="white_shd full margin_bottom_30">-->
        <div class="full graph_head">
            <div class="heading1 margin_0">
                <h2>Prediction History</h2>
            </div>
        </div>
        <!--<div class="padding_infor_info">-->
            <!-- Filter Section -->
            <form id="filterForm" action="{{route('delete_prediction')}}" method="post">
                @csrf
                <div class="row" style="backgorund-color: #343a40; padding: 10px; border-radius: 5px; ">
                    
                    <div class="col-md-3 ">
                        <!--<input type="text" class="btn btn-primary" onclick="filterTable()">Filter</button>-->
                        <h4 class="city">Delete Prediction :</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <!--<label for="result_time">Result Time</label>-->
                            <input type="datetime-local" class="form-control" name="result_time" id="result_time" placeholder="Enter result time">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit"  name=="submit" class="btn btn-primary">Delete</button>
                    </div>
                    <div class="col-md-2 ">
                        
                    </div>
                </div>
            </form>
            
            <!-- Table Section -->
            <div class="table-responsive-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Card Name</th>
                            <th>Result Time</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @php
                                $card_info = [
                                    '1' => 'JC',
                                    '2' => 'JD',
                                    '3' => 'JS',
                                    '4' => 'JH',
                                    '5' => 'QC',
                                    '6' => 'QD',
                                    '7' => 'QS',
                                    '8' => 'QH',
                                    '9' => 'KC',
                                    '10' => 'KD',
                                    '11' => 'KS',
                                    '12' => 'KH'
                                ];
                            @endphp
                        
                        @foreach($prediction_history as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>
                                {{$card_info[$item->card_number]}}
                            </td>
                            <td>{{$item->result_time}}</td>
                        </tr>
                        
                        @endforeach
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
                	<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $prediction_history->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $prediction_history->url(1) }}" aria-label="First">
                <span aria-hidden="true">&laquo;&laquo;</span>
            </a>
        </li>
        <li class="page-item {{ $prediction_history->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $prediction_history->previousPageUrl() }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        @php
            $half_total_links = floor(9 / 2);
            $from = $prediction_history->currentPage() - $half_total_links;
            $to = $prediction_history->currentPage() + $half_total_links;

            if ($prediction_history->currentPage() < $half_total_links) {
                $to += $half_total_links - $prediction_history->currentPage();
            }

            if ($prediction_history->lastPage() - $prediction_history->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($prediction_history->lastPage() - $prediction_history->currentPage()) - 1;
            }
        @endphp

        @for ($i = $from; $i <= $to; $i++)
            @if ($i > 0 && $i <= $prediction_history->lastPage())
                <li class="page-item {{ $prediction_history->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $prediction_history->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor

        <li class="page-item {{ $prediction_history->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $prediction_history->nextPageUrl() }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <li class="page-item {{ $prediction_history->currentPage() == $prediction_history->lastPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $prediction_history->url($prediction_history->lastPage()) }}" aria-label="Last">
                <span aria-hidden="true">&raquo;&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
                
                
            </div>
        <!--</div>-->
    <!--</div>-->
</div>

@endsection
