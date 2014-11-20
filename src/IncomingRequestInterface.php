<?php

namespace Psr\Http\Message;

/**
 * Representation of an incoming, server-side HTTP request.
 *
 * Per the HTTP specification, this interface includes accessors for
 * the following:
 *
 * - Protocol version
 * - HTTP method
 * - URL
 * - Headers
 * - Message body
 *
 * Additionally, it encapsulates all data as it has arrived to the 
 * application from the PHP environment, including:
 *
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body parameters (generally from $_POST)
 *
 * Server and upload files MUST be immutable, as they represent the result of PHP
 * processes that cannot be recreated in userland. Cookies, query string arguments,
 * and body parameters are mutable to mimic PHP's policy around the equivalent
 * superglobals, real-world use cases, and equivalent functionality in other
 * languages.
 *
 * Additionally, this interface recognizes the utility of introspecting a
 * request to derive and match additional parameters (e.g., via URI path 
 * matching, decrypting cookie values, deserializing non-form-encoded body
 * content, matching authorization headers to users, etc). These parameters
 * are stored in an "attributes" property, which MUST be mutable.
 */
interface IncomingRequestInterface extends MessageInterface
{
    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod();

    /**
     * Retrieves the request URL.
     *
     * The URL will contain the path information, and, if present and non-empty, the
     * query string, and these two items ONLY. As such, it fulfills the "origin-form"
     * request target of RFC 7230.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3
     * @return string Returns the URL as a string. The URL MUST contain the path
     *     information, and optionally the query string if present, per the
     *     origin-form request target specification of RFC 7230 section 5.3.
     */
    public function getUrl();

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment, 
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT 
     * REQUIRED to originate from $_SERVER.
     * 
     * @return array
     */
    public function getServerParams();

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The assumption is these are injected during instantiation, typically
     * from PHP's $_COOKIE superglobal. The data IS NOT REQUIRED to come from
     * $_COOKIE, but MUST be compatible with the structure of $_COOKIE.
     *
     * @return array
     */
    public function getCookieParams();

    /**
     * Set cookie parameters.
     *
     * Inject the request object with cookie parameters, replacing any
     * previously set.
     *
     * See getCookieParams(), above, for more information.
     *
     * @param array $cookies Cookie key/value pairs to set.
     * @return void
     */
    public function setCookieParams(array $cookies);

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's `parse_str()` would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * @return array
     */
    public function getQueryParams();

    /**
     * Set query string arguments.
     *
     * Inject the request object with query string arguments, replacing any
     * previously set.
     *
     * See getQueryParams(), above, for more information.
     *
     * @param array $params Deserialized query string arguments.
     * @return void
     */
    public function setQueryParams(array $params);

    /**
     * Retrieve the upload file metadata.
     *
     * This method MUST return file upload metadata in the same structure
     * as PHP's $_FILES superglobal.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_FILES superglobal, or MAY be derived from other sources.
     *
     * @return array Upload file(s) metadata, if any.
     */
    public function getFileParams();

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request body can be deserialized to an array, this method MAY be
     * used to retrieve them. These MAY be injected during instantiation from
     * PHP's $_POST superglobal. The data IS NOT REQUIRED to come from $_POST,
     * but MUST be an array.
     *
     * @return array The deserialized body parameters, if any.
     */
    public function getBodyParams();

    /**
     * Set request body parameters.
     *
     * Inject the request object with deserialized message body parameters,
     * overwriting any previously set.
     *
     * See getBodyParams(), above, for more information.
     *
     * @param array $params Deserialized message body parameters.
     * @return void
     */
    public function setBodyParams(array $params);

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes();

    /**
     * Retrieve a single derived request attribute.
     * 
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     * 
     * @see getAttributes()
     * @param string $attribute Attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($attribute, $default = null);

    /**
     * Set attributes derived from the request.
     *
     * This method allows setting request attributes, as described in
     * getAttributes().
     *
     * @see getAttributes()
     * @param array $attributes Attributes derived from the request.
     * @return void
     */
    public function setAttributes(array $attributes);

    /**
     * Set a single derived request attribute.
     * 
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * @see getAttributes()
     * @param string $attribute The attribute name.
     * @param mixed $value The value of the attribute.
     * @return void
     */
    public function setAttribute($attribute, $value);
}
