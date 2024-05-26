<?php

namespace core\response;

/*
 **************************************************
 ** ResponseCode
 **************************************************
 **
 ** simple dictionnaire de codes pour réponses HTTP
 **
 **************************************************
 */

class ResponseCode
{
	public const OK = ['code' => 200, 'message' => 'OK'];
	public const CREATED = ['code' => 201, 'message' => 'Created'];
	public const ACCEPTED = ['code' => 202, 'message' => 'Accepted'];
	public const NO_CONTENT = ['code' => 204, 'message' => 'No Content'];
	public const MOVED_PERMANENTLY = ['code' => 301, 'message' => 'Moved Permanently'];
	public const FOUND = ['code' => 302, 'message' => 'Found'];
	public const SEE_OTHER = ['code' => 303, 'message' => 'See Other'];
	public const NOT_MODIFIED = ['code' => 304, 'message' => 'Not Modified'];
	public const BAD_REQUEST = ['code' => 400, 'message' => 'Bad Request'];
	public const UNAUTHORIZED = ['code' => 401, 'message' => 'Unauthorized'];
	public const FORBIDDEN = ['code' => 403, 'message' => 'Forbidden'];
	public const NOT_FOUND = ['code' => 404, 'message' => 'Not Found'];
	public const METHOD_NOT_ALLOWED = ['code' => 405, 'message' => 'Method Not Allowed'];
	public const INTERNAL_SERVER_ERROR = ['code' => 500, 'message' => 'Internal Server Error'];
	public const NOT_IMPLEMENTED = ['code' => 501, 'message' => 'Not Implemented'];
	public const BAD_GATEWAY = ['code' => 502, 'message' => 'Bad Gateway'];
	public const SERVICE_UNAVAILABLE = ['code' => 503, 'message' => 'Service Unavailable'];
}