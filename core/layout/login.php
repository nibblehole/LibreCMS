<!DOCTYPE HTML>
<html lang="<?php echo$config['language'];?>" id="libreCMS">
	<head>
		<meta name="generator" content="LibreCMS">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<title>LibreCMS - <?php lang('Administration');?></title>
		<base href="<?php echo URL;?>">
<?php /*		<meta http-equiv="X-FRAME-OPTIONS" content="DENY"> */?>
		<link rel="alternate" media="handheld" href="<?php echo URL;?>">
		<link rel="alternate" hreflang="x-default" href="<?php echo URL;?>">
		<link rel="alternate" hreflang="<?php echo$config['language'];?>" href="<?php echo URL;?>">
		<link rel="icon" href="<?php echo URL.'/'.$favicon;?>">
		<link rel="apple-touch-icon" href="<?php echo URL.$favicon;?>">
		<meta name="viewport" content="width=400,initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="core/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="core/css/libreicons.css">
		<link rel="stylesheet" type="text/css" href="core/css/admin.css">
	</head>
	<body>
		<div class="container">
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
				<div class="login-panel animated fadeInDown">
					<img class="login img-responsive animated zoomIn" src="core/images/librecms.png" alt="LibreCMS">
					<div class="panel panel-default shadow-depth-1-half">
						<div class="panel-body">
							<form role="form" method="post" action="<?php if(strpos(URL,'logout')!=='false')echo rtrim(URL,'logout').'admin';?>" accept-charset="UTF-8">
								<h4 class="page-header"><?php lang('title','login');?></h4>
								<input type="hidden" name="act" value="login">
								<div class="form-group">
									<label for="username" class="control-label hidden-xs col-sm-4 col-md-5 col-lg-4"><?php lang('label','username');?></label>
									<div class="input-group col-xs-12 col-sm-8 col-md-7 col-lg-8">
										<input type="text" id="username" class="form-control" name="username" value="" placeholder="<?php lang('placeholder','username');?>" autofocus>
									</div>
								</div>
								<div class="form-group">
									<label for="password" class="control-label hidden-xs col-sm-4 col-md-5 col-lg-4"><?php lang('label','password');?></label>
									<div class="input-group col-xs-12 col-sm-8 col-md-7 col-lg-8">
										<input type="password" id="password" class="form-control" name="password" placeholder="<?php lang('placeholder','password');?>" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<div class="input-group col-xs-12 col-sm-8 col-md-7 col-lg-8 pull-right">
										<button class="btn btn-success btn-large btn-block text-bold"><?php lang('button','login');?></button>
									</div>
								</div>
							</form>
						</div>
						<div class="panel-footer hidden-xs text-right">
							<a target="_blank" href="https://github.com/StudioJunkyard/LibreCMS">GitHub</a>
							&nbsp;&nbsp;
							<a href="<?php echo URL;?>"><?php lang('Front');?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
		