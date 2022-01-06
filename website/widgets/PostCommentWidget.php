<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class PostCommentWidget extends Widget
{
    public $post_id;
    public $commentListSectionId = 'comment-list-section';

    public function run()
    {
        if (!$this->post_id) return;
        $this->registerClientScript();
        return $this->render('post-comments', [
            'commentListSectionId' => $this->commentListSectionId,
        ]);
    }

    protected function getVueCode()
    {
        $fetchCommentUrl = Url::to(['post/comments'], true);
        $addCommentUrl = Url::to(['post/comment', 'id' => $this->post_id], true);
        $fetchReplyUrl = Url::to(['post/replies'], true);
        $commentListSectionId = $this->commentListSectionId;
        $csrfTokenName = Yii::$app->request->csrfParam;
        $csrfToken = Yii::$app->request->csrfToken;
        return "
        var app = new Vue({
            el: '#$commentListSectionId',
            data: {
                post_id: '$this->post_id',
                sort: 'desc',
                content: '',
                comments: [],
                total: 0,
            },
            watch: {
            },
            computed: {
                lastCommentId() {
                    const ids = this.comments.map(({ id }) => id);
                    if (!ids.length) return 0;
                    return this.sort === 'asc' ? Math.max(...ids) : Math.min(...ids);
                }
            },
            methods: {
                addComment() {
                    const content = this.content.trim();
                    if (!content) return;
                    axios.post('$addCommentUrl', { content, '$csrfTokenName': '$csrfToken' })
                    .then(({ data }) => {
                        const { status, comment } = data;
                        if (status) {
                            this.comments.push(this.repairComment(comment));
                            this.content = '';
                        }
                    });
                },
                addReply(parent_id) {
                    console.log(parent_id);
                    const parentComment = this.comments.find(({ id }) => id == parent_id);
                    console.log('parentComment', parentComment);
                    if (!parentComment) return;
                    const { replyContent } = parentComment
                    if (!replyContent) return;
                    axios.post('$addCommentUrl', { content: replyContent, parent_id, '$csrfTokenName': '$csrfToken' })
                    .then(({ data }) => {
                        const { status, comment } = data;
                        if (status) {
                            parentComment.replyContent = '';
                            parentComment.children.push(comment);
                        }
                    });
                },
                repairComment(c) {
                    c.created_at = moment(c.created_at).format('MMMM Do YYYY, h:mm a');
                    if (!c.parent_id) {
                        c.showReply = false;
                        c.replyContent = '';
                        c.children = [];
                        axios.get('$fetchReplyUrl' + '?id=' + c.id)
                            .then(({ data }) => {
                                const { status, comments } = data;
                                c.children = comments.map(c => this.repairComment(c));
                            });
                    }
                    return c;
                },
                loadMore() {
                    const params = new URLSearchParams({
                        sort: this.sort,
                        lastKey: this.lastCommentId,
                        post_id: this.post_id,
                    });
                    axios.get('$fetchCommentUrl?'+params.toString())
                    .then(({ data }) => {
                        let { status, comments = [], total = 0 } = data || {};
                        if (!status || !comments.length || !total) return;
                        comments = comments.map(c => this.repairComment(c));
                        this.comments = this.comments.concat(comments);
                        this.total = total;
                    });
                },
                changeSorting() {
                    this.comments = [];
                    this.loadMore();
                }
            },
            mounted() {
                this.loadMore();
            }
        });
        ";
    }

    protected function getCssCode() 
    {
        $commentListSectionId = $this->commentListSectionId;

        return "
        #$commentListSectionId #comment-list {
            max-height: 500px;
            overflow: scroll;
            overflow-x: hidden;
        }
        #$commentListSectionId #sort-comment {
            margin-right: 5px;
        }
        /* width */
        ::-webkit-scrollbar {
        width: 2px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
        background: #f1f1f1; 
        }
        
        /* Handle */
        ::-webkit-scrollbar-thumb {
        background: #888; 
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
        background: #555; 
        }";
    }

    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $js = $this->getVueCode();
        $view->registerJsFile('https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js', ['depends' => ['\yii\web\JqueryAsset']]);
        $view->registerJsFile('https://unpkg.com/axios/dist/axios.min.js', ['depends' => ['\yii\web\JqueryAsset']]);
        $view->registerJsFile('https://momentjs.com/downloads/moment.min.js', ['depends' => ['\yii\web\JqueryAsset']]);
        $view->registerJs($js);
        $css = $this->getCssCode();
        $view->registerCss($css);
    }
}