<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="{$gvar.l_global}favicon.ico" />
<title>{$title}</title>
{literal} 
<style type="text/css"> @import url({/literal}{$gvar.l_global}{literal}css/bootstrap.css); </style>
<style type="text/css"> @import url({/literal}{$gvar.l_global}{literal}css/bootstrap-responsive.css); </style>
<style type="text/css"> @import url({/literal}{$gvar.l_global}{literal}css/t_dark.css); </style>
<script type='text/javascript'>l_global = '{/literal}{$gvar.l_global}{literal}';</script>
<script src="{/literal}{$gvar.l_global}{literal}js/jquery-1.7.2.min.js" language="Javascript"></script>
<script src="{/literal}{$gvar.l_global}{literal}js/bootstrap.js" language="Javascript"></script>
{/literal}
</head>

<body>
<div id="top-background">
<!-- Begin Container -->
<div class="container">

<!-- Begin Menu Header -->
<div class="navbar navbar-inverse">
<div class="navbar-inner">
<div class="container" style="width: auto;">
<a class="brand" href="{$gvar.l_index}"><img src="{$gvar.l_global}images/logo.png" /></a>
<ul class="nav">
    <li {if isset($active)}{if $active eq {$gvar.n_index}}class="active"{/if}{/if}><a href="{$gvar.l_index}">{$gvar.n_index}</a></li>
    {if isset($smarty.session.user) && $smarty.get.option neq 'logout'}
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin Panel <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="{$gvar.l_admin_index}">{$gvar.n_admin_index}</a></li>
        <li><a href="{$gvar.l_admin_control}">{$gvar.n_admin_control}</a></li>
      </ul>
    </li>
    <li><a href="{$gvar.l_index}?option=logout">{$gvar.n_logout}</a></li>
    {else}
    <li><a href="{$gvar.l_index}#login">{$gvar.n_login}</a></li>
    {/if}
</ul>
  <form class="navbar-search pull-left" action="">
    <input type="text" class="search-query span2" placeholder="Search">
  </form>
<ul class="nav pull-right">
    <li {if isset($active)}{if $active eq {$gvar.n_contact}}class="active"{/if}{/if}><a href="{$gvar.l_contact}">{$gvar.n_contact}</a></li>
    <li class="divider-vertical"></li>
    <a href="http://twitter.com/frameworkg"><img src="{$gvar.l_global}images/twtt.png" /></a>
</ul>
</div>
</div>
</div>
<!-- Begin End Header -->

<!-- Begin Content -->
<div id="content"> 

<!-- Begin Navigation -->
<table cellpadding="0" class="navigation"><tr><td align="left" valign="top">
<div class="where_middle"><div class="where_right"><div class="where_left">
<b>Navigation:</b>
{if isset($where)}
{section name=i loop=$where}{if !empty($where[i].link)}<a href="{$where[i].link}">{/if}{$where[i].name} {if !empty($where[i].link)}</a>{/if}
{if $smarty.section.i.total == 1}{else}{if $smarty.section.i.rownum == $smarty.section.i.total}{else}<b>>></b> 
{$i.index}{/if}{/if}
{/section}
{/if}
</div></div></div>
</td></tr></table>
<!-- End Navigation -->

<table cellpadding="0" cellspacing="10">
<tr><td align="left" width="210px" valign="top">
{include file='lateral.tpl'}
</td>
<td align="left" valign="top">