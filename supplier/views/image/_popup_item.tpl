<div class="col-md-2 image-item" data-id="{$model->getId()}">
  <div class="thumbnail" style="width: 100%; height: 100%">
    <div class="view view-first">
      <img style="width: 100%; display: block;" src="{$model->getUrl('150x150')}" alt="image" />
      {foreach $app->params['thumbnails'] as $thumbnail}
      <input type="hidden" name="{$thumbnail}" value="{$model->getUrl($thumbnail)}" data-id="{$model->getId()}">
      {/foreach}
    </div>
  </div>
</div>
