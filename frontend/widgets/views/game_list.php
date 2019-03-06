<?php
use yii\helpers\Url;
?>
<section class="section section-variant-2 bg-default novi-background bg-cover text-center">
  <div class="container container-wide">
    <div class="isotope-wrap row row-0 row-lg-30">
      <!-- Isotope Filters-->
      <div class="col-xl-12">
        <div class="isotope isotope-md row" data-isotope-layout="fitRows" data-isotope-group="movies" data-lightgallery="group">
          <div class="row">
            <?php foreach ($models as $model) : ?>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="<?=Url::to(['game/view', 'id' => $model->id, 'title' => 'aaaa']);?>"><img class="thumbnail-simple-image" src="<?=$model->getImageUrl('270x400');?>" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="<?=Url::to(['game/view', 'id' => $model->id]);?>"><?=$model->title;?></a></p>
               <!--  <p class="thumbnail-simple-subtitle">2013</p> -->
              </div>
            </div>
            <?php endforeach;?>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-2-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Safe Haven</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-3-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Olympus Has Fallen</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-4-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Star Trek: Into Darkness</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-5-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Dark Knight</a></p>
                <p class="thumbnail-simple-subtitle">2008</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-6-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">World War Z</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-7-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Shadow</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-8-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Silver Linings Playbook</a></p>
                <p class="thumbnail-simple-subtitle">2012s</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-9-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Les Misérables</a></p>
                <p class="thumbnail-simple-subtitle">2012</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-10-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Wicked</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-11-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Numbers Station</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/game/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-12-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Grandmaster</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>