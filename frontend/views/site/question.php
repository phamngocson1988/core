<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center">
          <img src="/images/text-qa-title.png" alt="">
        </div>
      </div>
    </div>
  </div>
</section>
<section class="promotion-search">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <form action="#" class="">
          <input type="text" placeholder="Got a question? Find it here...">
          <input type="submit" value="">
        </form>
      </div>
    </div>
  </div>
</section>
<section class="qa-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-12 col-sm-12 col-md-9 col-lg-9">
          <div class="qa-listing">
            <div class="qa-section">
              <div class="qa-section-title">
                G2G Knowledge Base
              </div>
              <div class="qa-section-list">
                <div class="row">
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="qa-section">
              <div class="qa-section-title">
                G2G Knowledge Base
              </div>
              <div class="qa-section-list">
                <div class="row">
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                  <div class="qa-item col col-12 col-lg-6">
                    <a class="qa-img" href="#"><img src="/images/qa-avatar.png" alt=""></a>
                    <div class="qa-group">
                      <a href="#" class="qa-group-name"><b>Account</b> (19)</a>
                      <p>Getting started</p>
                      <p>Add</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col col-12 col-sm-12 col-md-3 col-lg-3">
          Fanpage Box Here
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('.qa-section .qa-section-title').click(function(){
  $('.qa-section .qa-section-list.showing').slideToggle().removeClass('showing');
  $(this).next().slideToggle().addClass('showing');
});
JS;
$this->registerJs($script);
?>
