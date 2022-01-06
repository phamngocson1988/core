<hr/>
<div id="<?=$commentListSectionId;?>">
  <div id="comment-list">
    <div class="d-flex align-items-center justify-content-between">
        <h3 class="mb-0" id="total">{{ total }} comments</h3>
        <select class="form-select" id='sort-comment' v-model="sort" aria-label="Sort comments by" @change="changeSorting">
          <option value="asc">Increase</option>
          <option value="desc">Descrease</option>
        </select>
    </div>
    <!-- Comment level 1-->
    <div class="my-4 d-flex" v-for="comment in comments" :key="comment.id">
        <img class="avatar avatar-md rounded-circle float-start me-3" src="/images/img_avatar2.png" alt="avatar">
        <div style="width: 100%">
          <div class="mb-2">
              <h5 class="m-0">{{ comment.creator}}</h5>
          </div>
          <p class="mb-0">{{ comment.comment }}</p>
          <div class="cmt-info">
              <span class="me-3 small">{{ comment.created_at }}</span>
              <a class="text-body text-reply" @click.stop="comment.showReply = !comment.showReply">Reply</a>
          </div>

          <!-- Comment children level 2 -->
          <div class="my-4 d-flex" v-for="reply in comment.children" :key="reply.id">
              <img class="avatar avatar-md rounded-circle float-start me-3" src="/images/img_avatar2.png" alt="avatar">
              <div>
                <div class="mb-2">
                    <h5 class="m-0">{{ reply.creator}}</h5>
                </div>
                <p class="mb-0">{{ reply.comment }}</p>
                <div class="cmt-info">
                    <span class="me-3 small">{{ reply.created_at }}</span>
                </div>
              </div>
          </div>

          <div class="row" v-show="comment.showReply">
              <div class="col-12">
                <textarea class="form-control mt-2" rows="3" v-model="comment.replyContent"></textarea>
              </div>
              <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary" @click="addReply(comment.id)">Reply</button>
              </div>
          </div>
        </div>
    </div>

    <div class="text-center py-3">
        <button type="button" class="btn btn-link text-dark font-weight-bold" id="load-more-comments" v-show="total" @click="loadMore()">Load more</button>
        <span class="text-dark" id="load-more-comments" v-show="!total">No comments</span>
    </div>
  </div>

  <hr />
  <!-- Comments END -->
  <!-- Reply START -->
  <div class="row">
    <div class="col-12">
      <h3>Leave a reply</h3>
      <label class="form-label">Your Comment</label>
      <textarea class="form-control" rows="3" id="comment-input" v-model="content"></textarea>
    </div>
    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-primary" id="add-comment-button" @click="addComment">Post comment</button>
    </div>
  </div>
  <!-- Reply END -->
</div>