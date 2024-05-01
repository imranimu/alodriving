@extends('layouts.student.layer')
@section('title', 'Courses Lists | Driving School')
@section('content')
    
    <div class="Back">
        <a href="{{ url('student/dashboard') }}" class="btn-11"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    
    <!-- course area start -->
    <div class="course-area pb-5">
        <div class="container bg-white py-3">
			<!-- @include('layouts/student/top_navbar') -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="wrap wrap-content">
                        <table class="table table-bordered">
                            <thead>
                                <tr> 
                                    <th scope="col">Courses Name</th>
									<th scope="col">Total Module</th>
									<th scope="col">Module Complete</th>
                                    <th scope="col">Courses Progress</th>
                                    <th scope="col">Payment Details</th>
									<th scope="col">Payment Status</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($records))
                                    @php $count = 1; @endphp
                                    @foreach ($records as $val)
                                        <tr> 
                                            <td>{{ $val->get_course->title }}</td>
											<td>{{ $val->total_module }}</td>
											<td>{{ getModuleCompleted($val->course_id) }}</td>
                                            <td> 
                                                {{ getCoursePercetage($val->course_id) }}% 
                                            </td>
											<td><a href="javascript:void(0)" class="badge badge-success" onclick="paymentShow({{$val->id}})">History</a></td>
											<td>
                                                @if ($val->payment_status == 1 && $val->status == 1)
                                                    <label class="badge badge-success">Confirm</label>
                                                @else
                                                    <label class="badge badge-secondary">Pending</label>
                                                @endif
                                            </td>
											 @php
												$get_lession_status = getLastCourseLessionCompleted($val->course_id);
                                                $lession = DB::table('course_lessons')
                                                    ->where(['course_id' => $val->course_id, 'module_id' => $val->module_id])
													->whereRaw('deleted_at is null')
                                                    ->orderBy('id')
                                                    ->first();
                                            @endphp
                                            <td class="ActionButton">
											@if (isset($get_lession_status) && ($get_lession_status->complete_lession == '' || $get_lession_status->complete_lession != '') &&   $get_lession_status->ongoing_lession != '')
                                                    <a href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/' . $get_lession_status->ongoing_lession . '/2') }}" class="btn btn-primary ">Start</a>
                                                @elseif (isset($get_lession_status) && $get_lession_status->complete_lession != '' && $get_lession_status->ongoing_lession == '')
                                                    @php
                                                        $total_lession = $get_lession_status->complete_lession != '' ? count(json_decode($get_lession_status->complete_lession, true)) : 0;
                                                    @endphp
                                                    @if ($total_lession == $get_lession_status->total_lession && $get_lession_status->ongoing_lession == '')
                                                        <a href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/0/2') }}" class="btn btn-primary ">Start</a>
                                                    @else
                                                        <a href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/' . $get_lession_status->ongoing_lession . '/2') }}" class="btn btn-primary ">Start</a>
                                                    @endif
                                                @else
                                                    @if (!blank($lession))
                                                        <a href="{{ url('/student/course/' . $val->course_id . '/' . $val->module_id . '/' . $lession->id . '/2') }}" class="btn btn-primary ">Start</a>
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @php $count++; @endphp
                                    @endforeach
								@else
									<tr>
										<td colspan="8">No Courses Found!</td>
									</tr>
                                @endif
                            </tbody>
                        </table>
                        
                        @if (!blank($records)) 
                            @foreach ($records as $val)
                                @php
                                    $completedLesson = getLastCourseLessionCompleted($val->get_course->id);
                                    //echo $completedLesson;
                                @endphp
                                <ul class="text-left">
                                @foreach (getCoursesModules() as $Module)
                                    <li>{{ $Module->name }} {{ $Module->id }}  
                                        @php
                                            $getModules = getCourseLession($val->get_course->id, $Module->id);

                                            $ids = [];
                                            foreach ($getModules as $item) {
                                                $ids[] = $item['module_id'];
                                            }

                                            // echo $getModules; 
                                            // $ids = array_map(function($item) {
                                            //     return $item['id'];
                                            // }, $getModules); 

                                            // Array_count
                                            $TotalLession = count($ids);

                                        
                                            echo $TotalLession;
                                            
                                            
                                            echo '<pre>';
                                            print_r($ids);
                                            echo '</pre>';

                                        @endphp
                                        
                                        @if($getModules)
                                            <ul>
                                                @foreach($getModules as $module)
                                                    <li><i class="fa fa-lock"></i> {{ $module->title }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                                </ul>
                            @endforeach
                        @endif 
                    </div>
                </div>
            </div>
        </div>
		<!-- Modal -->
        <div class="modal fade" id="addonsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Payment History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="bodyBox">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .wrap.wrap-content {
            background: #fff;
            padding: 22px;
        }

        .wrap-content h3 {
            border-bottom: 1px solid #ccc;
            text-align: left;
        } 
        .Back {
            position: absolute;
            top: 90px;
            overflow: hidden;
            left: 20px;
            padding: 5px 0;
        }

        .Back a {
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 40px;
        }

        .Back a:hover{
            background: var(--main-color);
            color: #fff;
        }
        body .table td.ActionButton{
            position: absolute;
            border: none;
            left: 50%;
            transform: translate(-50%, 0px);
            bottom: -50px;
        }
        body .table td.ActionButton a.btn {
            background: #EFC45C;
            border-radius: 50px;
            width: 274px;
            color: #000;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 16px;
        }
        body .table td.ActionButton a.btn:hover{
            color: #fff;
        }
    </style>
     
	<script>
	function paymentShow(id) {
		$.ajax({
			type: 'POST',
			url: '{{ url('student/payment/get-payment-history') }}',
			data: {
				"id": id,
				"_token": "{{ csrf_token() }}"
			},
			success: function(response) {
				console.log(response);

				let html = '';
				html += '<table class="table table-bordered">' +
					'<thead>' +
					'<tr>' +
					'<th scope="col">Courses Name</th>' +
					'<th scope="col">Amount</th>' +
					'<th scope="col">Transaction ID</th>' +
					'<th scope="col">Purchase Date</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody>'+
					'<tr>'+
					'<td>' + response.get_course.title + '</td>' +
					'<td>' + response.total_amount + '</td>' +
					'<td>' + response.transaction_id + '</td>' +
					'<td>' + response.created_at + '</td>' +
					'</tr>';
					'</tbody>' +
				'</table>';
				html += '<table class="table table-bordered">' +
					'<thead>' +
					'<tr>' +
					'<th scope="col">#SL</th>' +
					'<th scope="col">Name</th>' +
					'<th scope="col">Amount</th>' +
					'<th scope="col">Created at</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody>';
				'<tr>';
				if (response.get_addons.length > 0) {
					for (let i = 0; i < response.get_addons.length; i++) {
						html += '<th scope="row">' + (i + 1) + '</th>' +
							'<td>' + response.get_addons[i].name + '</td>' +
							'<td>' + response.get_addons[i].amount + '</td>' +
							'<td>' + response.get_addons[i].created_at + '</td>' +
							'</tr>';
					}
				} else {
					html += '<td colspan="4">No Addons Found!</td></tr>'
				}
				html += '</tbody>' +
					'</table>';
				$('#bodyBox').html(html);
				$('#addonsModal').modal('show');

			}
		});
	}
	</script>
@endsection
