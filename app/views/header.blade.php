<div class="header">
	<nav class="navbar navbar-default">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">ZainBoy</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li @if($segment[1] == '') class="active" @endif><a href="/">首页</a></li>
					{{--<li><a href="javascript:void(0)">ZainPHP</a></li>
					<li><a href="javascript:void(0)">ZainLive</a></li>--}}
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">分类 <span class="caret"></span></a>
						<ul class="dropdown-menu">
							@foreach($sorts as $sort)
								<li class="cate-{{$sort->id}} @if($segment[1]=='sort' && $segment[2]==$sort->id) active @endif"><a href="/sort/{{$sort->id}}">{{$sort->name}}</a></li>
							@endforeach
						</ul>
					</li>
				</ul>
				<form class="navbar-form navbar-right" action="/search">
					<div class="form-group">
						<input type="text" class="form-control" name="keyword" placeholder="文章标题">
					</div>
					<button type="submit" class="btn btn-default">搜索</button>
				</form>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container -->
	</nav>
</div>