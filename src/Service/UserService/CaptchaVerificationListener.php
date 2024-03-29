<?php

namespace App\Service\UserService;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class CaptchaVerificationListener
{
    private $httpClient;
    private $router;
    private $captchaSecret;
    

    public function __construct(HttpClientInterface $httpClient, RouterInterface $router, string $captchaSecret, private LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->router = $router;
        $this->captchaSecret = $captchaSecret;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->isMethod('POST') && strpos($request->getPathInfo(), '/login') !== false) {

            $captchaResponse = $request->request->get('g-recaptcha-response');
            if (!$this->verifyCaptcha($captchaResponse, $request->getClientIp())) {
                // CAPTCHA verification failed
                // Redirect to the login page with an error message
                $url = $this->router->generate('login', ['captcha_failed' => 1]);
                $event->setResponse(new RedirectResponse($url));
                $event->stopPropagation();
            }
        }

    }

    private function verifyCaptcha(string $captchaResponse, string $remoteIp): bool
    {

        if (empty($captchaResponse)) {
            return false;
        }

        try {
            $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret' => $this->captchaSecret,
                    'response' => $captchaResponse,
                    'remoteip' => $remoteIp,
                ],
            ]);

            $data = $response->toArray();
            return $data['success'];
        } catch (\Exception $e) {
            $this->logger->error('CAPTCHA verification failed with error: ' . $e->getMessage());
            return false;
        }
    }
}
