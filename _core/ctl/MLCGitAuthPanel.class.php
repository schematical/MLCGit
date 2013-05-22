<?php

class MLCGitAuthPanel extends MJaxPanel {
    public $lnkGitAuth = null;
    public function __construct($objParentControl, $strControlId = null) {
        parent::__construct($objParentControl, $strControlId);
        $this->strTemplate = __MLC_GIT_CORE_VIEW__ . '/' . get_class($this) . '.tpl.php';

        $this->lnkGitAuth = new MJaxLinkButton($this);
        $this->lnkGitAuth->Text = 'Authenticate';
        $this->lnkGitAuth->AddCssClass('btn btn-large btn-primary');
        $this->lnkGitAuth->AddAction($this, 'lnkGitAuth_click');
    }
    public function lnkGitAuth_click(){
        $strAuthUrl = MLCGitClient::Init()->GetAuthorizeUrl('//' . $_SERVER['SERVER_NAME']);
        $this->objForm->Redirect($strAuthUrl);
    }
}
