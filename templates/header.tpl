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
</head>

<body>

<!-- Begin Container -->
<div class="container">

<!-- Begin Menu Header -->
<nav class="navbar navbar-inverse">
<div class="container-fluid">
<div class="navbar-header">
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<a class="brand" href="{$gvar.index.link}"><img src="{$gvar.l_global}images/logo.png" /></a>
</div>
<div id="navbar" class="navbar-collapse collapse">
<ul class="nav navbar-nav">
    <li {if isset($active)}{if $active eq {$gvar.index.name}}class="active"{/if}{/if}><a href="{$gvar.index.link}">{$gvar.index.name}</a></li>
    {if isset($smarty.session.user) && $smarty.get.option neq 'logout'}
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin Panel <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="{$gvar.l_admin_index}">{$gvar.n_admin_index}</a></li>
        <li><a href="{$gvar.l_admin_control}">{$gvar.n_admin_control}</a></li>
      </ul>
    </li>   
    {/if}
	<li {if isset($active)}{if $active eq {$gvar.contact.name}}class="active"{/if}{/if}><a href="{$gvar.contact.link}">{$gvar.contact.name}</a></li>
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
</nav>
<!-- Begin End Header -->

<!-- Begin Content -->
<div id="content"> 
<!-- Begin Navigation -->
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
		<li><a href="{$gvar[$breadcrumb[$i]].link}">{$gvar[$breadcrumb[$i]].name}</a></li>
	{/while}
	<li class="active">{$gvar[$where].name}</li>
{/if}
</ol>
{*<table cellpadding="0" class="navigation"><tr><td align="left" valign="top">
<div class="where_middle"><div class="where_right"><div class="where_left">
<b>Navigation:</b>
{if isset($where)}
{section name=i loop=$where}{if !empty($where[i].link)}<a href="{$where[i].link}">{/if}{$where[i].name} {if !empty($where[i].link)}</a>{/if}
{if $smarty.section.i.total == 1}{else}{if $smarty.section.i.rownum == $smarty.section.i.total}{else}<b>>></b> 
{$i.index}{/if}{/if}
{/section}
{/if}
</div></div></div>
</td></tr></table>*}
<!-- End Navigation -->

<div class="col-md-2">
{include file='lateral.tpl'}
</div>
<div class="col-md-10">
