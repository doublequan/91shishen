<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('TOKEN', "hsh2014");

class Weixin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        parse_str($_SERVER['QUERY_STRING'], $_GET);
    }

    public function index()
    {
        if ($this->_valid())
        {
            // 判读是不是只是验证
            $echostr = $this->input->get('echostr');
            if (!empty($echostr))
            {
                $this->load->view('weixin_valid', array('output' => $echostr));
            }
            else
            {
                // 实际处理用户消息
                $this->_responseMsg();
            }
        }
        else
        {
            $this->load->view('weixin_valid', array('output' => 'Error!'));
        }
    }

    // 用于接入验证
    private function _valid()
    {
        $token = TOKEN;
        $signature = $this->input->get('signature');
        $timestamp = $this->input->get('timestamp');
        $nonce = $this->input->get('nonce');

        $tmp_arr = array($token, $timestamp, $nonce);
        sort($tmp_arr);
        $tmp_str = implode($tmp_arr);
        $tmp_str = sha1($tmp_str);

        return ($tmp_str == $signature);
    }

    // 这里是处理消息的地方，在这里拿到用户发送的字符串
    private function _responseMsg()
    {
        $post_str = file_get_contents('php://input');

        if (!empty($post_str))
        {
            // 解析微信传过来的 XML 内容
            $post_obj = simplexml_load_string($post_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            $from_username = $post_obj->FromUserName;
            $to_username = $post_obj->ToUserName;
            $create_time = $post_obj->CreateTime;
            $msg_type = $post_obj->MsgType;

            switch ($msg_type) {

                case "text": //文本消息
                    
                    $content = $post_obj->Content; //用户消息内容

                    if(!empty(trim($content)))
                    {
                        $type = "text";
                        $content = $this->_parseMessage($content);

                        $data = array(
                            'to' => $from_username,
                            'from' => $to_username,
                            'type' => $type,
                            'content' => $content,
                        );
                        $this->load->view('weixin_text', $data);

                    }
                    else
                    {
                        $type = "text";
                        $content = "要不您还是说点什么！╭(╯^╰)╮";

                        $data = array(
                            'to' => $from_username,
                            'from' => $to_username,
                            'type' => $type,
                            'content' => $content,
                        );

                        $this->load->view('weixin_text', $data);
                    }
                    break;
                case "image": //图片消息
                    # code...
                    break;
                case "voice": //语音消息
                    # code...
                    break;
                case "video": //视频消息
                    # code...
                    break;
                case "location": //地理位置消息
                    # code...
                    break;
                case "link": //链接消息
                    # code...
                    break;
                case "event": //事件推送
                    $event = $post_obj->Event; //事件类型

                    switch ($event) {
                        case 'subscribe': //订阅事件
                            $event_key = $post_obj->EventKey;

                            if(!stripos($event_key, "qrscene_")) //通过查找朋友添加
                            {

                                $type = "text";
                                $content = "谢谢您的关注！\n /:#-0 本服务号处于测试阶段，在此期间欢迎调戏！";

                                $data = array(
                                    'to' => $from_username,
                                    'from' => $to_username,
                                    'type' => $type,
                                    'content' => $content,
                                );
                                $this->load->view('weixin_text', $data);
                            }
                            else
                            {

                            }

                            break;

                        case 'unsubscribe': //订阅事件

                                $type = "text";
                                $content = "走好不送！\n /:turn 怀念你我相伴的日子！";

                                $data = array(
                                    'to' => $from_username,
                                    'from' => $to_username,
                                    'type' => $type,
                                    'content' => $content,
                                );
                                $this->load->view('weixin_text', $data);
                            break;
                    }
                    break;                    
            }
        }
        else
        {
            $this->load->view('weixin_valid', array('output' => 'Error!'));
        }
    }

    // 解析用户输入的字符串
    private function _parseMessage($message)
    {
        log_message('debug', $message);

        // TODO: 在这里做一些字符串解析，比如分析某关键字，返回什么信息等等
        $content = "";
        $pos = strpos($message, "#");

        if(!$pos)
        {
            $content = $this->itpk($message) ? $this->itpk($message): "就这么任性，不搭理你！";
        }
        else
        {
            $msg_arr = explode("#", $message);

            $msg_key = trim($msg_arr[0]);
            $msg_val = htmlspecialchars(trim($msg_arr[1]));
            
            switch ($msg_key) {

                case "邀请码":
                    $content = $this->_getInviteCode($msg_val);
                    break;
                case "yqm":
                    $content = $this->_getInviteCode($msg_val);
                    break;     
                default:
                    $content = "小的不明白您想干啥！";
                    break;
            }
        }
        return $content;
    }

    private function _getInviteCode($username){

        $this->load->model('employee_model','employee');

        if($username)
        {
            $invite_result = $this->employee->selectInviteCode($username);
            if($invite_result)
            {

                $url= "http://m.100hl.com/page/invite.php?invite_code=$invite_result[invite_code]";
                $url = dwz($url);
                return "帮我完成个任务呗，点击 $url 完成注册。你还可以得到5元代金券！";
            }
            else
            {
                return "在ERP系统里未发现，贵尊的身影！";   
            }
        }
        else
        {
            return "参数视乎有问题！";
        }
        
    }


    public function itpk($message){

        $param_arr = array(
            'question'      => trim($message),
            'api_key'       => config_item('itpk_key'),
            'api_secret'    => config_item('itpk_secret'),
            );

        $url = config_item('itpk_url');

        $result = send_request( $url, $param_arr);

        if(is_json($result))
        {
            $result = json_decode($result,true);

            return "$result[title] \n $result[content]";
        }
        else
        {
            return $result;
        }
    }
}


/* End of file weixin.php */
/* Location: ./application/controllers/weixin.php */
