<?php

namespace ride\application\mail\handler;

use ride\library\mail\handler\GenericMailHandler;
use ride\library\mail\transport\Transport;
use ride\library\mail\MailMessage;
use ride\library\system\file\browser\FileBrowser;

use ride\service\TemplateService;

/**
 * Implementation for a mail handler which supports the template engine.
 */
class TemplateMailHandler extends GenericMailHandler {

    /**
     * Instance of the template facade
     * @var \ride\service\TemplateService
     */
    private $templateService;

    /**
     * Template resource for the mails
     * @var string
     */
    private $templateResource;

    /**
     * Constructs a new mail handler
     * @param \ride\library\mail\transport\Transport $transport
     * @param \ride\library\system\file\browser\FileBrowser $fileBrowser
     * @param \ride\service\TemplateService $templateService
     * @param string $templateResource
     * @return null
     */
    public function __construct(Transport $transport, FileBrowser $fileBrowser, TemplateService $templateService, $templateResource = null) {
        parent::__construct($transport, $fileBrowser);

        $this->templateService = $templateService;
        $this->templateResource = $templateResource;
    }

    /**
     * Hook to process the message before sending it
     * @param \ride\library\mail\MailMessage $message
     * @return \ride\library\mail\MailMessage
     */
    protected function processMessage(MailMessage $message) {
        if ($this->templateResource === null) {
            return $message;
        }

        $body = $message->getMessage();
        $variables = array('body' => $body);

        $template = $this->templateFacade->createTemplate($this->templateResource, $variables);
        $body = $this->templateFacade->render($template);

        $message->setMessage($body);

        return $message;
    }

}
