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
<section class="section section-variant-2 bg-default novi-background bg-cover text-center">
  <div class="container container-wide">
    <div class="isotope-wrap row row-0 row-lg-30">
      <!-- Isotope Filters-->
      <div class="col-xl-12">
        <ul class="isotope-filters isotope-filters-horizontal">
          <li class="block-top-level">
            <p class="big">Choose your category:</p>
            <!-- Isotope Filters-->
            <button class="isotope-filters-toggle button button-xs button-primary" data-custom-toggle="#isotope-filters-list-1" data-custom-toggle-hide-on-blur="true">Filter<span class="caret"></span></button>
            <ul class="isotope-filters-list isotope-filters-list-buttons" id="isotope-filters-list-1">
              <li><a class="button-nina active" data-isotope-filter="*" data-isotope-group="movies" href="#">All movies</a></li>
              <li><a class="button-nina" data-isotope-filter="type 1" data-isotope-group="movies" href="#">Popular</a></li>
              <li><a class="button-nina" data-isotope-filter="type 2" data-isotope-group="movies" href="#">Trending</a></li>
              <li><a class="button-nina" data-isotope-filter="type 3" data-isotope-group="movies" href="#">Coming soon</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- Isotope Content-->
      <div class="col-xl-12">
        <div class="isotope isotope-md row" data-isotope-layout="fitRows" data-isotope-group="movies" data-lightgallery="group">
          <div class="row">
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-1-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Home Run</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-2-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Safe Haven</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-3-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Olympus Has Fallen</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-4-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Star Trek: Into Darkness</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-5-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Dark Knight</a></p>
                <p class="thumbnail-simple-subtitle">2008</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-6-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">World War Z</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-7-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Shadow</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-8-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Silver Linings Playbook</a></p>
                <p class="thumbnail-simple-subtitle">2012s</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-9-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">Les Misérables</a></p>
                <p class="thumbnail-simple-subtitle">2012</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 3">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-10-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Wicked</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 2">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-11-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Numbers Station</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xxl-2 isotope-item" data-filter="type 1">
              <!-- Thumbnail simple-->
              <div class="thumbnail-simple"><a class="thumbnail-simple-image-wrap" href="/product/view.html?id=1"><img class="thumbnail-simple-image" src="/images/landing-movie-12-270x400.jpg" alt="" width="270" height="400"/></a>
                <p class="thumbnail-simple-title"><a href="#">The Grandmaster</a></p>
                <p class="thumbnail-simple-subtitle">2013</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><a class="button button-secondary button-nina" href="#">view more movies</a>
  </div>
</section>

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

<!-- Twitter form-->
<section class="section section-variant-2 bg-image-11 novi-background custom-bg-image context-dark">
  <div class="container container-bigger">
    <div class="row row-fix row-50 justify-content-lg-between">
      <div class="col-sm-12 col-md-6 col-lg-5">
        <h3>get the latest news from the team of brave</h3>
        <hr class="divider divider-left divider-default">
        <p class="big">Keep up with our always upcoming product features and technologies. Enter your e-mail and subscribe to our newsletter.</p>
        <!-- Subscribe form-->
        <form class="rd-mailform" data-form-output="form-output-global" action="bat/rd-mailform.php" method="post" data-form-type="subscribe">
          <div class="form-wrap">
            <input class="form-input" type="email" name="email" data-constraints="@Email @Required" id="form-email-twitter">
            <label class="form-label" for="form-email-twitter">Enter your e-mail</label>
          </div>
          <button class="button form-button button-secondary button-nina" type="submit">Subscribe</button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- news and reviews-->
<section class="section section-variant-2 bg-gray-lighter novi-background bg-cover text-center">
  <div class="container container-bigger">
    <h3>news and reviews</h3>
    <div class="divider divider-default"></div>
    <div class="row row-50 justify-content-sm-center justify-content-lg-around justify-content-xxl-between offset-custom-2">
      <div class="col-md-6 col-xl-6 col-xxl-7">
        <article class="post-blog-large">
          <figure class="post-blog-large-image"><img src="/images/landing-movie-13-868x640.jpg" alt="" width="868" height="640"/>
          </figure>
          <ul class="post-blog-meta">
            <li><span>by</span>&nbsp;<a href="about-me.html">Ronald Chen</a></li>
            <li>
              <time datetime="2018">Feb, 27 2018 at 5:47 pm</time>
            </li>
          </ul>
          <div class="post-blog-large-caption">
            <ul class="post-blog-tags">
              <li><a class="button-tags" href="single-post.html">News</a></li>
            </ul><a class="post-blog-large-title" href="single-post.html">Tony Stark will Come Back</a>
            <p class="post-blog-large-text">Marvel Studios representatives confirmed that Iron Man will appear in several movies about Avengers and other projects.</p><a class="button button-xs button-secondary button-nina" href="single-post.html">Continue reading</a>
          </div>
        </article>
      </div>
      <div class="col-md-6 col-xl-4">
        <div class="post-minimal-wrap">
          <!-- Post minimal-->
          <article class="post-minimal post-minimal-md">
            <p class="post-minimal-title"><a href="single-post.html">What to Watch Now on Amazon Video</a></p>
            <time class="post-minimal-time" datetime="2018">Feb 27, 2018 at 5:47 pm</time>
          </article>
          <!-- Post minimal-->
          <article class="post-minimal post-minimal-md">
            <p class="post-minimal-title"><a href="single-post.html">Predict the Metascores for Summer's Biggest Movies</a></p>
            <time class="post-minimal-time" datetime="2018">Feb 27, 2018 at 5:47 pm</time>
          </article>
          <!-- Post minimal-->
          <article class="post-minimal post-minimal-md">
            <p class="post-minimal-title"><a href="single-post.html">14 Films to See in April</a></p>
            <time class="post-minimal-time" datetime="2018">Feb 27, 2018 at 5:47 pm</time>
          </article>
          <!-- Post minimal-->
          <article class="post-minimal post-minimal-md">
            <p class="post-minimal-title"><a href="single-post.html">DVD/BLU-RAY Release Calendar: April 2018</a></p>
            <time class="post-minimal-time" datetime="2018">Feb 27, 2018 at 5:47 pm</time>
          </article>
          <!-- Post minimal-->
          <article class="post-minimal post-minimal-md">
            <p class="post-minimal-title"><a href="single-post.html">What to Watch Now on Netflix</a></p>
            <time class="post-minimal-time" datetime="2018">Feb 27, 2018 at 5:47 pm</time>
          </article>
        </div>
      </div>
    </div><a class="button button-secondary button-nina" href="#">view all reviews</a>
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
