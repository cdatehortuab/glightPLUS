<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="{$gvar.l_global}favicon.ico" />
<title>{$title}</title>
{if $gvar.environment eq 'development'}
<link href="{$gvar.l_global}css/bootstrap.css" rel="stylesheet">
<link href="{$gvar.l_global}css/bootstrap-theme.css" rel="stylesheet">
<script type='text/javascript'>l_global = '{$gvar.l_global}';</script>
<script src="{$gvar.l_global}js/jquery-1.11.3.js"></script>
<script src="{$gvar.l_global}js/bootstrap.js"></script>
{elseif $gvar.environment eq 'production'}
<link href="{$gvar.l_global}css/bootstrap.min.css" rel="stylesheet">
<link href="{$gvar.l_global}css/bootstrap-theme.min.css" rel="stylesheet">
<script type='text/javascript'>l_global = '{$gvar.l_global}';</script>
<script src="{$gvar.l_global}js/jquery-1.11.3.min.js"></script>
<script src="{$gvar.l_global}js/bootstrap.min.js"></script>
{/if}
<script src="{$gvar.l_global}js/npm.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body>

<!-- Begin Container -->
<div class="container">
<br/>
<!-- Begin Menu Header -->
<header class="navbar navbar-inverse">
<div class="container-fluid">
<div class="navbar-header">
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<a class="navbar-brand" href="{$gvar.index.link}"><img src="{$gvar.l_global}images/logo.png" /></a>
</div>
<div id="navbar" class="navbar-collapse collapse">
<ul class="nav navbar-nav">
    <li {if isset($active)}{if $active eq {$gvar.index.link}}class="active"{/if}{/if}><a href="{$gvar.index.link}">{$gvar.index.name.$lang}</a></li>
    {if isset($smarty.session.user) && $smarty.get.option neq 'logout'}
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin Panel <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="{$gvar.l_admin_index}">{$gvar.n_admin_index}</a></li>
        <li><a href="{$gvar.l_admin_control}">{$gvar.n_admin_control}</a></li>
      </ul>
    </li>   
    {/if}
	<li {if isset($active)}{if $active eq {$gvar.contact.link}}class="active"{/if}{/if}><a href="{$gvar.contact.link}">{$gvar.contact.name.$lang}</a></li>
</ul>
<ul class="nav navbar-nav navbar-right">
	{if isset($smarty.session.user) && $smarty.get.option neq 'logout'}    
		<li><a href="{$gvar.index.link}?option=logout">{$gvar.n_logout}</a></li>
	{else}
		<li><a href="{$gvar.index.link}#login">{$gvar.n_login}</a></li>
	{/if}
</ul>
</div>
</div>
</header>
<!-- Begin End Header -->

<!-- Begin Navigation -->
<nav>
<ol class="breadcrumb">
{if isset($where)}
	{$page = $where}
	{$i = 0}
	{$breadcrumb = array()}
	{while $page != null}
		{$breadcrumb[$i] = $page}
		{$page = $gvar[$page].father}
		{$i = $i + 1}
	{/while}
	{while $i > 1}
		{$i = $i - 1}
		<li><a href="{$gvar[$breadcrumb[$i]].link}">{$gvar[$breadcrumb[$i]].name.$lang}</a></li>
	{/while}
	<li class="active">{$gvar[$where].name.$lang}</li>
{/if}
</ol>
</nav>
<!-- End Navigation -->

<div class="col-sm-3 col-md-2">
<aside>
{include file='lateral.tpl'}
</aside>
</div>

<div class="col-sm-9 col-md-10">
<section>