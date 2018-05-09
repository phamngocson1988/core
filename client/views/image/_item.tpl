<div class="col-md-55 image-item" data-id="{$model->getId()}">
  <div class="thumbnail">
    <div class="view view-first">
      <img style="width: 100%; display: block;" src="{$model->getUrl('150x150')}" alt="image" />
      <div class="mask">
        <div class="tools tools-bottom">
          <a href="{$model->getUrl('150x150')}" target="_blank" class="copy"><i class="fa fa-files-o"></i></a>
          <a href="{$model->getUrl()}" target="_blank"><i class="fa fa-link"></i></a>
          <a href="{url route='image/ajax-delete' id=$model->getId()}" class="delete"><i class="fa fa-times"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>