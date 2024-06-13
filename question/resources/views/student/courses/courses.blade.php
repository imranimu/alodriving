@extends('layouts.student.layer')
@section('title', 'Dashboard | Driving School')
@section('content')

    <!-- course area start -->
    <div class="course-area pb-5">
        <div class="container">
            <div class="row">

                
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 text-center">
                    <div class="wrap">
   
                        <div class="row home-grids justify-content-center">
                            <div class="col-md-3">
                                <a href="{{ url('student/course-lists') }}">
                                    <div class="home-grid bg-info">
                                        <i class="fa fa-book"></i>
                                        <h6>Goto Course</h6>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="#">
                                    <div class="home-grid bg-success">
                                        <i class="fa fa-refresh"></i>
                                        <h6>Course Summary</h6>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="clearfix"></div>
                  
                </div>

            </div>
        </div>
    </div>
@endsection
