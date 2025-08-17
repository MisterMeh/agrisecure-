@extends('layouts.main')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cloud-upload-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Recent Uploads</span>
                    <span class="info-box-number">
                        {{ $recentUploads }}
                    </span>
                </div>
                </div>
            </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-archive"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Files Uploaded</span>
                    <span class="info-box-number">{{ $totalFiles }}</span>
                </div>
                </div>
            </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Registered Employees</span>
                    <span class="info-box-number">{{ $registeredEmployees }}</span>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection