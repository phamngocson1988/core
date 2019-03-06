<?php
$this->title = 'Home Page';
?>
<!-- Swiper-->
<section class="section swiper-container swiper-slider swiper-type-1" data-loop="true" data-autoplay="5500" data-simulate-touch="false" data-slide-effect="fade">
  <div class="swiper-wrapper bg-gray-darker">
    <div class="swiper-slide" data-slide-bg="/images/landing-movie-slide-1-1920x750.jpg">
      <div class="swiper-slide-caption">
        <div class="section-xl">
          <div class="container container-wide">
            <div class="row row-fix">
              <div class="col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <p class="bigger">IMDb 7.0</p>
                <h3>Oblivion</h3>
                <p class="big">A veteran assigned to extract Earth's remaining resources begins to question what he knows about his mission and himself.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="swiper-slide" data-slide-bg="/images/landing-movie-slide-2-1920x750.jpg">
      <div class="swiper-slide-caption">
        <div class="section-xl">
          <div class="container container-wide">
            <div class="row row-fix">
              <div class="col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <p class="bigger">IMDb 6.5</p>
                <h3>Snitch</h3>
                <p class="big">A father goes undercover for the DEA in order to free his son, who was imprisoned after being set up in a drug deal.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="swiper-slide" data-slide-bg="/images/landing-movie-slide-3-1920x750.jpg">
      <div class="swiper-slide-caption">
        <div class="section-xl">
          <div class="container container-wide">
            <div class="row row-fix">
              <div class="col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <p class="bigger">IMDb 7.6</p>
                <h3>Les Misérables</h3>
                <p class="big">In 19th-century France, Jean Valjean, who for decades has been hunted by the ruthless policeman Javert after breaking parole, agrees to care for a factory worker's daughter. </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Swiper controls-->
  <div class="swiper-buttons">
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</section>

<!-- Button isotope-->
<?= \frontend\widgets\GameListWidget::widget();?>

<!-- Small Features-->
<section class="section section-variant-2 bg-gray-lighter novi-background bg-cover">
  <div class="container container-wide">
    <div class="row row-50 justify-content-sm-center text-gray-light">
      <div class="col-sm-10 col-md-6 col-xl-3">
        <article class="box-minimal">
          <div class="box-minimal-header">
            <div class="box-minimal-icon novi-icon fl-bigmug-line-monitor74"></div>
            <h6 class="box-minimal-title">online cinema</h6>
          </div>
          <p>At Brave Cinema, you can watch any movie for free or at an affordable price (depending on the movie or TV series you are looking for).  We have more than 500 movies as of now.</p>
        </article>
      </div>
      <div class="col-sm-10 col-md-6 col-xl-3">
        <article class="box-minimal">
          <div class="box-minimal-header">
            <div class="box-minimal-icon novi-icon fl-bigmug-line-note35"></div>
            <h6 class="box-minimal-title">reviews</h6>
          </div>
          <p>Choose what you want to watch according to the reviews of movie critics and users of our website. You can also write your own review for any movie or TV series available at Brave.</p>
        </article>
      </div>
      <div class="col-sm-10 col-md-6 col-xl-3">
        <article class="box-minimal">
          <div class="box-minimal-header">
            <div class="box-minimal-icon novi-icon fl-bigmug-line-video163"></div>
            <h6 class="box-minimal-title">movie news</h6>
          </div>
          <p>Read the latest news from our journalists about the facts, rumors, and updates from the movie world. Don’t forget to subscribe to our newsletter to receive the upcoming news.</p>
        </article>
      </div>
      <div class="col-sm-10 col-md-6 col-xl-3">
        <article class="box-minimal">
          <div class="box-minimal-header">
            <div class="box-minimal-icon novi-icon fl-bigmug-line-megaphone11"></div>
            <h6 class="box-minimal-title">regular promotions</h6>
          </div>
          <p>If you are a PR manager of a movie studio, our promo toolkit is just what you need! Promote your movie with trailers and teasers, start a blog, and publish articles about your studio.</p>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- Gallery-->
<section class="section">
  <div class="row no-gutters" data-lightgallery="group">
    <div class="col-sm-12 col-md-6 col-lg-4"><a class="gallery-item gallery-item-fullwidth" href="/images/landing-movie-14-810x1200.jpg" data-lightgallery="item">
        <div class="gallery-item-image">
          <figure><img src="/images/landing-movie-14-640x430.jpg" alt="" width="640" height="430"/>
          </figure>
          <div class="caption">
            <p class="caption-title">photo #1</p>
            <p class="caption-text">We have a variety of movies for you to enjoy. Just choose and download!</p>
          </div>
        </div></a>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4"><a class="gallery-item gallery-item-fullwidth" href="/images/landing-movie-15-810x1200.jpg" data-lightgallery="item">
        <div class="gallery-item-image">
          <figure><img src="/images/landing-movie-15-640x430.jpg" alt="" width="640" height="430"/>
          </figure>
          <div class="caption">
            <p class="caption-title">photo #2</p>
            <p class="caption-text">We have a variety of movies for you to enjoy. Just choose and download!</p>
          </div>
        </div></a>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4"><a class="gallery-item gallery-item-fullwidth" href="/images/landing-movie-16-810x1200.jpg" data-lightgallery="item">
        <div class="gallery-item-image">
          <figure><img src="/images/landing-movie-16-640x430.jpg" alt="" width="640" height="430"/>
          </figure>
          <div class="caption">
            <p class="caption-title">photo #3</p>
            <p class="caption-text">We have a variety of movies for you to enjoy. Just choose and download!</p>
          </div>
        </div></a>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4"><a class="gallery-item gallery-item-fullwidth" href="/images/landing-movie-17-810x1200.jpg" data-lightgallery="item">
        <div class="gallery-item-image">
          <figure><img src="/images/landing-movie-17-640x430.jpg" alt="" width="640" height="430"/>
          </figure>
          <div class="caption">
            <p class="caption-title">photo #4</p>
            <p class="caption-text">We have a variety of movies for you to enjoy. Just choose and download!</p>
          </div>
        </div></a>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4"><a class="gallery-item gallery-item-fullwidth" href="/images/landing-movie-18-810x1200.jpg" data-lightgallery="item">
        <div class="gallery-item-image">
          <figure><img src="/images/landing-movie-18-640x430.jpg" alt="" width="640" height="430"/>
          </figure>
          <div class="caption">
            <p class="caption-title">photo #5</p>
            <p class="caption-text">We have a variety of movies for you to enjoy. Just choose and download!</p>
          </div>
        </div></a>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4"><a class="gallery-item gallery-item-fullwidth" href="/images/landing-movie-19-1200x750.jpg" data-lightgallery="item">
        <div class="gallery-item-image">
          <figure><img src="/images/landing-movie-19-640x430.jpg" alt="" width="640" height="430"/>
          </figure>
          <div class="caption">
            <p class="caption-title">photo #6</p>
            <p class="caption-text">We have a variety of movies for you to enjoy. Just choose and download!</p>
          </div>
        </div></a>
    </div>
  </div>
</section>
