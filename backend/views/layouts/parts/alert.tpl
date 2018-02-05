{if $app->session->hasFlash('error')}
<div class="alert alert-danger" role="alert">
  {$app->session->getFlash('error')}
</div>
{/if}