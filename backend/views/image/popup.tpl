<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="image-popup-form">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Gallery</h4>
      </div>
      <div class="modal-body" style="height: 400px; overflow: scroll;">
        <div class="row" id="popup-items">
        </div>
        <div class="row">
          <div class="col-md-4 col-md-offset-4">
            <button type="button" class="btn btn-default btn-block" id="load_more_popup">Load More</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="false">Size <span class="caret"></span></button>
          <ul role="size" class="dropdown-menu">
            {foreach $app->params['thumbnails'] as $thumbnail}
            <li><a href="javascript:void(0)" {if $default_thumbnail eq $thumbnail}class="selected"{/if} value="{$thumbnail}"><i class="fa fa-check "></i> <span class="search-option">{$thumbnail}</span></a></li>
            {/foreach}
          </ul>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default" function="upload">Upload</button>
          <input type="file" name="popup-upload-image[]" multiple="true" id="popup-upload-image" style="display: none">
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-primary" function="ok">Choose</button>
        </div>
      </div>

    </div>
  </div>
</div>

{registerJs}
{literal}

{/literal}
{/registerJs}