<?php

namespace Eykj\AliyunApiGateway;

use Eykj\AliyunApiGateway\Http\HttpRequest;
use Eykj\AliyunApiGateway\Http\HttpClient;
use Eykj\AliyunApiGateway\Constant\HttpMethod;
use Eykj\AliyunApiGateway\Constant\HttpHeader;
use Eykj\AliyunApiGateway\Constant\ContentType;
use Eykj\AliyunApiGateway\Constant\SystemHeader;
use function Hyperf\Support\env;

/**
 *请求示例
 *如一个完整的url为http://api.aaaa.com/createobject?key1=value&key2=value2
 *$host为http://api.aaaa.com
 *$path为/createobject
 *query为key1=value&key2=value2
 */
class Service
{
    private $appKey = "your appKey";
    private $appSecret = "your appSecret";
    //协议(http或https)://域名:端口，注意必须有http://或https://
    private $host = "http://test.alicloudapi.com";
    /**
     * 构造方法初始化配置参数
     */
    public function __construct($appKey, $appSecret, $host)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->host = $host;
    }

    /**
     * 获取请求实例
     */
    protected function request($path, $method)
    {
        return new HttpRequest($this->host, $path, $method, $this->appKey, $this->appSecret);
    }

    /**
     * get 请求
     */
    public function doGet(string $path, array $headers = [], array $querys = [], $debug = false)
    {
        //域名后、query前的部分
        $request = $this->request($path, HttpMethod::GET);

        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);

        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
        //如果是调用测试环境请设置
        $debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        //指定参与签名的header
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if ($headers) {
            foreach ($headers as $key => $node) {
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ($querys) {
            foreach ($querys as $key => $node) {
                $request->setQuery($key, $node);
            }
        }

        return HttpClient::execute($request);
    }

    /**
     * POST  非表单请求 String
     */
    public function doPostForm(string $path, array $headers = [], array $querys = [], array $bodys = [], $debug = false)
    {
        //域名后、query前的部分
        $request = $this->request($path, HttpMethod::POST);

        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_FORM);

        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
        $debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        //同时指定参与签名的header
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if ($headers) {
            foreach ($headers as $key => $node) {
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ($querys) {
            foreach ($querys as $key => $node) {
                $request->setQuery($key, $node);
            }
        }

        //注意：业务body部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ($bodys) {
            foreach ($bodys as $key => $node) {
                $request->setBody($key, $node);
            }
        }

        return HttpClient::execute($request);
    }


    /**
     * POST  非表单请求 String
     */
    public function doPostString(string $path, array $headers = [], array $querys = [], string $bodyContent = '', $debug = false)
    {
        //域名后、query前的部分
        $request = $this->request($path, HttpMethod::POST);
        //传入内容是json格式的字符串
        // $bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"base64_image_string(此行)\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\"side\":\"face(#此行此行)\"}\"}}]}";

        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);

        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
        $debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if ($headers) {
            foreach ($headers as $key => $node) {
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ($querys) {
            foreach ($querys as $key => $node) {
                $request->setQuery($key, $node);
            }
        }

        //注意：业务body部分，不能设置key值，只能有value
        if (strlen($bodyContent) > 0) {
            $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
            $request->setBodyString($bodyContent);
        }


        return HttpClient::execute($request);
    }

    /**
     * @author     :  Wangqs  2021/3/22
     * @description:  POST  非表单请求 Stream
     */
    public function doPostStream($path, array $headers = [], array $querys = [], array $bytes = [], string $bodyContent='', $debug = false)
    {
        //域名后、query前的部分
        // $path = "/poststream";
        $request = $this->request($path, HttpMethod::POST);
        //Stream的内容
        // $bytes = array();
        //传入内容是json格式的字符串
        // $bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"base64_image_string(此行)\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\"side\":\"face(#此行此行)\"}\"}}]}";

        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_STREAM);

        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
        $debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        if ($headers) {
            foreach ($headers as $key => $node) {
                $request->setHeader($key, $node);
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ($querys) {
            foreach ($querys as $key => $node) {
                $request->setQuery($key, $node);
            }
        }

        //注意：业务body部分，不能设置key值，只能有value
        if ($bytes) {
            foreach ($bytes as $byte) {
                $bodyContent .= chr($byte);
            }
        }

        if (0 < strlen($bodyContent)) {
            $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
            $request->setBodyStream($bodyContent);
        }

        return HttpClient::execute($request);
    }

    //method=PUT方式和method=POST基本类似，这里不再举例

    /**
     *method=DELETE请求示例
     */
    public function doDelete($path, array $headers = [], array $querys = [], $debug = false)
    {
        $request =  $this->request($path, HttpMethod::DELETE);

        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);

        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
        //如果是调用测试环境请设置
        $debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        if ($headers) {
            foreach ($headers as $key => $node) {
                $request->setHeader($key, $node);
                //指定参与签名的header
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ($querys) {
            foreach ($querys as $key => $node) {
                $request->setQuery($key, $node);
            }
        }

        return HttpClient::execute($request);
    }


    /**
     *method=HEAD请求示例
     */
    public function doHead($path, array $headers = [], array $querys = [], $debug = false)
    {
        //域名后、query前的部分
        $request =  $this->request($path, HttpMethod::HEAD);

        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_TEXT);

        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_TEXT);
        //如果是调用测试环境请设置
        $debug && $request->setHeader(SystemHeader::X_CA_STAG, "TEST");


        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        if ($headers) {
            foreach ($headers as $key => $node) {
                $request->setHeader($key, $node);
                //指定参与签名的header
                $request->setSignHeader($key);
            }
        }

        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        if ($querys) {
            foreach ($querys as $key => $node) {
                $request->setQuery($key, $node);
            }
        }

        return HttpClient::execute($request);
    }
}
