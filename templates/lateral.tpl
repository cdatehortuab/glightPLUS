{if isset($smarty.session.user) && $smarty.get.option neq 'logout'}
<div class="row">
<div class="well well-small">
<b>Welcome {$smarty.session.user.name}</b><br /><br /><img src="{$gvar.l_global}images/admin.png" /><br /><br />
<button class="btn" onClick="location.href='{$gvar.l_index}?option=logout'">{$gvar.n_logout}</button>
</div>
</div>
{else}   
<div class="row">
<form role="form" class="well well-small" action="{$gvar.index.link}?option=login" method="post" name="login">
<b><a name="login">{$gvar.n_login}</a></b><br /><br />
<div class="form-group">
<label class="sr-only" for="login-user">User</label>
<input id="login-user" name="user" type="text" class="form-control" placeholder="User" required>
</div>
<div class="form-group">
<label class="sr-only" for="login-password">Password</label>
<input id="login-password" name="password" type="password" class="form-control" placeholder="Password" required>
</div>
<button type="submit" class="btn btn-default">{$gvar.n_login}</button>
</form>
</div>
{/if}

<div class="row">
<h4>Menu</h4>
<div class="well well-small" style="padding: 8px 0;">
    <ul class="nav nav-pills nav-stacked">
      <li {if $active eq {$gvar.index.name}}class="active"{/if}><a href="{$gvar.index.link}">{$gvar.index.name}</a></li>
      <li {if $active eq {$gvar.contact.name}}class="active"{/if}><a href="{$gvar.contact.link}">{$gvar.contact.name}</a></li>
    </ul>
</div>
</div>