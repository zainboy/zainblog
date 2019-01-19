@extends('layouts.bootstraps')
@section('title', 'zainphp')
@section('content')
	<style type="text/css">
		html {
			font-size: 100%;
		}
		.content {
		    position: absolute;
		    height: 300px;
		    width: 300px;
			line-height:300px;
		    left: 50%;
		    top: 50%;
			text-shadow: 1px 1px 1px #999;
		    margin-left: -150px;
		    margin-top: -150px;
		    text-align: center;
			font-size:5em;
			color: #59A3E1;
		}
	</style>
    <div class="content">
		ZainPHP
        {{--<img src="/img/zainphp.png">--}}
	</div>
@endsection