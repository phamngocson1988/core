{if $app->session->hasFlash('error')}
<div class="alert alert-danger" role="alert">
  <ul style="display: inline-block">
  {foreach $app->session->getFlash('error') as $errors}
      {foreach $errors as $error}
          <li>{$error}</li>
      {/foreach}
  {/foreach}
  </ul>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
{/if}
{if $app->session->hasFlash('success')}
  <div class="alert alert-success" role="alert">
      {$app->session->getFlash('success')}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
  </div>
{/if}