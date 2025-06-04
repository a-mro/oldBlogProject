<?php
session_start();

class PostsController extends Controller
{

    public function __construct($model, $action)
    {

        parent::__construct($model, $action);
        $this->_setModel($model);
    }

    public function index()
    {

        try {
            $posts = $this->_model->getPosts();
            $this->_view->set('posts', $posts);
            $this->_view->set('title', 'Blog Posts');

            return $this->_view->output();
        } catch (Exception $e) {

            echo "Application error:" . $e->getMessage();
        }


    }

    public function newpost()
    {

        try {
            $categories = $this->_model->getCategories();
            $this->_view->set('title', 'New Post');
            $this->_view->set('categories', $categories);

            return $this->_view->output();
        } catch (Exception $e) {

            echo "Application error:" . $e->getMessage();
        }


    }


    public function fullarticle($postID)
    {

        try {

            $post = $this->_model->getPostById((int)$postID);
            $comments = $this->_model->getPostComments((int)$postID);

            if ($post) {
                $this->_view->set('title', $post['title']);
                $this->_view->set('articleBody', $post['text']);
                $this->_view->set('datePublished', $post['timestamp']);
                $this->_view->set('post_id', $postID);
                $this->_view->set('user', $post['username']);
            } else {
                $this->_view->set('title', 'Invalid article ID');
                $this->_view->set('noArticle', true);
            }

            $this->_view->set('comments', $comments);

            return $this->_view->output();


        } catch (Exception $e) {

            echo "Application error:" . $e->getMessage();
        }
    }

    public function commentPost()
    {

        $comment = new PostsModel();
        $userid = $_SESSION["user_id"];
        $postid = $_POST['post_id'];
        $text = $_POST['text'];
        $purifier = new HTMLPurifier();
        $text = $purifier->purify($text);

        try {
            $comment->saveComment($userid, $text, $postid);
        } catch (Exception $e) {

            echo "Application error:" . $e->getMessage();
        }


        header("Location: /posts/fullarticle/$postid");
    }

    public function userNewPost()
    {
        $post = new PostsModel();
        $userid = $_SESSION["user_id"];
        $cat = $_POST['category_id'];
        $text = $_POST['text'];
        $title = $_POST['title'];

        $purifier = new HTMLPurifier();
        $text = $purifier->purify($text);
        $title = $purifier->purify($title);

        try {
            $post->newComment($userid, $cat, $text, $title);
        } catch (Exception $e) {
            echo "Application error:" . $e->getMessage();
        }
    }

    public function submitPost()
    {

        if (!isset($_POST['newpostFormSubmit'])) {
            header('Location: /posts/index');
        }

        $errors = array();
        $check = true;


        $userid = isset($_SESSION["user_id"]) ? trim($_SESSION["user_id"]) : null;
        $title = isset($_POST['title']) ? trim($_POST['title']) : "";
        $category = isset($_POST['category']) ? trim($_POST['category']) : null;
        $content = isset($_POST['editor']) ? trim($_POST['editor']) : "";

        if ($userid == null) {

            $check = false;
            array_push($errors, "You are not allowed to post, login first");
        }

        if (empty($title)) {

            $check = false;
            array_push($errors, "You must specify a title!");

        }

        if (empty($content)) {

            $check = false;
            array_push($errors, "You can't submit an empty post!");
        }


        if (!$check) {
            $this->_setView('postresult');
            $this->_view->set('title', 'Invalid form data!');
            $this->_view->set('errors', $errors);
            return $this->_view->output();
        }

        try {
            $purifier = new HTMLPurifier();
            $post = new PostsModel();
            $content = $purifier->purify($content);
            $title = $purifier->purify($title);
            $category = $purifier->purify($category);
            $post->newComment($userid, $category, $content, $title);


        } catch (Exception $e) {
            $this->_setView('postresult');
            $this->_view->set('title', 'There was an error saving the data!');
            $this->_view->set('saveError', $e->getMessage());
        }

        $this->_setView('postresult');
        $this->_view->set('title', 'Post Successful!');
        return $this->_view->output();

    }


}