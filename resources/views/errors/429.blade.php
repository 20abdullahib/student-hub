@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('You’ve made too many requests. Please wait a moment and try again.'))
@section('error-title', 'Too Many Requests')