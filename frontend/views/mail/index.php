<main>
        <div class="section-user-message-wrapper">
          <section class="section-user-storage">
            <div class="block-storage">
              <div class="progress-meter"><span style="width:40%"></span></div>
              <div class="progress-text">Used 0% message storage</div>
            </div>
          </section>
          <section class="section-user-message container">
            <aside class="sec-sidebar">
              <div class="block-header">
                <div class="header-title">Inbox</div>
                <div class="header-button"><a class="btn btn-primary btn-sm" href="#">Compose new</a></div>
              </div>
              <div class="block-main widget-box">
                <div class="box-title widget-head">
                  <div class="head-text">Messages</div>
                  <div class="head-button">
                    <div class="dropdown">
                      <div class="btn btn-sm dropdown-toggle" id="dropdown-select" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <input type="checkbox">
                      </div>
                      <div class="dropdown-menu" aria-labelledby="dropdown-select"><a class="dropdown-item" href="#">All</a><a class="dropdown-item" href="#">None</a></div>
                    </div>
                  </div>
                </div>
                <ul class="list-message" id="js-list-message">
                  <li>
                    <div class="col-avatar"><a class="user-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a></div>
                    <div class="col-content">
                      <div class="message-title"><a href="#">I have a question for you</a></div>
                      <div class="message-info">
                        <div class="sender"><a href="#">Username</a></div>
                        <div class="date">2 hours ago</div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="col-avatar"><a class="user-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a></div>
                    <div class="col-content">
                      <div class="message-title"><a href="#">I have a question for you</a></div>
                      <div class="message-info">
                        <div class="sender"><a href="#">Username</a></div>
                        <div class="date">2 hours ago</div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="col-avatar"><a class="user-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a></div>
                    <div class="col-content">
                      <div class="message-title"><a href="#">I have a question for you</a></div>
                      <div class="message-info">
                        <div class="sender"><a href="#">Username</a></div>
                        <div class="date">2 hours ago</div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="col-avatar"><a class="user-photo" href="#"><img src="../img/common/sample_img_00.png" alt="Username"></a></div>
                    <div class="col-content">
                      <div class="message-title"><a href="#">I have a question for you</a></div>
                      <div class="message-info">
                        <div class="sender"><a href="#">Username</a></div>
                        <div class="date">2 hours ago</div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </aside>
            <div class="sec-main" id="js-message-main">
              <div class="sec-empty"><i class="fa fa-envelope"></i>
                <p>No message selected</p>
              </div>
              <div class="sec-button d-block d-md-none"><a class="btn btn-sm btn-primary js-back" href="#">Back</a></div>
              <div class="sec-message-view review-list widget-box">
                <article class="review-item complaint-item">
                  <div class="review-user">
                    <div class="user-photo"><img src="../img/common/sample_img_00.png" alt="Username"></div>
                    <div class="user-name"><a href="#">Username</a></div>
                  </div>
                  <div class="review-content">
                    <div class="review-complaint-heading">
                      <h3 class="complaint-title">I have a question for you</h3>
                      <div class="review-date">An hour ago</div>
                    </div>
                    <div class="review-text">
                      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa nam cumque maxime, officia aut expedita libero? Deleniti reiciendis accusamus, modi, dolor temporibus a quam accusantium soluta, voluptate eius ullam maiores.</p>
                      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa nam cumque maxime, officia aut expedita libero? Deleniti reiciendis accusamus, modi, dolor temporibus a quam accusantium soluta, voluptate eius ullam maiores.</p>
                      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa nam cumque maxime, officia aut expedita libero? Deleniti reiciendis accusamus, modi, dolor temporibus a quam accusantium soluta, voluptate eius ullam maiores.</p>
                    </div>
                    <div class="review-comments">
                      <div class="review-comment">
                        <div class="review-comment-header">
                          <div class="user-photo"><img src="../img/common/sample_img_00.png" alt="Username"></div>
                          <div class="user-name">You</div>
                          <div class="comment-date">Replied on February 8, 2020</div>
                        </div>
                        <div class="review-comment-content">
                          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro voluptatem ratione fugiat quod voluptas quam ducimus maiores temporibus dolorem facere illum nostrum, a nobis reiciendis expedita saepe repellat quia sapiente.</p>
                        </div>
                      </div>
                      <div class="review-comment">
                        <div class="review-comment-header">
                          <div class="user-photo"><img src="../img/common/sample_img_00.png" alt="Username"></div>
                          <div class="user-name">You</div>
                          <div class="comment-date">Replied on February 8, 2020</div>
                        </div>
                        <div class="review-comment-content">
                          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro voluptatem ratione fugiat quod voluptas quam ducimus maiores temporibus dolorem facere illum nostrum, a nobis reiciendis expedita saepe repellat quia sapiente.</p>
                        </div>
                      </div>
                    </div>
                    <div class="review-reply">
                      <div class="form-group">
                        <textarea class="form-control" rows="5" placeholder="Reply..."></textarea>
                      </div>
                      <div class="form-group form-check">
                        <input class="form-check-input" id="option-close" type="checkbox">
                        <label class="form-check-label" for="option-close">Mark to close this case</label>
                      </div>
                      <div class="form-group">
                        <button class="btn btn-primary" type="submit">Post my reply</button>
                      </div>
                    </div>
                  </div>
                </article>
              </div>
            </div>
          </section>
        </div>
      </main>