<?php

namespace Gini\Controller\CGI\AJAX\Strawberry;

class Editor extends \Gini\Controller\CGI {

    private function alert($msg) {
        header('Content-type: text/html; charset=UTF-8');
        $json = new \Gini\Module\Json();
        echo $json->encode(array('error' => 1, 'message' => $msg));
        exit;
    }

    function actionUpload($uniqid) {
        $me = _G('ME');
        if (!$me->id) return;

        //文件保存目录路径
        $save_path = APP_PATH . '/' . DATA_DIR . '/attached/';
        \Gini\File::ensureDir($save_path);
        //文件保存目录URL
        
        //定义允许上传的文件扩展名
        $ext_arr = [
            'image' => ['gif', 'jpg', 'jpeg', 'png', 'bmp'],
            'flash' => ['swf', 'flv'],
            'media' => ['swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'],
            'file' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2', 'pdf'],
        ];
        //最大文件大小
        $max_size = 1024 * 1000;
        $save_path = realpath($save_path) . '/';

        //PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch($_FILES['imgFile']['error']){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            $this->alert($error);
        }

        //有上传文件时
        if (empty($_FILES) !== false) return;

        $file_name = $_FILES['imgFile']['name'];
        $tmp_name = $_FILES['imgFile']['tmp_name'];
        $file_size = $_FILES['imgFile']['size'];
        
        if (!$file_name) $this->alert("请选择文件。");
        if (@is_dir($save_path) === false) $this->alert("上传目录不存在。");
        if (@is_writable($save_path) === false) $this->alert("上传目录没有写权限。");
        if (@is_uploaded_file($tmp_name) === false) $this->alert("上传失败。");
        if ($file_size > $max_size) $this->alert("上传文件大小超过限制。");

        //获得文件扩展名
        $file_ext = \Gini\File::extension($file_name);
        //检查扩展名
        $type = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
        if (in_array($file_ext, $ext_arr[$type]) === false) {
            $this->alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$type]) . "格式。");
        }
        
        $save_path .= $uniqid . "/";
        if (!file_exists($save_path)) \Gini\File::ensureDir($save_path);
        
        $seed = date("YmdHis") . '_' . rand(10000, 99999);
        $new = "{$seed}.{$file_ext}";
        $file_path = $save_path . $new;
        if (move_uploaded_file($tmp_name, $file_path) === false) $this->alert("上传文件失败。");

        @chmod($file_path, 0644);
        $file_url = URL("editor/get/{$uniqid}/{$seed}/{$file_ext}");
        $json = new \Gini\Module\Json();
        echo $json->encode(['error' => 0, 'url' => $file_url]);
        exit;
    }

}
