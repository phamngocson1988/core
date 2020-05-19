<div class="modal-header d-block">
  <h2 class="modal-title text-center w-100 text-red text-uppercase">Order details</h2>
  <p class="text-center d-block">Order ID: #<?=$order->id;?></p>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-6 border-right">
      <div><span class="list-item">Game:</span><b><?=$order->game_title;?></b></div>
      <div><span class="list-item">Version:</span><b>Global</b></div>
      <div><span class="list-item">Pack:</span><b class="text-red">100 GEMS</b></div>
      <div><span class="list-item">Quantity:</span><b><?=number_format($order->quantity, 1);?></b></div>
      <div><span class="list-item">Total Unit:</span><b class="text-red"><?=sprintf("%s %s", $order->total_unit, $order->unit_name);?></b></div>
      <hr />
      <div><span class="list-item">Payment method:</span><b class="text-red"><?=$order->payment_method;?></b></div>
      <div><span class="list-item">Total Unit:</span><b class="text-red"><?=number_format($order->total_price, 1);?> USD</b></div>
    </div>
    <div class="col-md-6">
      <div><span class="list-item">Login method:</span><b><?=$order->login_method;?></b></div>
      <div><span class="list-item">Character name:</span><b><?=$order->character_name;?></b></div>
      <div><span class="list-item">Account login:</span><b><?=$order->username;?></b></div>
      <div><span class="list-item">Account password:</span><b><?=$order->password;?></b></div>
      <div><span class="list-item">Server:</span><b><?=$order->server;?></b></div>
      <div><span class="list-item">Recovery code:</span><b><?=$order->recover_code;?></b></div>
    </div>
    <div class="col-md-12 mt-4">
      <h3 class="text-center text-uppercase">delivery status</h3>
      <p class="text-center">1/8000 Completed</p>
      <div class="row bf-at">
        <div class="col-md-6 mb-4">
          <a href="#" data-toggle="modal" data-target="#img-modal">
            <img class="btn-modal-img" src="/images/post-item01.jpg" />
          </a>
        </div>
        <div class="col-md-6 mb-4">
          <a href="#" data-toggle="modal" data-target="#img-modal">
            <img class="btn-modal-img" src="/images/post-item01.jpg" />
          </a>
        </div>
      </div>
      <p class="text-center">
        <button type="button" class="btn btn-comfirm text-uppercase">comfirm delivery</button>
      </p>
      <p class="text-center mb-0">
        <b>Rate this order!</b>
      </p>
      <!-- Rating Stars Box -->
      <div class='rating-stars text-center'>
        <ul id='stars'>
          <li data-toggle="modal" data-target="#modalSurvey" class='star' title='Poor' data-value='1'>
            <span class="icon-star"></span>
          </li>
          <li data-toggle="modal" data-target="#modalSurvey" class='star' title='Fair' data-value='2'>
            <span class="icon-star"></span>
          </li>
          <li data-toggle="modal" data-target="#modalSurvey" class='star' title='Good' data-value='3'>
            <span class="icon-star"></span>
          </li>
          <li data-toggle="modal" data-target="#modalSurvey" class='star' title='Excellent' data-value='4'>
            <span class="icon-star"></span>
          </li>
          <li data-toggle="modal" data-target="#modalSurvey" class='star' title='WOW!!!' data-value='5'>
            <span class="icon-star"></span>
          </li>
        </ul>
      </div>

    </div>
  </div>
  <h4 class="text-uppercase text-right chat-admin-title">Chat ADMIN</h4>
  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Chat Box-->
    <div class="col-12 px-0">
      <div class="px-4 py-5 chat-box bg-white">
        <!-- Sender Message-->
        <div class="media w-50 mb-3"><img src="/images/icon/young.svg" alt="user" width="50"
            class="rounded-circle">
          <div class="media-body ml-3">
            <div class="bg-light rounded py-2 px-3 mb-2">
              <p class="text-small mb-0 text-muted">Anh và tôi thật ra gặp nhau và quen nhau cũng đã được mấy
                năm, mà chẳng có chi hơn lời hỏi thăm</p>
            </div>
            <p class="small text-muted">12:00 PM | Aug 13</p>
          </div>
        </div>

        <!-- Reciever Message-->
        <div class="media w-50 ml-auto mb-3">
          <div class="media-body">
            <div class="bg-primary rounded py-2 px-3 mb-2">
              <p class="text-small mb-0 text-white">rằng giờ này đã ăn sáng chưa? ở bên đấy nắng hay mưa?</p>
            </div>
            <p class="small text-muted">12:00 PM | Aug 13</p>
          </div>
        </div>

        <!-- Sender Message-->
        <div class="media w-50 mb-3"><img src="/images/icon/young.svg" alt="user" width="50"
            class="rounded-circle">
          <div class="media-body ml-3">
            <div class="bg-light rounded py-2 px-3 mb-2">
              <p class="text-small mb-0 text-muted">Anh và tôi thật ra Mm, Mmm mải mê nhìn lén nhau, Và không
                một ai nói nên câu</p>
            </div>
            <p class="small text-muted">12:00 PM | Aug 13</p>
          </div>
        </div>

        <!-- Reciever Message-->
        <div class="media w-50 ml-auto mb-3">
          <div class="media-body">
            <div class="bg-primary rounded py-2 px-3 mb-2">
              <p class="text-small mb-0 text-white">Rằng người ơi tôi đang nhớ anh, Và anh có nhớ tôi không?</p>
            </div>
            <p class="small text-muted">12:00 PM | Aug 13</p>
          </div>
        </div>

        <!-- Sender Message-->
        <div class="media w-50 mb-3"><img src="/images/icon/young.svg" alt="user" width="50"
            class="rounded-circle">
          <div class="media-body ml-3">
            <div class="bg-light rounded py-2 px-3 mb-2">
              <p class="text-small mb-0 text-muted">Tôi... từ lâu đã thích anh rồi, Chỉ mong hai ta thành đôi
              </p>
            </div>
            <p class="small text-muted">12:00 PM | Aug 13</p>
          </div>
        </div>

        <!-- Reciever Message-->
        <div class="media w-50 ml-auto mb-3">
          <div class="media-body">
            <div class="bg-primary rounded py-2 px-3 mb-2">
              <p class="text-small mb-0 text-white">Anh nhà ở đâu thế?</p>
            </div>
            <p class="small text-muted">12:00 PM | Aug 13</p>
          </div>
        </div>

      </div>

      <!-- Typing area -->
      <form action="#" class="bg-light">
        <div class="input-group">
          <div contentEditable="true" placeholder="Type a message" aria-describedby="button-addon2"
            class="form-control rounded-0 border-0 py-4 bg-light">
            Anh nhà ở đâu thế <img class="icon-md" src="/images/post-item01.jpg"> bla bla
          </div>
          <div class="input-group-append">
            <input class="d-none" type="file" id="FileUpload"/>
            <button onclick='$("#FileUpload").click()' id="button-addon2" type="file" class="btn btn-link">
              <img class="icon-sm" src="/images/icon/attach.svg" />
            </button>
          </div>
          <div class="input-group-append">
            <button id="button-addon2" type="submit" class="btn btn-link">
              <img class="icon-sm" src="/images/icon/send.svg" />
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>
  <!-- END CHATBOX -->
</div>