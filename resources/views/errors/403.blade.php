@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'You don’t have permission to access this page. Contact the administrator if you believe this is a mistake.'))
@section('error-title', 'Forbidden')