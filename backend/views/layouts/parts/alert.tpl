{if $app->session->hasFlash('error')}
<div class="alert alert-danger" role="alert">
  <ul>
  {foreach $app->session->getFlash('error') as $errors}
      {foreach $$errors as $error}
          <li>{$error}</li>
      {/foreach}
  {/foreach}
  </ul>
</div>
{/if}
{if $app->session->hasFlash('success')}
  <div class="alert alert-success" role="alert">
      {$app->session->getFlash('success')}
  </div>
{/if}