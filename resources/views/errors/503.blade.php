@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('We’re currently down for maintenance. Please check back soon.'))
@section('error-title', 'Service Unavailable')