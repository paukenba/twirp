<?php
# Generated by the protocol buffer compiler (protoc-gen-twirp_php previous_exception).  DO NOT EDIT!
# source: service.proto

namespace Twitch\Twirp\Example;

use Google\Protobuf\Internal\GPBDecodeException;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twirp\BaseServerHooks;
use Twirp\Context;
use Twirp\ErrorCode;
use Twirp\RequestHandler;
use Twirp\ServerHooks;

/**
 * @see Haberdasher
 *
 * Generated from protobuf service <code>twitch.twirp.example.Haberdasher</code>
 */
final class HaberdasherServer extends TwirpServer implements RequestHandler
{
    const PATH_PREFIX = '/twirp/twitch.twirp.example.Haberdasher/';

    /**
     * @var Haberdasher
     */
    private $svc;

    /**
     * @var ServerHooks
     */
    private $hook;

    /**
     * @param Haberdasher $svc
     * @param ServerHooks|null    $hook
     * @param MessageFactory|null $messageFactory
     * @param StreamFactory|null  $streamFactory
     */
    public function __construct(
        Haberdasher $svc,
        ServerHooks $hook = null,
        MessageFactory $messageFactory = null,
        StreamFactory $streamFactory = null
    ) {
        parent::__construct($messageFactory, $streamFactory);

        if ($hook === null) {
            $hook = new BaseServerHooks();
        }

        $this->svc = $svc;
        $this->hook = $hook;
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $req
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $req)
    {
        $ctx = $req->getAttributes();
        $ctx = Context::withPackageName($ctx, 'twitch.twirp.example');
        $ctx = Context::withServiceName($ctx, 'Haberdasher');

        try {
            $ctx = $this->hook->requestReceived($ctx);
        } catch (\Throwable $e) {
            return $this->handleError($ctx, $e);
        } catch (\Exception $e) { // For PHP 5.6 compatibility
            return $this->handleError($ctx, $e);
        }

        if ($req->getMethod() !== 'POST') {
            $msg = sprintf('unsupported method "%s" (only POST is allowed)', $req->getMethod());

            return $this->handleError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        switch ($req->getUri()->getPath()) {
            case '/twirp/twitch.twirp.example.Haberdasher/MakeHat':
                return $this->handleMakeHat($ctx, $req);

            default:
                return $this->handleError($ctx, $this->noRouteError($req));
        }
    }

    private function handleMakeHat(array $ctx, ServerRequestInterface $req)
    {
        $header = $req->getHeaderLine('Content-Type');
        $i = strpos($header, ';');

        if ($i === false) {
            $i = strlen($header);
        }

        $respHeaders = [];
        $ctx[Context::RESPONSE_HEADER] = &$respHeaders;

        switch (trim(strtolower(substr($header, 0, $i)))) {
            case 'application/json':
                $resp = $this->handleMakeHatJson($ctx, $req);
                break;

            case 'application/protobuf':
                $resp = $this->handleMakeHatProtobuf($ctx, $req);
                break;

            default:
                $msg = sprintf('unexpected Content-Type: "%s"', $req->getHeaderLine('Content-Type'));

                return $this->handleError($ctx, $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath()));
        }

        foreach ($respHeaders as $key => $value) {
            $resp = $resp->withHeader($key, $value);
        }

        return $resp;
    }

    private function handleMakeHatJson(array $ctx, ServerRequestInterface $req)
    {
        $ctx = Context::withMethodName($ctx, 'MakeHat');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Twitch\Twirp\Example\Size();
            $in->mergeFromJsonString((string)$req->getBody());

            $out = $this->svc->MakeHat($ctx, $in);

            if ($out === null) {
                return $this->handleError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling MakeHat. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->handleError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request json'));
        } catch (\Throwable $e) {
            return $this->handleError($ctx, $e);
        } catch (\Exception $e) { // For PHP 5.6 compatibility
            return $this->handleError($ctx, $e);
        }

        $data = $out->serializeToJsonString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->messageFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    private function handleMakeHatProtobuf(array $ctx, ServerRequestInterface $req)
    {
        $ctx = Context::withMethodName($ctx, 'MakeHat');

        try {
            $ctx = $this->hook->requestRouted($ctx);

            $in = new \Twitch\Twirp\Example\Size();
            $in->mergeFromString((string)$req->getBody());

            $out = $this->svc->MakeHat($ctx, $in);

            if ($out === null) {
                return $this->handleError($ctx, TwirpError::newError(ErrorCode::Internal, 'received a null response while calling MakeHat. null responses are not supported'));
            }

            $ctx = $this->hook->responsePrepared($ctx);
        } catch (GPBDecodeException $e) {
            return $this->handleError($ctx, TwirpError::newError(ErrorCode::Internal, 'failed to parse request proto'));
        } catch (\Throwable $e) {
            return $this->handleError($ctx, $e);
        } catch (\Exception $e) { // For PHP 5.6 compatibility
            return $this->handleError($ctx, $e);
        }

        $data = $out->serializeToString();

        $body = $this->streamFactory->createStream($data);

        $resp = $this->messageFactory
            ->createResponse(200)
            ->withHeader('Content-Type', 'application/protobuf')
            ->withBody($body);

        $this->callResponseSent($ctx);

        return $resp;
    }

    /**
     * Writes errors in the response and triggers hooks.
     *
     * @param array                 $ctx
     * @param \Throwable|\Exception $e
     *
     * @return ResponseInterface
     */
    protected function handleError(array $ctx, $e)
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode($e->getErrorCode());
        $ctx = Context::withStatusCode($ctx, $statusCode);

        try {
            $ctx = $this->hook->error($ctx, $e);
        } catch (\Throwable $e) {
            // We have three options here. We could log the error, call the Error
            // hook, or just silently ignore the error.
            //
            // Logging is unacceptable because we don't have a user-controlled
            // logger; writing out to stderr without permission is too rude.
            //
            // Calling the Error hook would confuse users: it would mean the Error
            // hook got called twice for one request, which is likely to lead to
            // duplicated log messages and metrics, no matter how well we document
            // the behavior.
            //
            // Silently ignoring the error is our least-bad option. It's highly
            // likely that the connection is broken and the original 'err' says
            // so anyway.
        } catch (\Exception $e) {
             // For PHP 5.6 compatibility. Same as above.
         }

        $this->callResponseSent($ctx);

        if (!$e instanceof \Twirp\Error) {
            $e = TwirpError::errorFrom($e, 'internal error');
        }

        return $this->writeError($ctx, $e);
    }

    /**
     * Triggers response sent hook.
     *
     * @param array $ctx
     */
    private function callResponseSent(array $ctx)
    {
        try {
            $this->hook->responseSent($ctx);
        } catch (\Throwable $e) {
            // We have three options here. We could log the error, call the Error
            // hook, or just silently ignore the error.
            //
            // Logging is unacceptable because we don't have a user-controlled
            // logger; writing out to stderr without permission is too rude.
            //
            // Calling the Error hook could confuse users: this hook is triggered
            // by the error hook itself, which is likely to lead to
            // duplicated log messages and metrics, no matter how well we document
            // the behavior.
            //
            // Silently ignoring the error is our least-bad option. It's highly
            // likely that the connection is broken and the original 'err' says
            // so anyway.
        } catch (\Exception $e) {
            // For PHP 5.6 compatibility. Same as above.
        }
    }
}
