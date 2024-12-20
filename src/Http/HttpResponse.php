<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

namespace Eykj\AliyunApiGateway\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;

class HttpResponse implements ResponseInterface
{
    private $content;
    private $body;
    private $header;
    private $requestId;
    private $errorMessage;
    private $contentType;
    private $httpStatusCode;
    private $protocolVersion = '1.1';
    private $reasonPhrase = '';
    private $headers = [];

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function getHeader(string $name = '')
    {
        return $this->header;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody(): StreamInterface
    {
        return new SwooleStream((string)$this->body);
    }


    public function getRequestId()
    {
        return $this->requestId;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode  = $httpStatusCode;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType($contentType)
    {
        $this->contentType  = $contentType;
    }

    public function getSuccess()
    {
        if (200 <= $this->httpStatusCode && 300 > $this->httpStatusCode) {
            return true;
        }
        return false;
    }

    /**
     *根据headersize大小，区分返回的header和body
     */
    public function setHeaderSize($headerSize)
    {
        if (0 < $headerSize && 0 < strlen($this->content)) {
            $this->header = substr($this->content, 0, $headerSize);
            self::extractKey();
        }
        if (0 < $headerSize && $headerSize < strlen($this->content)) {
            $this->body = substr($this->content, $headerSize);
        }
    }

    /**
     *提取header中的requestId和errorMessage
     */
    private function extractKey()
    {
        if (0 < strlen($this->header)) {
            $headers = explode("\r\n", $this->header);
            foreach ($headers as $value) {
                if (strpos($value, "X-Ca-Request-Id:") !== false) {
                    $this->requestId = trim(substr($value, strlen("X-Ca-Request-Id:")));
                }
                if (strpos($value, "X-Ca-Error-Message:") !== false) {
                    $this->errorMessage = trim(substr($value, strlen("X-Ca-Error-Message:")));
                }
            }
        }
    }

    public function getStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->httpStatusCode = $code;
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): ResponseInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->headers[$name] ?? '';
    }

    public function withHeader(string $name, $value): ResponseInterface
    {
        $new = clone $this;
        $new->headers[$name] = (string)$value;
        return $new;
    }

    public function withAddedHeader(string $name, $value): ResponseInterface
    {
        $new = clone $this;
        $new->headers[$name] = ($this->headers[$name] ?? '') . ',' . (string) $value;
        return $new;
    }

    public function withoutHeader(string $name): ResponseInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function withBody(StreamInterface $body) {}
}
