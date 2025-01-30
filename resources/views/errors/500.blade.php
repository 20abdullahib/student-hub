@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Something went wrong on our end. We’re working to fix it. Please try again later.'))
@section('error-title', 'Server Error')