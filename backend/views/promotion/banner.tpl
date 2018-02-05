{use class='yii\helpers\Html'}
{use class='yii\widgets\ActiveForm' type='block'}
<div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="/">Home</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <a href="{url route='promotion/index'}">Manage Promotions</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <span>Create Banners</span>
        </li>
      </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Create Banners</h1>
    <!-- END PAGE TITLE-->
    <div class="row">
      <div class="col-md-12">
        {ActiveForm assign='form' options=['class' => 'form-horizontal form-row-seperated', 'id' => 'promotion-banner-form']}
          <div class="portlet">
            <div class="portlet-title">
              <div class="caption">Create Banners</div>
              <div class="actions btn-set">
                <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i> Save
                </button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="tabbable-bordered">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#tab_gallery" data-toggle="tab"> Main content</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_gallery">
                    {$form->field($model, 'images', [ 
                      'inputOptions' => ['id' => 'images'],
                      'template' => '{input}'
                    ])->hiddenInput()}
                    <div id="tab_images_uploader_container" class="text-align-reverse margin-bottom-10">
                        <a class="btn btn-success" id="upload-gallery"><i class="fa fa-plus"></i> Select Files </a>
                        <span class="help-block"> Image size is 1592x890 </span>
                    </div>
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr role="row" class="heading">
                          <th width="20%"> Image </th>
                          <th width="40%"> Order</th>
                          <th width="40%"> Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        {foreach $model->getGalleryImages() as $imageObject}
                        <tr>
                          <td>
                            <a href="{$imageObject->getUrl()}" class="fancybox-button" data-rel="fancybox-button">
                            <img class="img-responsive" src="{$imageObject->getUrl('150x150')}" alt=""> </a>
                            <input type="hidden" name="image_id[]" value="{$imageObject->id}">
                          </td>
                          <td>
                            <a href="javascript:;" class="btn btn-icon-only green go-up"><i class="fa fa-arrow-up"></i></a>
                            <a href="javascript:;" class="btn btn-icon-only red go-down"><i class="fa fa-arrow-down"></i></a>
                          </td>
                          <td>
                            <a href="javascript:;" class="btn btn-default btn-sm remove">
                            <i class="fa fa-times"></i> Remove </a>
                          </td>
                        </tr>
                        {/foreach}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {/ActiveForm}
      </div>
    </div>
  </div>
  <!-- END CONTENT BODY -->
</div>

{registerJs}
{literal}
// image
var manager = new ImageManager();

// gallery
$("#upload-gallery").selectImage(manager, {
  type: 'multiple',
  callback: function(imgs) {
    $.each( imgs, function( key, img ) {
      var _html = "";
      _html += '<tr>';
      _html += '<td>';
      _html += '<a href="javascript:;" class="fancybox-button" data-rel="fancybox-button">';
      _html += '<img class="img-responsive" src="' + img.src + '" alt=""> </a>';
      _html += '<input type="hidden" name="image_id[]" value="' + img.id + '">';
      _html += '</td>';
      _html += '<td>';
      _html += '<a href="javascript:;" class="btn btn-icon-only green go-up"><i class="fa fa-arrow-up"></i></a>';
      _html += '<a href="javascript:;" class="btn btn-icon-only red go-down"><i class="fa fa-arrow-down"></i></a>';
      _html += '</td>';
      _html += '<td>';
      _html += '<a href="javascript:;" class="btn btn-default btn-sm remove"><i class="fa fa-times"></i> Remove </a>';                        
      _html += '</td>';
      _html += '</tr>';
      $('#tab_gallery').find('tbody').append(_html);
    });
  }
});
$("#tab_gallery").on('click', '.remove', function() {
  $(this).closest('tr').fadeOut(300, function(){ $(this).remove();});
});
$("#promotion-banner-form").on('submit', function(event) {
  var ids = [];
  ids = $('input[name^=image_id]').map(function(idx, elem) {
    return $(elem).val();
  }).get();
  $("#images").val(ids.join());
})
{/literal}
{/registerJs}